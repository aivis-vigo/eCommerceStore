import { createContext, useState, useEffect } from 'react';
import { useQuery } from '@apollo/client';
import {GET_ALL_CATEGORIES} from "../../queries.ts";

export const NavigationContext = createContext();

const NavigationProvider = ({ children }) => {
    const { data, loading, error } = useQuery(GET_ALL_CATEGORIES);
    const [defaultCategory, setDefaultCategory] = useState('');

    useEffect(() => {
        if (!loading && !error && data) {
            const initialCategory = data.categories[0].category_name.toLowerCase();
            setDefaultCategory(initialCategory);
        }
    }, [data, loading, error]);

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error.message}</p>;

    return (
        <NavigationContext.Provider value={{ defaultCategory }}>
            {children}
        </NavigationContext.Provider>
    );
};

export default NavigationProvider;
