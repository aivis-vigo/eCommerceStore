import PropTypes from "prop-types";
import formatCurrency from "../../utilities/formatCurrency";
import { Component } from "react";
import toKebabCase from "../../utilities/toKebabCase.ts";
import { CartContext } from "../../context/CartContext.tsx";

class CartItem extends Component {
    static contextType = CartContext;

    static propTypes = {
        id: PropTypes.string.isRequired,
        name: PropTypes.string.isRequired,
        attributes: PropTypes.array.isRequired,
        selectedAttributes: PropTypes.object.isRequired,
        price: PropTypes.number.isRequired,
        image_url: PropTypes.string.isRequired,
        quantity: PropTypes.number.isRequired,
    };

    constructor(props) {
        super(props);

        this.state = {
            quantity: props.quantity,
            current_price: props.quantity * props.price,
            selectedOptions: { ...this.props.selectedAttributes },
        };

        this.addOne = this.addOne.bind(this);
        this.removeOne = this.removeOne.bind(this);
        this.handleSelectOption = this.handleSelectOption.bind(this);
    }

    static getDerivedStateFromProps(nextProps, prevState) {
        if (nextProps.quantity !== prevState.quantity) {
            return {
                quantity: nextProps.quantity,
                current_price: nextProps.quantity * nextProps.price,
            };
        }
        return null;
    }

    addOne() {
        const { updateItemQuantity } = this.context;
        const quantity = this.state.quantity + 1;
        updateItemQuantity(this.props.id, quantity);
    }

    removeOne() {
        const { updateItemQuantity } = this.context;
        const quantity = this.state.quantity - 1;

        if (quantity > -1) {
            updateItemQuantity(this.props.id, quantity);
        }
    }

    handleSelectOption(attributeName, optionValue) {
        const { updateSelectedOption } = this.context;

        this.setState((prevState) => {
            const newSelectedOptions = {
                ...prevState.selectedOptions,
                [attributeName]: optionValue,
            };

            updateSelectedOption(
                this.props.id,
                this.props.name,
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
                        <p className="text-gray-500">{this.props.name}</p>
                        <p>{formatCurrency(this.state.current_price)}</p>
                        {this.props.attributes.length > 0 &&
                            this.props.attributes.map((attribute, index) => (
                                <div key={index} className={`flex flex-col ${index !== this.props.attributes.length - 1 ? 'my-2' : ''}`}>
                                    <p className="text-gray-500 mb-1">
                                        {attribute.attribute_name.includes("Capacity")
                                            ? "Capacity:"
                                            : attribute.attribute_name + ':'}
                                    </p>
                                    <div
                                        className="flex flex-row space-x-2 max-w-28"
                                        data-testid={`cart-item-attribute-${toKebabCase(
                                            attribute.attribute_name
                                        )}`}
                                    >
                                        {attribute.attribute_name === "Color" ? (
                                            /* box with colors */
                                            attribute.attribute_options.map((option, idx) => (
                                                <div
                                                    key={idx}
                                                    onClick={() => {
                                                        this.handleSelectOption(
                                                            attribute.attribute_name,
                                                            option.attribute_option_value
                                                        );
                                                    }}
                                                    style={{
                                                        backgroundColor: option.attribute_option_value,
                                                    }}
                                                    className={`w-4 h-4 cursor-pointer ${
                                                        this.props.selectedAttributes[
                                                            attribute.attribute_name
                                                            ] === option.attribute_option_value
                                                            ? "border-2 border-green-400"
                                                            : "border border-gray-300"
                                                    }`}
                                                    data-testid={`cart-item-attribute-${toKebabCase(
                                                        attribute.attribute_name
                                                    )}-${toKebabCase(option.display_value)}${
                                                        this.props.selectedAttributes[
                                                            attribute.attribute_name
                                                            ] === option.attribute_option_value
                                                            ? "-selected"
                                                            : ""
                                                    }`}
                                                />
                                            ))
                                        ) : (
                                            /* box with characters */
                                            attribute.attribute_options.map((option, idx) =>
                                                option.attribute_option_value !== undefined ? (
                                                    <div
                                                        key={idx}
                                                        onClick={() =>
                                                            this.handleSelectOption(
                                                                attribute.attribute_name,
                                                                option.attribute_option_value
                                                            )
                                                        }
                                                        className={`justify-center cursor-pointer border border-black w-14 h-6 flex items-center justify-center ${
                                                            this.props.selectedAttributes[
                                                                attribute.attribute_name
                                                                ] === option.attribute_option_value
                                                                ? "bg-black text-white"
                                                                : ""
                                                        }`}
                                                        data-testid={`cart-item-attribute-${toKebabCase(
                                                            attribute.attribute_name
                                                        )}-${toKebabCase(option.display_value)}${
                                                            this.props.selectedAttributes[
                                                                attribute.attribute_name
                                                                ] === option.attribute_option_value
                                                                ? "-selected"
                                                                : ""
                                                        }`}
                                                    >
                                                        {option.display_value}
                                                    </div>
                                                ) : (
                                                    <div
                                                        key={idx}
                                                        onClick={() =>
                                                            this.handleSelectOption(
                                                                attribute.attribute_name,
                                                                option.size_code
                                                            )
                                                        }
                                                        className={`justify-center cursor-pointer border border-black w-11 h-6 flex items-center justify-center ${
                                                            this.props.selectedAttributes[
                                                                attribute.attribute_name
                                                                ] === option.size_code
                                                                ? "bg-black text-white"
                                                                : ""
                                                        }`}
                                                        data-testid={`cart-item-attribute-${toKebabCase(
                                                            attribute.attribute_name
                                                        )}-${toKebabCase(option.size_code)}${
                                                            this.props.selectedAttributes[
                                                                attribute.attribute_name
                                                                ] === option.size_code
                                                                ? "-selected"
                                                                : ""
                                                        }`}
                                                    >
                                                        {option.size_code}
                                                    </div>
                                                )
                                            )
                                        )}
                                    </div>
                                </div>
                            ))}
                    </div>

                    <div className="flex flex-col justify-between items-center mr-2">
                        <button onClick={this.addOne} data-testid="cart-item-amount-increase">
                            <svg
                                className="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            >
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                        </button>
                        <p id="item-count">{this.state.quantity}</p>
                        <button onClick={this.removeOne} data-testid="cart-item-amount-decrease">
                            <svg
                                className="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            >
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                        </button>
                    </div>

                    <div className="w-36 flex items-center justify-center">
                        <img
                            src={this.props.image_url}
                            alt={this.props.name}
                            className="h-28 w-auto"
                        />
                    </div>
                </div>
            </div>
        );
    }
}

export default CartItem;
