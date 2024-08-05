import { Component } from "react";
import CartItem from "./CartItem";
import formatCurrency from "../../utilities/formatCurrency.ts";
import {CartContext} from "../../context/CartContext.tsx";


class Cart extends Component {
    static contextType = CartContext;

    constructor(props) {
        super(props);

        this.logItems = this.logItems.bind(this);
    }

    logItems() {
        //console.log(this.props.items);
        console.log("Cart context: ", this.context);
    }

    calculateTotal() {
        return this.context.items.reduce((total, item) => {
            return total + (item.quantity * item.price);
        }, 0);
    }

    render() {
        return (
            <div className="absolute top-0 right-0 mt-cart-top z-20 bg-white w-80 max-h-80 overflow-y-auto shadow-lg">
                <div className="p-4">
                    <p className="text-sm">
                        <span className="font-semibold">My Bag,</span> <span data-testid='cart-item-amount'>{this.context.items.length}</span> {this.context.items.length === 1 ? 'item' : 'items'}
                    </p>
                </div>
                <ul id="testing" className="py-2 text-sm space-y-4">
                    {this.context.items.map((item) => (
                        <CartItem
                            key={item.name + item.quantity}
                            id={item.id}
                            name={item.name}
                            attributes={item.attributes}
                            selectedAttributes={item.selectedAttributes}
                            price={item.price}
                            image_url={item.image_url}
                            quantity={item.quantity}
                        />
                    ))}
                </ul>
                <div className="p-4 border-gray-200">
                    <p className="text-sm flex flex-row justify-between font-semibold">
                        Total:
                        <span data-testid='cart-total'>
                            {formatCurrency(this.calculateTotal())}
                        </span>
                    </p>
                    <button onClick={this.logItems} className="w-full py-2 px-4 bg-green-500 text-white mt-2">
                        Place order
                    </button>
                </div>
            </div>
        );
    }
}

export default Cart;
