import React from "react";
import {Link, useParams} from "react-router-dom";
import {useQuery} from "@apollo/client";
import {GET_TECH_PRODUCTS} from "../Queries/queries";

const Product = () => {
    const {category} = useParams();
    const {loading, error, data} = useQuery(GET_TECH_PRODUCTS, {
        variables: {
            category_name: category,
        }
    });

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error.message}</p>;

    return (
        <>
            <div className="container mx-auto grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 pt-6 gap-8">
                {data.product_category.map((product) => (
                    <Link to={`/${category}/${product.product_id}`} key={product.product_id} className="rounded border border-gray-300 p-4">
                            <img src={product.images[0].image_url}/>
                            <h2 className="text-lg font-semibold">{product.name}</h2>
                            <div className="mt-4 flex justify-between items-center">
                                {/* todo: price calculation should be in utils? */}
                                <span
                                    className="text-gray-900 font-bold">${product.original_price}</span>
                            </div>
                    </Link>
                ))}
            </div>
        </>
    )
}

export default Product;