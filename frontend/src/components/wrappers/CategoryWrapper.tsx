import { useParams } from 'react-router-dom';
import Products from '../products/Products';

const CategoryWrapper = () => {
    const { category } = useParams<{ category?: string }>();

    const categoryName = category || '';

    return <Products category={categoryName} />;
};

export default CategoryWrapper;
