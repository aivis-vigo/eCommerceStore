import React from "react";
import {useQuery} from "@apollo/client";
import {GET_ALL_PRODUCTS} from "../Queries/queries";

// todo: make query only for product to be shown

const Product = () => {
    const {loading, error, data} = useQuery(GET_ALL_PRODUCTS);

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error.message}</p>;

    return (
        <>
            {data.products.map((product) => (
                <div>{product.name}</div>
            ))}
        </>
    )
}

export default Product;