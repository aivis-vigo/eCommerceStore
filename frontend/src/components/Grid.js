import React from "react";
import {useQuery} from "@apollo/client";
import {GET_ALL_PRODUCTS} from "../queries";

function Index() {
    const {loading, error, data} = useQuery(GET_ALL_PRODUCTS);

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error.message}</p>;

    return (
        <div className="container mx-auto grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 pt-6 gap-8">
            {data.products.map((product) => (
                <div key={product.id} className="rounded border border-gray-300 p-4">
                    <img src={product.images[0].image_url}/>
                    <h2 className="text-lg font-semibold">{product.name}</h2>
                    <div className="mt-4 flex justify-between items-center">
                        {/* todo: price calculation should be in utils? */}
                        <span
                            className="text-gray-900 font-bold">${product.original_price}</span>
                    </div>
                </div>
            ))}
        </div>
    );
}

export default Index;
