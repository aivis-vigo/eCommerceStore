import {Component} from "react";
import {NavLink} from "react-router-dom";
import PropTypes from "prop-types";
import formatCurrency from "../../utilities/formatCurrency";
import generateItemId from "../../utilities/generateItemId.ts";
import toKebabCase from "../../utilities/toKebabCase.ts";
import {CartContext} from "../../context/CartContext.tsx";

class ProductCard extends Component {
    static contextType = CartContext;

    static propTypes = {
        category: PropTypes.string.isRequired,
        product_id: PropTypes.string.isRequired,
        name: PropTypes.string.isRequired,
        attributes: PropTypes.array.isRequired,
        size_options: PropTypes.object.isRequired,
        image_url: PropTypes.string.isRequired,
        original_price: PropTypes.number.isRequired,
        in_stock: PropTypes.number.isRequired,
    }

    constructor(props) {
        super(props);
        this.addItemToCart = this.addItemToCart.bind(this);
    }

    addItemToCart() {
        const {addItemToCart} = this.context;
        const itemId = generateItemId(this.props.name, this.props.attributes);
        const defaultAttributes = this.props.category === 'fashion' ? this.getDefaultAttributes([{attribute_name: 'Size', attribute_options: this.props.size_options}]) : this.getDefaultAttributes(this.props.attributes);

        addItemToCart({
            id: itemId,
            name: this.props.name,
            attributes: this.props.category === 'fashion' ? [{attribute_name: "Size", attribute_options: this.props.size_options}] : this.props.attributes,
            selectedAttributes: defaultAttributes,
            price: this.props.original_price,
            image_url: this.props.image_url,
            quantity: 1,
        });
    }

    getDefaultAttributes(attributes) {
        if (attributes.length === 0) {
            return [];
        }

        if (attributes[0].attribute_name === 'Size') {
            return attributes.reduce((acc, attribute) => {
                acc[attribute.attribute_name] = attribute.attribute_options[0].size_code;
                return acc;
            }, {});
        }

        return attributes.reduce((acc, attribute) => {
            acc[attribute.attribute_name] = attribute.attribute_options[0].attribute_option_value;
            return acc;
        }, {});
    }

    render() {
        return (
            <>
                {this.props.in_stock === 1 ? (
                    <>
                        <NavLink
                            to={`/${this.props.category}/${this.props.product_id}`}
                            className="relative border-gray-300 p-4 hover:shadow-xl group"
                            data-testid={`product-${toKebabCase(this.props.name)}`}
                        >
                            <div className="h-48 w-full flex items-center justify-center overflow-hidden">
                                <img
                                    src={this.props.image_url}
                                    alt="Product"
                                    className="h-full w-full object-contain object-center"
                                />
                            </div>
                            <div className="flex justify-end -translate-y-5 -translate-x-3">

                                {/* quick shop button */}
                                <button
                                    onClick={(event) => {
                                        event.preventDefault();
                                        this.addItemToCart();
                                    }}
                                    className="absolute hidden group-hover:block bg-green-500 text-white py-2 px-2 rounded-full">
                                    {/* cart icon */}
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <div className="mt-4">
                                <h2 className="text-lg text-gray-500 font-light">{this.props.name}</h2>
                                <div className="mt-1 flex justify-between items-center">
                                <span
                                    className="font-normal">{formatCurrency(this.props.original_price)}</span>
                                </div>
                            </div>
                        </NavLink>
                    </>
                ) : (
                    <NavLink
                        to={`/${this.props.category}`}
                        className="block"
                        data-testid={`product-${toKebabCase(this.props.name)}`}
                    >
                        <div
                            className="relative h-48 w-full flex items-center justify-center overflow-hidden">
                        <img
                                src={this.props.image_url}
                                alt="Product"
                                className="h-full w-full object-contain object-center filter grayscale opacity-50"
                            />
                            <p className="absolute inset-0 flex items-center justify-center text-gray-500 font-light bg-white bg-opacity-70">
                                OUT OF STOCK
                            </p>
                        </div>
                        <div className="text-gray-500 mt-4">
                            <h2 className="text-lg text-gray-500 font-light">{this.props.name}</h2>
                            <div className="mt-1 flex justify-between items-center">
                                <span
                                    className="text-gray-500 font-normal">{formatCurrency(this.props.original_price)}</span>
                            </div>
                        </div>
                    </NavLink>
                )
                }
            </>
        )
    }
}

export default ProductCard;