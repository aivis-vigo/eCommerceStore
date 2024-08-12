import React, { useContext, useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';
import {NavigationContext} from "../context/NavigationContext.tsx";

const DefaultCategoryRedirect: React.FC = () => {
    const navigationContext = useContext(NavigationContext);
    const [redirect, setRedirect] = useState<string | null>(null);

    useEffect(() => {
        if (navigationContext) {
            setRedirect(navigationContext.defaultCategory);
        }
    }, [navigationContext]);

    if (redirect) {
        return <Navigate to={`/${redirect}`} replace />;
    }

    return <p>Loading...</p>;
};

export default DefaultCategoryRedirect;
