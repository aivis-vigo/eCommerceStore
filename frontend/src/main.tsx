import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.tsx'
import './index.css'
import {ApolloClient, ApolloProvider, InMemoryCache} from "@apollo/client";
import CartProvider from "./context/CartContext.tsx";

const client = new ApolloClient({
    uri: 'http://localhost:8080/graphql',
    cache: new InMemoryCache()
});

ReactDOM.createRoot(document.getElementById('root')!).render(
    <React.StrictMode>
        <ApolloProvider client={client}>
            <CartProvider>
                <App/>
            </CartProvider>
        </ApolloProvider>
    </React.StrictMode>,
)
