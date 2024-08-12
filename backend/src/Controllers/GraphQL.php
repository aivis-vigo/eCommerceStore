<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\CategoryService;
use App\Services\OrderLineService;
use App\Services\ProductService;
use App\Services\ShopOrderService;
use App\Type\Scalar\CategoryType;
use App\Type\Scalar\OrderItemInputType;
use App\Type\Scalar\ProductType;
use App\Type\TypeRegistry;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use Ramsey\Uuid\Uuid;
use Throwable;

class GraphQL
{
    static public function handle()
    {
        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'categories' => [
                        'type' => Type::listOf(TypeRegistry::type(CategoryType::class)),
                        'resolve' => function () {
                            return (new CategoryService())->findAll();
                        }
                    ],
                    // all products
                    'products' => [
                        'type' => Type::listOf(TypeRegistry::type(ProductType::class)),
                        'resolve' => function (): array {
                            return (new ProductService())->findAll();
                        }
                    ],
                    // find a specific product
                    'product' => [
                        'type' => TypeRegistry::type(ProductType::class),
                        'args' => [
                            'product_id' => new NonNull(Type::string()),
                        ],
                        'resolve' => function ($root, array $args): array {
                            return (new ProductService())->findOneById($args['product_id']);
                        }
                    ],
                    'product_category' => [
                        'type' => Type::listOf(TypeRegistry::type(ProductType::class)),
                        'args' => [
                            'category_name' => new NonNull(Type::string()),
                        ],
                        'resolve' => function ($root, array $args): array {
                            return (new ProductService())->findAllByCategory($args['category_name']);
                        }
                    ]
                ],
            ]);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'placeOrder' => [
                        'type' => new ObjectType([
                            'name' => 'PlaceOrderResponse',
                            'fields' => [
                                'success' => Type::nonNull(Type::boolean()),
                                'message' => Type::string(),
                                'orderId' => Type::string(),
                            ],
                        ]),
                        'args' => [
                            'items' => [
                                'type' => Type::nonNull(Type::listOf(TypeRegistry::type(OrderItemInputType::class))),
                            ],
                        ],
                        'resolve' => function ($root, array $args) {
                            error_log('placeOrder called with args: ' . print_r($args, true));

                            try {
                                $orderId = Uuid::uuid4()->toString();
                                $total = 0;

                                foreach ($args['items'] as $item) {
                                    error_log('Processing item: ' . print_r($item, true));
                                    // Validate and calculate
                                    if (!isset($item['original_id'], $item['quantity'], $item['price'])) {
                                        throw new \Exception('Invalid item data');
                                    }
                                    $total += $item['quantity'] * $item['price'];
                                }

                                // Insert order
                                (new ShopOrderService())->insert([
                                    'orderId' => $orderId,
                                    'total' => $total,
                                ]);

                                // Insert order lines
                                $orderLineService = new OrderLineService();
                                foreach ($args['items'] as $item) {
                                    $item['order_id'] = $orderId;
                                    $orderLineService->insert($item);
                                }

                                return [
                                    'success' => true,
                                    'message' => 'Order placed successfully',
                                    'orderId' => $orderId,
                                ];
                            } catch (Throwable $e) {
                                error_log('Error in placeOrder: ' . $e->getMessage());
                                throw $e; // Rethrow to be captured by GraphQL
                            }
                        },
                    ],
                ],
            ]);

            // See docs on schema options:
            // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                    ->setMutation($mutationType)
            );

            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $query = $input['query'];

            $variableValues = $input['variables'] ?? null;
            $rootValue = [];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}