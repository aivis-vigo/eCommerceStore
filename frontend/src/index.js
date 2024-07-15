import React from 'react';
import ReactDOM from 'react-dom/client';
import {
    createBrowserRouter,
    RouterProvider
} from "react-router-dom";
import Root from './routes/root';
import {ApolloClient, ApolloProvider, InMemoryCache} from "@apollo/client";
import reportWebVitals from "./reportWebVitals";
import "./index.css";
import ErrorPage from "./error-page";
import Product from "./components/Product";
import NavigationBar from "./components/NavigationBar";
import TechProducts from "./components/TechProducts";
import Tech from "./routes/product";

const client = new ApolloClient({
    uri: 'http://localhost:8080/graphql',
    cache: new InMemoryCache()
});

const router = createBrowserRouter([
    {
        path: "/",
        element: <Root/>,
        errorElement: <ErrorPage/>
    },
    {
        path: "/:category",
        element: <TechProducts/>,
        errorElement: <ErrorPage/>
    },
    // todo: dynamic category
    {
        path: "/:cat_name/:productId",
        element: <Tech/>,
        errorElement: <ErrorPage/>
    }
]);

ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <ApolloProvider client={client}>
            <NavigationBar />
            <RouterProvider router={router}/>
        </ApolloProvider>
    </React.StrictMode>
);

reportWebVitals();