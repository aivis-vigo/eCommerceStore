import {createContext, useState, useEffect, ReactNode, FC} from 'react';
import { useQuery } from '@apollo/client';
import {GET_ALL_CATEGORIES} from "../graphql/queries.ts";

interface NavigationContextType {
    defaultCategory: string;
}

export const NavigationContext = createContext<NavigationContextType | undefined>(undefined);

const NavigationProvider: FC<{ children: ReactNode }> = ({ children }) => {
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
