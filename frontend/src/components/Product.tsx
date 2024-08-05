import {GET_SPECIFIC_PRODUCT} from '../../queries.ts';
import formatCurrency from '../utilities/formatCurrency.ts';
import ImageCarousel from './products/ImageCarousel.tsx';
import {Component} from "react";
import PropTypes from "prop-types";
import {Query} from "@apollo/client/react/components";
import generateItemId from "../utilities/generateItemId.ts";
import {CartContext} from "../context/CartContext.tsx";
import parse from 'html-react-parser';

class Product extends Component {
    static contextType = CartContext;

    static propTypes = {
        productId: PropTypes.string.isRequired,
    }

    constructor(props) {
        super(props);

        this.state = {
            selectedAttributes: {},
            isAddToCartDisabled: true,
        }
        this.handleAttributeSelect = this.handleAttributeSelect.bind(this);
        this.handleAddToCart = this.handleAddToCart.bind(this);
    }

    handleAttributeSelect(attributeName, optionValue, product) {
        this.setState((prevState) => {
            const selectedAttributes = {
                ...prevState.selectedAttributes,
                [attributeName]: optionValue,
            }

            console.log(selectedAttributes, product.size_options);
            if (product.category_name === "Fashion") {
                const allAttributesSelected = Object.keys(selectedAttributes).length === 1;

                return {
                    selectedAttributes,
                    isAddToCartDisabled: !allAttributesSelected
                };
            } else {
                if (product.attributes.length === 0) {
                    return {
                        selectedAttributes,
                        isAddToCartDisabled: false
                    };
                }

                const allAttributesSelected = Object.keys(selectedAttributes).length === product.attributes.length;
                return {
                    selectedAttributes,
                    isAddToCartDisabled: !allAttributesSelected
                };
            }
        })
    }

    handleAddToCart(product) {
        const {addItemToCart} = this.context;
        const {selectedAttributes} = this.state;
        const generatedId = generateItemId(product.name, selectedAttributes);

        const item = {
            id: generatedId,
            name: product.name,
            attributes: product.category_name === "Fashion" ? [{
                'attribute_name': 'Size',
                'attribute_options': product.size_options
            }] : product.attributes,
            selectedAttributes: this.state.selectedAttributes,
            price: product.original_price,
            image_url: product.images[0].image_url,
            quantity: 1,
        }

        addItemToCart(item);
    }

    componentDidUpdate(prevProps, prevState) {
        const {selectedAttributes} = this.state;
        if (Object.keys(selectedAttributes).length === 0 && !this.state.isAddToCartDisabled && prevState.isAddToCartDisabled) {
            this.setState({isAddToCartDisabled: false});
        }
    }

    render() {
        return (
            <Query query={GET_SPECIFIC_PRODUCT} variables={{product_id: this.props.productId}}>
                {({loading, error, data}) => {
                    if (loading) return <p>Loading...</p>;
                    if (error) return <p>Error: {error.message}</p>;

                    const product = data.product;

                    return (
                        <div className="flex justify-center py-12">
                            <div className="flex w-3/4 h-full">
                                <div className="w-2/3 pr-4">
                                    <ImageCarousel imageUrls={product.images}/>
                                </div>
                                <div className="w-1/3 pl-4">
                                    <div className="pb-6">
                                        <h1 className="text-xl lg:text-2xl font-semibold leading-7 lg:leading-6 text-gray-800">
                                            {product.name}
                                        </h1>
                                    </div>

                                    {product.category_name == 'Fashion' ? (
                                        <div className="py-4 items-center justify-between border-gray-200">
                                            <p className="pb-2 text-base font-semibold leading-4 text-gray-800">
                                                Size:
                                            </p>
                                            <div className="flex items-center justify-start gap-x-2">
                                                {product.size_options.map((option, idx) => {
                                                    /* checks if there even is a size selected */
                                                    const isSelected = (this.state.selectedAttributes.hasOwnProperty("Size") && this.state.selectedAttributes['Size'] == option.size_code);

                                                    return (
                                                        <button
                                                            key={idx}
                                                            className={`w-16 h-8 text-center border ${isSelected ? 'bg-black text-white' : 'border-gray-400'} hover:bg-black hover:text-white cursor-pointer`}
                                                            onClick={() => this.handleAttributeSelect('Size', option.size_code, product)}
                                                        >
                                                            {option.size_code}
                                                        </button>
                                                    );
                                                })}
                                            </div>
                                        </div>
                                    ) : (
                                        product.attributes.length > 0 &&
                                        product.attributes.map((attribute, index) => (
                                            <div key={index}
                                                 className="py-4 items-center justify-between border-gray-200">
                                                <p className="pb-2 text-base font-semibold leading-4 text-gray-800">
                                                    {attribute.attribute_name.includes('Capacity') ? "CAPACITY" : attribute.attribute_name.toUpperCase()}:
                                                </p>
                                                <div className="flex items-center justify-start gap-x-2">
                                                    {attribute.attribute_options.map((option, idx) => {
                                                        const isSelected = this.state.selectedAttributes[attribute.attribute_name] === option.attribute_option_value;

                                                        return (
                                                            <button
                                                                key={idx}
                                                                className={`
                                                                ${attribute.attribute_name === 'Color'
                                                                    ? `w-7 h-7 ${isSelected ? 'border-2 border-green-500' : 'border'} hover:border-green-500`
                                                                    : `w-16 h-8 text-center border ${isSelected ? 'bg-black text-white' : 'border-gray-400'} hover:bg-black hover:text-white`
                                                                }
                                                                cursor-pointer
                                                            `}
                                                                style={{backgroundColor: attribute.attribute_name === 'Color' ? option.attribute_option_value : ''}}
                                                                onClick={() => this.handleAttributeSelect(attribute.attribute_name, option.attribute_option_value, product)}
                                                            >
                                                                {attribute.attribute_name !== 'Color' ? option.display_value : ''}
                                                            </button>
                                                        );
                                                    })}
                                                </div>
                                            </div>
                                        )))}

                                    <div className="pb-6 flex flex-col border-b border-gray-200">
                                        <div
                                            className="text-xl lg:text-2xl font-semibold leading-7 lg:leading-6 text-gray-800 gap-y-2">
                                            <p className="mb-2 text-m">PRICE:</p>
                                            <p>{formatCurrency(product.original_price)}</p>
                                        </div>
                                    </div>

                                    {product.attributes.length > 0 || product.size_options.length > 0 ? (
                                        <button
                                            disabled={this.state.isAddToCartDisabled}
                                            onClick={() => this.handleAddToCart(product)}
                                            className={`w-full py-4 text-base leading-none flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800
        ${this.state.isAddToCartDisabled ? 'bg-gray-300 cursor-not-allowed' : 'bg-green-500 hover:bg-gray-700 text-white'}`}
                                            data-testid='add-to-cart'
                                        >
                                            ADD TO CART
                                        </button>
                                    ) : (
                                        <button
                                            onClick={() => this.handleAddToCart(product)}
                                            className="w-full py-4 text-base leading-none flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 bg-green-500 hover:bg-gray-700 text-white"
                                            data-testid='add-to-cart'
                                        >
                                            ADD TO CART
                                        </button>
                                    )}


                                    <div className="mt-8">
                                        {parse(`${product.description}`)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    )
                }}
            </Query>
        );
    }
}

export default Product;
