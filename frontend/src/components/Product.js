import React from "react";
import {useParams} from "react-router-dom";
import {useQuery} from "@apollo/client";
import {GET_SPECIFIC_PRODUCT} from "../Queries/queries";

// todo: make query only for product to be shown

const Product = () => {
    const {productId} = useParams();
    const {loading, error, data} = useQuery(GET_SPECIFIC_PRODUCT, {
        variables: {
            product_id: productId
        }
    });

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error.message}</p>;

    return (
        <>
            <div>{data.product.name}</div>
            <div>{data.product.description}</div>
        </>
    )
}

export default Product;