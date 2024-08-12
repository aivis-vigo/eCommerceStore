import { createContext, FC, ReactNode, useEffect, useState } from 'react';
import generateItemId from "../utilities/generateItemId.ts";
import { ApolloClient, InMemoryCache } from "@apollo/client";
import { PLACE_ORDER } from "../graphql/mutations.ts";

interface AttributeOption {
    attribute_option_value: string;
    display_value: string;
    size_code?: string;
}

interface Attribute {
    attribute_name: string;
    attribute_options: AttributeOption[];
}

interface SelectedAttributes {
    [attributeName: string]: string;
}

export interface Product {
    product_id: string;
    original_id: string;
    name: string;
    attributes: Attribute[];
    selectedAttributes: SelectedAttributes;
    price: number;
    image_url: string;
    quantity: number;
}

export interface CartContextType {
    items: Product[];
    totalPrice: number;
    isCartOpen: boolean;
    setIsCartOpen: React.Dispatch<React.SetStateAction<boolean>>;
    addItemToCart: (newItem: Product) => void;
    updateItemQuantity: (id: string, quantity: number) => void;
    updateSelectedOption: (productId: string, original_id: string, attributes: any) => void;
    toggleCart: () => void;
    placeOrder: () => void;
}

export const CartContext = createContext<CartContextType | undefined>(undefined);

interface CartProviderProps {
    children: ReactNode;
}

const CartProvider: FC<CartProviderProps> = ({ children }) => {
    const storedItems = localStorage.getItem("shopping-cart");
    const parsedItems = storedItems ? JSON.parse(storedItems) : [];
    const [items, setItems] = useState<Product[]>(parsedItems);
    const [isCartOpen, setIsCartOpen] = useState(false);

    useEffect(() => {
        localStorage.setItem("shopping-cart", JSON.stringify(items));
    }, [items]);

    const addItemToCart = (newItem: Product) => {
        setItems(prevItems => {
            const existingItemIndex = prevItems.findIndex(item => item.product_id === newItem.product_id);

            const updatedItems = existingItemIndex >= 0
                ? prevItems.map((item, index) =>
                    index === existingItemIndex
                        ? { ...item, quantity: item.quantity + newItem.quantity }
                        : item
                )
                : [...prevItems, newItem];

            setIsCartOpen(true);

            return updatedItems;
        });
    };

    const updateItemQuantity = (id: string, quantity: number) => {
        setItems(prevItems => {
            return prevItems.map(item =>
                item.product_id === id ? { ...item, quantity } : item
            ).filter(item => item.quantity > 0);
        });
    };

    const updateSelectedOption = (productId: string, original_id: string, attributes: SelectedAttributes) => {
        const newId = generateItemId(original_id, attributes);

        setItems(prevItems => {
            const updatedItems: Product[] = [];

            const findExistingItemIndex = (item: Product) => {
                return updatedItems.findIndex(updatedItem =>
                    updatedItem.product_id === newId &&
                    JSON.stringify(updatedItem.selectedAttributes) === JSON.stringify(item.selectedAttributes)
                );
            };

            prevItems.forEach(item => {
                if (item.product_id === productId) {
                    const updatedItem = { ...item, product_id: newId, selectedAttributes: attributes };

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

    const placeOrder = async () => {
        const cartItems = items.map(({ original_id, quantity, price }) => ({ original_id, quantity, price }));

        try {
            const client = new ApolloClient({
                uri: 'http://minimalistmall.com/api/src/Controllers/GraphQL.php',
                cache: new InMemoryCache()
            });
            const response = await client.mutate({
                mutation: PLACE_ORDER,
                variables: { items: cartItems }
            });

            console.log(response.data);
        } catch (error) {
            console.error('Error placing order:', error);
        }

        setItems([]);
        localStorage.removeItem("shopping-cart");
    }

    const totalPrice = items.reduce((total, item) => total + (item.price * item.quantity), 0);

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
            placeOrder
        }}>
            {children}
        </CartContext.Provider>
    );
};

export default CartProvider;
