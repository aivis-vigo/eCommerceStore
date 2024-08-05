import {useParams} from "react-router-dom";
import Products from "../products/Products.tsx";

// CategoryWrapper component to use URL params
const CategoryWrapper = () => {
    const { category } = useParams();

    return <Products category={category} />;
};

export default CategoryWrapper;