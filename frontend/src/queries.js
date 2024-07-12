import {gql} from '@apollo/client';

export const GET_ALL_PRODUCTS = gql`
    query {
        products {
            name
            original_price
            in_stock
            brand {
                brand_name
            }
            images {
                image_url
            }
        }
    }
`