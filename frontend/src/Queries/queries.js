import {gql} from '@apollo/client';

export const GET_ALL_CATEGORIES = gql`
    query Query {
        categories {
            product_category_id
            category_name
        }
    }
`

export const GET_ALL_PRODUCTS = gql`
    query Query {
        products {
            name
        }
    }
`

export const GET_SPECIFIC_PRODUCT = gql`
    query Query($product_id: String!) {
        product(product_id: $product_id) {
            name
            description
        }
    }
`

export const GET_TECH_PRODUCTS = gql`
    query Query($category_name: String!) {
        product_category(category_name: $category_name) {
            product_id
            name
            original_price
            images {
                image_url
            }
        }
    }
`