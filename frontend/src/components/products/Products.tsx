import { Component } from 'react';
import { GET_PRODUCTS } from '../../graphql/queries';
import ProductList from './ProductList';
import PropTypes from 'prop-types';
import { Query } from '@apollo/client/react/components';
import uppercaseFirst from '../../utilities/uppercaseFirst';

interface ProductsProps {
    category: string;
}

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

interface ProductData {
    product_category: Product[];
}

class Products extends Component<ProductsProps> {
    static propTypes = {
        category: PropTypes.string.isRequired,
    };

    render() {
        const { category } = this.props;

        return (
            <Query<ProductData> query={GET_PRODUCTS} variables={{ category_name: category }}>
                {({ loading, error, data }) => {
                    if (loading) return <p>Loading...</p>;
                    if (error) return <p>Error: {error.message}</p>;

                    return (
                        <>
                            <div className="px-20 pb-20">
                                <h1 className="font-light text-3xl pt-16 pb-24 pl-3">{uppercaseFirst(category)}</h1>
                                <ProductList category={category} products={data?.product_category || []} />
                            </div>
                        </>
                    );
                }}
            </Query>
        );
    }
}

export default Products;
