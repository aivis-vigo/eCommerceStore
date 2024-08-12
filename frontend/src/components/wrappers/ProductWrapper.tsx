import { useParams } from 'react-router-dom';
import Product from "../Product.tsx";

const ProductWrapper = () => {
    const { productId } = useParams<{ productId?: string }>();

    if (!productId) {
        return <p>Product ID is missing</p>;
    }

    return <Product productId={productId} />;
};

export default ProductWrapper;
