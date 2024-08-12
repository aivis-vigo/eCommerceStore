import React from "react";
import { useQuery } from '@apollo/client';
import { GET_SPECIFIC_PRODUCT } from '../graphql/queries.ts';
import formatCurrency from '../utilities/formatCurrency.ts';
import ImageCarousel from './products/ImageCarousel.tsx';
import generateItemId from "../utilities/generateItemId.ts";
import { CartContext, CartContextType } from "../context/CartContext.tsx";
import parse from 'html-react-parser';
import toKebabCase from "../utilities/toKebabCase.ts";

interface AttributeOption {
    attribute_option_value: string;
    display_value: string;
    size_code?: string;
}

interface Attribute {
    attribute_name: string;
    attribute_options: AttributeOption[];
}

interface Product {
    product_id: string;
    name: string;
    category_name: string;
    attributes: Attribute[];
    size_options: AttributeOption[];
    images: { image_url: string }[];
    original_price: number;
    description: string;
}

interface ProductProps {
    productId: string;
}

const Product: React.FC<ProductProps> = ({ productId }) => {
    const { addItemToCart } = React.useContext(CartContext) as CartContextType;
    const { data, loading, error } = useQuery(GET_SPECIFIC_PRODUCT, {
        variables: { product_id: productId },
    });

    const [selectedAttributes, setSelectedAttributes] = React.useState<{ [key: string]: string }>({});
    const [isAddToCartDisabled, setIsAddToCartDisabled] = React.useState(true);

    const handleAttributeSelect = (attributeName: string, optionValue: string, product: Product) => {
        setSelectedAttributes(prevState => {
            const newAttributes = {
                ...prevState,
                [attributeName]: optionValue,
            };

            if (product.category_name === "Fashion") {
                const allAttributesSelected = Object.keys(newAttributes).length === 1;
                setIsAddToCartDisabled(!allAttributesSelected);
            } else {
                if (product.attributes.length === 0) {
                    setIsAddToCartDisabled(false);
                } else {
                    const allAttributesSelected = Object.keys(newAttributes).length === product.attributes.length;
                    setIsAddToCartDisabled(!allAttributesSelected);
                }
            }

            return newAttributes;
        });
    };

    const handleAddToCart = (product: Product) => {
        const generatedId = generateItemId(product.product_id, selectedAttributes);
        const item = {
            product_id: generatedId,
            original_id: product.product_id,
            name: product.name,
            attributes: product.category_name === "Fashion" ? [{
                'attribute_name': 'Size',
                'attribute_options': product.size_options
            }] : product.attributes,
            selectedAttributes: selectedAttributes,
            price: product.original_price,
            image_url: product.images[0].image_url,
            quantity: 1,
        };

        addItemToCart(item);
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error.message}</p>;

    if (!data || !data.product) return <p>No product data</p>;

    const product: Product = data.product;

    return (
        <div className="flex justify-center py-12">
            <div className="flex w-full h-full px-20 gap-x-14">
                <div className="w-2/3">
                    <ImageCarousel imageUrls={product.images} />
                </div>
                <div className="w-1/3 pl-4">
                    <div>
                        <h1 className="pb-8 text-xl lg:text-2xl font-semibold leading-7 lg:leading-6 text-gray-800">
                            {product.name}
                        </h1>
                    </div>

                    {product.category_name === 'Fashion' ? (
                        <div data-testid='product-attribute-size' className="pb-8 items-center justify-between border-gray-200">
                            <p className="pb-2 text-base font-semibold leading-4 text-gray-800">
                                SIZE:
                            </p>
                            <div className="flex items-center justify-start gap-x-2">
                                {product.size_options.map((option, idx) => {
                                    const isSelected = (selectedAttributes.Size === option.size_code);

                                    return (
                                        <svg
                                            key={idx}
                                            width="63"
                                            height="45"
                                            viewBox="0 0 63 45"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            onClick={() => {
                                                if (option.size_code) {
                                                    handleAttributeSelect('Size', option.size_code, product);
                                                }
                                            }}
                                            className="cursor-pointer"
                                        >
                                            <rect
                                                x="0.5"
                                                y="0.5"
                                                width="62"
                                                height="44"
                                                fill={isSelected ? "#1D1F22" : "none"}
                                                stroke="#1D1F22"
                                            />
                                            <text
                                                x="50%"
                                                y="50%"
                                                dominantBaseline="middle"
                                                textAnchor="middle"
                                                fill={isSelected ? "#FFF" : "#000"}
                                                fontSize="14"
                                            >
                                                {option.size_code}
                                            </text>
                                        </svg>
                                    );
                                })}
                            </div>
                        </div>
                    ) : (
                        product.attributes.length > 0 &&
                        product.attributes.map((attribute, index) => (
                            <div data-testid={`product-attribute-${toKebabCase(attribute.attribute_name)}`} key={index} className="pb-8 items-center justify-between border-gray-200">
                                <p className="pb-2 text-base font-semibold leading-4 text-gray-800">
                                    {attribute.attribute_name.includes('Capacity') ? "CAPACITY" : attribute.attribute_name.toUpperCase()}:
                                </p>
                                <div className="flex items-center justify-start gap-x-2">
                                    {attribute.attribute_options.map((option, idx) => {
                                        const isSelected = selectedAttributes[attribute.attribute_name] === option.attribute_option_value;

                                        return (
                                            <svg
                                                key={idx}
                                                width={attribute.attribute_name === 'Color' ? "32" : "63"}
                                                height={attribute.attribute_name === 'Color' ? "32" : "45"}
                                                viewBox={attribute.attribute_name === 'Color' ? "0 0 32 32" : "0 0 63 45"}
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                onClick={() => handleAttributeSelect(attribute.attribute_name, option.attribute_option_value, product)}
                                                className="cursor-pointer"
                                            >
                                                {attribute.attribute_name === 'Color' ? (
                                                    <>
                                                        <rect
                                                            width="32"
                                                            height="32"
                                                            fill={option.attribute_option_value}
                                                        />
                                                        {isSelected && (
                                                            <rect
                                                                x="0.5"
                                                                y="0.5"
                                                                width="31"
                                                                height="31"
                                                                stroke="#5ECE7B"
                                                                strokeWidth="2"
                                                            />
                                                        )}
                                                    </>
                                                ) : (
                                                    <>
                                                        <rect
                                                            x="0.5"
                                                            y="0.5"
                                                            width="62"
                                                            height="44"
                                                            fill={isSelected ? "#1D1F22" : "none"}
                                                            stroke="#1D1F22"
                                                        />
                                                        <text
                                                            x="50%"
                                                            y="50%"
                                                            dominantBaseline="middle"
                                                            textAnchor="middle"
                                                            fill={isSelected ? "#FFF" : "#000"}
                                                            fontSize="14"
                                                        >
                                                            {option.display_value}
                                                        </text>
                                                    </>
                                                )}
                                            </svg>
                                        );
                                    })}
                                </div>
                            </div>
                        )))}

                    <div className="pb-6 flex flex-col border-b border-gray-200">
                        <div className="text-xl lg:text-2xl font-semibold leading-7 lg:leading-6 text-gray-800 gap-y-2">
                            <p className="mb-4 text-sm">PRICE:</p>
                            <p>{formatCurrency(product.original_price)}</p>
                        </div>
                    </div>

                    {(product.attributes.length > 0 || product.size_options.length > 0) ? (
                        <button
                            disabled={isAddToCartDisabled}
                            onClick={() => handleAddToCart(product)}
                            className={`w-full py-4 text-base leading-none flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800
        ${isAddToCartDisabled ? 'bg-gray-300 cursor-not-allowed' : 'bg-green-500 hover:bg-gray-700 text-white'}`}
                            data-testid='add-to-cart'
                        >
                            ADD TO CART
                        </button>
                    ) : (
                        <button
                            onClick={() => handleAddToCart(product)}
                            className="w-full py-4 text-base leading-none flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 bg-green-500 hover:bg-gray-700 text-white"
                            data-testid='add-to-cart'
                        >
                            ADD TO CART
                        </button>
                    )}

                    <div data-testid='product-description' className="mt-8 font-light">
                        {parse(`${product.description}`)}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Product;
