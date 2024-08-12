import {gql} from '@apollo/client';

export const GET_ALL_CATEGORIES = gql`
    query Query {
        categories {
            product_category_id
            category_name
        }
    }
`

export const GET_SPECIFIC_PRODUCT = gql`
    query Query($product_id: String!) {
        product(product_id: $product_id) {
            product_id
            name
            description
            original_price
            category_name
            attributes {
                attribute_name
                attribute_options {
                    display_value
                    attribute_option_value
                }
            }
            size_options {
                size_code
            }
            images {
                image_url
            }
        }
    }
`

export const GET_PRODUCTS = gql`
    query Query($category_name: String!) {
        product_category(category_name: $category_name) {
            product_id
            category_name
            name
            attributes {
                attribute_name
                attribute_options {
                    display_value
                    attribute_option_value
                }
            }
            size_options {
                size_code
            }
            original_price
            in_stock
            images {
                image_url
            }
        }
    }
`