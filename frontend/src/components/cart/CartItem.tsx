import { Component } from "react";
import formatCurrency from "../../utilities/formatCurrency";
import toKebabCase from "../../utilities/toKebabCase";
import { CartContext } from "../../context/CartContext";

interface AttributeOption {
    attribute_option_value: string;
    display_value: string;
    size_code?: string;
}

interface Attribute {
    attribute_name: string;
    attribute_options: AttributeOption[];
}

interface CartItemProps {
    product_id: string;
    original_id: string;
    name: string;
    attributes: Attribute[];
    selectedAttributes: Record<string, string>;
    price: number;
    image_url: string;
    quantity: number;
}

interface CartItemState {
    quantity: number;
    current_price: number;
    selectedOptions: Record<string, string>;
}

class CartItem extends Component<CartItemProps, CartItemState> {
    static contextType = CartContext;

    constructor(props: CartItemProps) {
        super(props);

        this.state = {
            quantity: props.quantity,
            current_price: props.quantity * props.price,
            selectedOptions: { ...props.selectedAttributes },
        };
    }

    static getDerivedStateFromProps(nextProps: CartItemProps, prevState: CartItemState) {
        if (nextProps.quantity !== prevState.quantity) {
            return {
                quantity: nextProps.quantity,
                current_price: nextProps.quantity * nextProps.price,
            };
        }
        return null;
    }

    addOne = () => {
        const { updateItemQuantity } = this.context as any;
        const quantity = this.state.quantity + 1;
        updateItemQuantity(this.props.product_id, quantity);
    }

    removeOne = () => {
        const { updateItemQuantity } = this.context as any;
        const quantity = this.state.quantity - 1;

        if (quantity > -1) {
            updateItemQuantity(this.props.product_id, quantity);
        }
    }

    handleSelectOption = (attributeName: string, optionValue: string) => {
        const { updateSelectedOption } = this.context as any;

        this.setState((prevState) => {
            const newSelectedOptions = {
                ...prevState.selectedOptions,
                [attributeName]: optionValue,
            };

            updateSelectedOption(
                this.props.product_id,
                this.props.original_id,
                newSelectedOptions
            );

            return {
                selectedOptions: newSelectedOptions,
            };
        });
    }

