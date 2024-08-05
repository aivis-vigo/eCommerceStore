import { useLocation } from "react-router-dom";
import NavigationBar from "../NavigationBar.tsx";

const NavigationBarWrapper: React.FC = () => {
    const { pathname } = useLocation();
    return <NavigationBar pathName={pathname} />;
};

export default NavigationBarWrapper;
