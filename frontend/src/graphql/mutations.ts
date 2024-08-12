import {gql} from '@apollo/client';

export const PLACE_ORDER = gql`
    mutation PlaceOrder($items: [OrderItemInput!]!) {
        placeOrder(items: $items) {
            success
            message
            orderId
        }
    }
`;