    render() {
        return (
            <div className="block px-4 py-2">
                <div className="flex flex-row justify-between w-full">
                    <div className="flex flex-col justify-between w-2/3 max-w-md">
                        <p className="font-light mt-1">{this.props.name}</p>
                        <p className={`${Object.keys(this.props.selectedAttributes).length === 0 ? '' : 'py-2'}`}>{formatCurrency(this.state.current_price)}</p>
                        {this.props.attributes.length > 0 &&
                            this.props.attributes.map((attribute, index) => (
                                <div key={index} className={`flex flex-col ${index !== this.props.attributes.length - 1 ? 'mb-2' : ''}`}>
                                    <p className="font-light mb-1">
                                        {attribute.attribute_name.includes("Capacity")
                                            ? "Capacity:"
                                            : attribute.attribute_name + ':'}
                                    </p>
                                    <div
                                        className="flex flex-row space-x-2 max-w-28 justify-items-center font-light"
                                        data-testid={`cart-item-attribute-${toKebabCase(
                                            attribute.attribute_name
                                        ).toLowerCase()}`}
                                    >
                                        {attribute.attribute_name === "Color" ? (
                                            attribute.attribute_options.map((option, idx) => {
                                                const safeOptionValue = option.attribute_option_value || '';
                                                return (
                                                    <div
                                                        key={idx}
                                                        onClick={() => {
                                                            this.handleSelectOption(attribute.attribute_name, safeOptionValue);
                                                        }}
                                                        data-testid={`cart-item-attribute-${toKebabCase(attribute.attribute_name).toLowerCase()}-${toKebabCase(option.display_value)}${
                                                            this.state.selectedOptions[attribute.attribute_name] === safeOptionValue ? "-selected" : ""
                                                        }`}
                                                        className="inline-block content-center"
                                                    >
                                                        {this.state.selectedOptions[attribute.attribute_name] === safeOptionValue ? (
                                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="2" y="2" width="16" height="16" fill={safeOptionValue} />
                                                                <rect x="0.5" y="0.5" width="19" height="19" stroke="#5ECE7B" />
                                                            </svg>
                                                        ) : (
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" className="hover:cursor-pointer">
                                                                <rect width="16" height="16" fill={safeOptionValue} />
                                                            </svg>
                                                        )}
                                                    </div>
                                                );
                                            })
                                        ) : (
                                            attribute.attribute_options.map((option, idx) => {
                                                const safeOptionValue = option.attribute_option_value || '';
                                                const safeSizeCode = option.size_code || '';
                                                return option.attribute_option_value !== undefined ? (
                                                    <div
                                                        data-testid={`cart-item-attribute-${toKebabCase(
                                                            attribute.attribute_name
                                                        ).toLowerCase()}-${toKebabCase(option.display_value)}${
                                                            this.state.selectedOptions[
                                                                attribute.attribute_name
                                                                ] === safeOptionValue
                                                                ? "-selected"
                                                                : ""
                                                        }`}
                                                        key={idx}
                                                        onClick={() =>
                                                            this.handleSelectOption(
                                                                attribute.attribute_name,
                                                                safeOptionValue
                                                            )
                                                        }
                                                        className={`justify-center cursor-pointer border border-black w-14 h-6 flex items-center justify-center ${
                                                            this.state.selectedOptions[
                                                                attribute.attribute_name
                                                                ] === safeOptionValue
                                                                ? "bg-black text-white"
                                                                : ""
                                                        }`}
                                                    >
                                                        {option.display_value}
                                                    </div>
                                                ) : (
                                                    <div
                                                        data-testid={`cart-item-attribute-${toKebabCase(attribute.attribute_name).toLowerCase()}-${toKebabCase(safeSizeCode)}${
                                                            this.state.selectedOptions[attribute.attribute_name] === safeSizeCode
                                                                ? "-selected"
                                                                : ""
                                                        }`}
                                                        key={idx}
                                                        onClick={() =>
                                                            this.handleSelectOption(
                                                                attribute.attribute_name,
                                                                safeSizeCode
                                                            )
                                                        }
                                                        className={`justify-center cursor-pointer border border-black w-11 h-6 flex items-center justify-center ${
                                                            this.state.selectedOptions[
                                                                attribute.attribute_name
                                                                ] === safeSizeCode
                                                                ? "bg-black text-white"
                                                                : ""
                                                        }`}
                                                    >
                                                        {safeSizeCode}
                                                    </div>
                                                );
                                            })
                                        )}
                                    </div>
                                </div>
                            ))}
                    </div>

                    <div className="flex flex-col justify-between items-center mr-2">
                        <button onClick={this.addOne} data-testid="cart-item-amount-increase">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_92234_46)">
                                    <path d="M12 8V16" stroke="#1D1F22" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 12H16" stroke="#1D1F22" stroke-linecap="round" stroke-linejoin="round"/>
                                    <rect x="0.5" y="0.5" width="23" height="23" stroke="#1D1F22"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_92234_46">
                                        <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                        <p id="item-count">{this.state.quantity}</p>
                        <button onClick={this.removeOne} data-testid="cart-item-amount-decrease">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" y="0.5" width="23" height="23" stroke="#1D1F22"/>
                                <path d="M8 12H16" stroke="#1D1F22" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <div className="w-36 flex items-center justify-center">
                        <img
                            src={this.props.image_url}
                            alt={this.props.name}
                            className="object-cover"
                        />
                    </div>
                </div>
            </div>
        );
    }
}

export default CartItem;
