<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProductService;
use App\Type\Scalar\ProductType;
use App\Type\TypeRegistry;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use Throwable;

class GraphQL
{
    // todo: there can be multiple queries and each in it's own file
    static public function handle()
    {
        try {
            $queryType = new ObjectType([
                'name' => 'AllProducts',
                'fields' => [
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
                    ]
                ],
            ]);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'sum' => [
                        'type' => Type::int(),
                        'args' => [
                            'x' => ['type' => Type::int()],
                            'y' => ['type' => Type::int()],
                        ],
                        'resolve' => static fn($calc, array $args): int => $args['x'] + $args['y'],
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