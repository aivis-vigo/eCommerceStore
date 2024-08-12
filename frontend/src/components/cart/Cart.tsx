import { Component } from "react";
import CartItem from "./CartItem";
import formatCurrency from "../../utilities/formatCurrency";
import { CartContext } from "../../context/CartContext";
import { CartContextType } from "../../context/CartContext"; // Import the type

class Cart extends Component {
    static contextType = CartContext;

    // @ts-ignore
    declare context!: CartContextType;

    order = () => {
        if (this.context) {
            this.context.placeOrder();
        }
    };

    calculateTotal = () => {
        if (this.context) {
            return this.context.items.reduce((total, item) => {
                return total + (item.quantity * item.price);
            }, 0);
        }
        return 0;
    };

    render() {
        if (!this.context) {
            throw new Error("CartContext is undefined");
        }

        const { items } = this.context;
        const isDisabled = items.length === 0;

        return (
            <div className="absolute top-0 right-0 mt-cart-top z-20 bg-white w-80 max-h-80 overflow-y-auto shadow-lg">
                <div className="p-4">
                    <p className="text-sm">
                        <span className="font-semibold">My Bag,</span> <span
                        data-testid='cart-item-amount'>{items.length}</span> {items.length === 1 ? 'item' : 'items'}
                    </p>
                </div>
                <ul className="text-sm space-y-4">
                    {items.map(item => (
                        <CartItem
                            key={item.product_id + item.quantity}
                            product_id={item.product_id}
                            original_id={item.original_id}
                            name={item.name}
                            attributes={item.attributes}
                            selectedAttributes={item.selectedAttributes}
                            price={item.price}
                            image_url={item.image_url}
                            quantity={item.quantity}
                        />
                    ))}
                </ul>
                <div className="p-4 mb-4 border-gray-200">
                    <p className="text-sm flex flex-row justify-between font-semibold my-4">
                        Total
                        <span data-testid='cart-total'>
                            {formatCurrency(this.calculateTotal())}
                        </span>
                    </p>
                    <button
                        onClick={this.order}
                        disabled={isDisabled}
                        className={`w-full py-2 px-4 ${isDisabled ? 'bg-gray-500' : 'bg-green-500'} text-white mt-2`}
                    >
                        Place order
                    </button>
                </div>
            </div>
        );
    }
}

export default Cart;
