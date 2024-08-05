import { useParams } from 'react-router-dom';
import Product from "../Product.tsx";

const ProductWrapper = () => {
    const {productId} = useParams();

    return <Product productId={productId} />;
};

export default ProductWrapper;
