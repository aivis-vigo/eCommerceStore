import {Component} from 'react';
import ProductCard from "./ProductCard.js";
import PropTypes from "prop-types";

class ProductList extends Component {
    static propTypes = {
        category: PropTypes.string.isRequired,
        products: PropTypes.array.isRequired,
    }

    render() {
        return (
            <div className="container mx-auto grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 pt-6 gap-8">
                {this.props.products.map((product, index) => (
                    <ProductCard
                        key={index}
                        category={this.props.category}
                        product_id={product.product_id}
                        name={product.name}
                        attributes={product.attributes}
                        size_options={product.size_options}
                        image_url={product.images[0].image_url}
                        original_price={product.original_price}
                        in_stock={product.in_stock}
                    />
                ))}
            </div>
        )
    }
}

export default ProductList;