import {Component} from "react";
import {GET_PRODUCTS} from "../../../queries.ts";
import ProductList from "./ProductList.js";
import PropTypes from "prop-types";
import {Query} from "@apollo/client/react/components";
import uppercaseFirst from "../../utilities/uppercaseFirst.ts";

class Products extends Component {
    static propTypes = {
        category: PropTypes.string.isRequired,
    }

    render() {
        return (
            <Query query={GET_PRODUCTS} variables={{category_name: this.props.category}}>
                {({loading, error, data}) => {
                    if (loading) return <p>Loading...</p>;
                    if (error) return <p>Error: {error.message}</p>;

                    return (
                        <>
                            <div className="px-16 pb-20">
                                <h1 className="font-normal text-2xl py-10">{uppercaseFirst(this.props.category)}</h1>
                                <ProductList category={this.props.category} products={data.product_category} />
                            </div>
                        </>
                    )
                }}
            </Query>
        )
    }
}

export default Products;