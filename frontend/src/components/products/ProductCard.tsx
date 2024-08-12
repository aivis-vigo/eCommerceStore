import { Component } from "react";
import { NavLink } from "react-router-dom";
import PropTypes from "prop-types";
import formatCurrency from "../../utilities/formatCurrency";
import generateItemId from "../../utilities/generateItemId.ts";
import toKebabCase from "../../utilities/toKebabCase.ts";
import { CartContext, CartContextType } from "../../context/CartContext.tsx";

interface ProductCardProps {
    category: string;
    product_id: string;
    name: string;
    attributes: any[];
    size_options: any[];
    image_url: string;
    original_price: number;
    in_stock: number;
}

class ProductCard extends Component<ProductCardProps> {
    static contextType = CartContext;
    // @ts-ignore
    declare context!: CartContextType;

    static propTypes = {
        category: PropTypes.string.isRequired,
        product_id: PropTypes.string.isRequired,
        name: PropTypes.string.isRequired,
        attributes: PropTypes.array.isRequired,
        size_options: PropTypes.array.isRequired,
        image_url: PropTypes.string.isRequired,
        original_price: PropTypes.number.isRequired,
        in_stock: PropTypes.number.isRequired,
    }

    addItemToCart = () => {
        const { addItemToCart } = this.context;
        const itemId = generateItemId(this.props.product_id, this.props.attributes);
        const defaultAttributes = this.props.category === 'fashion' ? this.getDefaultAttributes([{
            attribute_name: 'Size',
            attribute_options: this.props.size_options
        }]) : this.getDefaultAttributes(this.props.attributes);

        addItemToCart({
            product_id: itemId,
            original_id: this.props.product_id,
            name: this.props.name,
            attributes: this.props.category === 'fashion' ? [{
                attribute_name: "Size",
                attribute_options: this.props.size_options
            }] : this.props.attributes,
            selectedAttributes: defaultAttributes,
            price: this.props.original_price,
            image_url: this.props.image_url,
            quantity: 1,
        });
    }

    getDefaultAttributes = (attributes: any[]) => {
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
                            className="w-88 relative border-gray-300 p-4 hover:shadow-xl group"
                            data-testid={`product-${toKebabCase(this.props.name)}`}
                        >
                            <div className="h-64 w-full flex items-center justify-center overflow-hidden">
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
                                    <div className="p-1">
                                        <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.5613 3.87359C19.1822 3.41031 18.5924 3.12873 17.9821 3.12873H5.15889L4.75914 1.63901C4.52718 0.773016 3.72769 0.168945 2.80069 0.168945H0.653099C0.295301 0.168945 0 0.450523 0 0.793474C0 1.13562 0.294459 1.418 0.653099 1.418H2.80069C3.11654 1.418 3.39045 1.61936 3.47434 1.92139L6.04306 11.7077C6.27502 12.5737 7.07451 13.1778 8.00152 13.1778H16.4028C17.3289 13.1778 18.1507 12.5737 18.3612 11.7077L19.9405 5.50575C20.0877 4.941 19.9619 4.33693 19.5613 3.87365L19.5613 3.87359ZM18.6566 5.22252L17.0773 11.4245C16.9934 11.7265 16.7195 11.9279 16.4036 11.9279H8.00154C7.68569 11.9279 7.41178 11.7265 7.32789 11.4245L5.49611 4.39756H17.983C18.1936 4.39756 18.4042 4.49824 18.5308 4.65948C18.6567 4.81994 18.7192 5.0213 18.6567 5.22266L18.6566 5.22252Z"
                                                fill="#FFFFFF"/>
                                            <path
                                                d="M8.44437 13.9814C7.2443 13.9814 6.25488 14.9276 6.25488 16.0751C6.25488 17.2226 7.24439 18.1688 8.44437 18.1688C9.64445 18.1696 10.6339 17.2234 10.6339 16.0757C10.6339 14.928 9.64436 13.9812 8.44437 13.9812V13.9814ZM8.44437 16.9011C7.9599 16.9011 7.58071 16.5385 7.58071 16.0752C7.58071 15.6119 7.9599 15.2493 8.44437 15.2493C8.92885 15.2493 9.30804 15.6119 9.30804 16.0752C9.30722 16.5188 8.90748 16.9011 8.44437 16.9011Z"
                                                fill="#FFFFFF"/>
                                            <path
                                                d="M15.6875 13.9814C14.4875 13.9814 13.498 14.9277 13.498 16.0752C13.498 17.2226 14.4876 18.1689 15.6875 18.1689C16.8875 18.1689 17.877 17.2226 17.877 16.0752C17.8565 14.9284 16.8875 13.9814 15.6875 13.9814ZM15.6875 16.9011C15.2031 16.9011 14.8239 16.5385 14.8239 16.0752C14.8239 15.612 15.2031 15.2493 15.6875 15.2493C16.172 15.2493 16.5512 15.612 16.5512 16.0752C16.5512 16.5188 16.1506 16.9011 15.6875 16.9011Z"
                                                fill="#FFFFFF"/>
                                        </svg>
                                    </div>

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
                        className="block w-88 relative border-gray-300 p-4 hover:shadow-xl group"
                        data-testid={`product-${toKebabCase(this.props.name)}`}
                    >
                        <div
                            className="relative h-64 w-full flex items-center justify-center overflow-hidden">
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
