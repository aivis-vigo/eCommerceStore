import { Component } from 'react';
import PropTypes from 'prop-types';
import ProductCard from './ProductCard';

interface AttributeOption {
    attribute_option_value: string;
    display_value: string;
    size_code?: string;
}

interface Attribute {
    attribute_name: string;
    attribute_options: AttributeOption[];
}

interface Product {
    product_id: string;
    name: string;
    attributes: Attribute[];
    size_options: AttributeOption[];
    images: { image_url: string }[];
    original_price: number;
    in_stock: number;
}

interface ProductListProps {
    category: string;
    products: Product[];
}

class ProductList extends Component<ProductListProps> {
    static propTypes = {
        category: PropTypes.string.isRequired,
        products: PropTypes.arrayOf(PropTypes.shape({
            product_id: PropTypes.string.isRequired,
            name: PropTypes.string.isRequired,
            attributes: PropTypes.array.isRequired,
            size_options: PropTypes.array.isRequired,
            images: PropTypes.arrayOf(PropTypes.shape({
                image_url: PropTypes.string.isRequired,
            })).isRequired,
            original_price: PropTypes.number.isRequired,
            in_stock: PropTypes.number.isRequired,
        })).isRequired,
    }

    render() {
        return (
            <div className="container mx-auto grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16 justify-items-center">
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
        );
    }
}

export default ProductList;
