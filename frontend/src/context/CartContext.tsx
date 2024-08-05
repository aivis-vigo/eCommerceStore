import {createContext, useEffect, useState} from 'react';
import generateItemId from "../utilities/generateItemId.ts";

export const CartContext = createContext();

/* todo: cart is emptied after order is placed */
/* todo: cart has a disabled and greyed out button when it's empty */
/* todo: product_id needs to stay the same for it to be connected with products table (problem started in a place when product names was used to generate ids with selected attributes) */
/* todo: pass cleaned cart items array to mutation so there wouldn't be side effects */

const CartProvider = ({children}) => {
    const storedItems = JSON.parse(localStorage.getItem("shopping-cart")) || [];
    const [items, setItems] = useState(storedItems);
    const [totalPrice, setTotalPrice] = useState(0);
    const [isCartOpen, setIsCartOpen] = useState(false);

    //localStorage.clear();

    useEffect(() => {
        localStorage.setItem("shopping-cart", JSON.stringify(items));
    }, [items]);

    const addItemToCart = (newItem) => {
        setItems(prevItems => {
            const existingItemIndex = prevItems.findIndex(item => item.id === newItem.id);

            // -1 is returned if no match is found
            const updatedItems = existingItemIndex >= 0
                ? prevItems.map((item, index) =>
                    index === existingItemIndex
                        ? { ...item, quantity: item.quantity + newItem.quantity }
                        : item
                )
                : [...prevItems, newItem];

            // Open the cart overlay after adding the item
            setIsCartOpen(true);

            return updatedItems;
        });
    };

    const updateItemQuantity = (id: string, quantity: number) => {
        setItems(prevItems =>
            prevItems.map(item =>
                item.id === id ? {...item, quantity} : item
            )
        );
    };

    const updateSelectedOption = (productId, name, attributes) => {
        const newId = generateItemId(name, attributes);

        setItems(prevItems => {
            const updatedItems = [];

            // Helper function to check if an item with the same attributes already exists
            const findExistingItemIndex = (item) => {
                return updatedItems.findIndex(updatedItem =>
                    updatedItem.id === newId &&
                    JSON.stringify(updatedItem.selectedAttributes) === JSON.stringify(item.selectedAttributes)
                );
            };

            prevItems.forEach(item => {
                if (item.id === productId) {
                    const updatedItem = { ...item, id: newId, selectedAttributes: attributes };

                    const existingItemIndex = findExistingItemIndex(item);

                    if (existingItemIndex >= 0) {
                        updatedItems[existingItemIndex].quantity += item.quantity;
                    } else {
                        updatedItems.push(updatedItem);
                    }
                } else {
                    const existingItemIndex = findExistingItemIndex(item);
                    if (existingItemIndex === -1) {
                        updatedItems.push(item);
                    } else {
                        updatedItems[existingItemIndex].quantity += (item.quantity / 2);
                    }
                }
            });

            return updatedItems;
        });
    };

    const toggleCart = () => {
        setIsCartOpen(prevState => !prevState);
    };

    return (
        <CartContext.Provider value={{
            items,
            totalPrice,
            isCartOpen,
            setIsCartOpen,
            addItemToCart,
            updateItemQuantity,
            updateSelectedOption,
            toggleCart,
        }}>
            {children}
        </CartContext.Provider>
    );
};

export default CartProvider;
