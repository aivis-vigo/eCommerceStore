import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import NavigationProvider from "./context/NavigationContext.tsx";
import DefaultCategoryRedirect from './components/DefaultCategoryRedirect';
import NavigationBarWrapper from './components/wrappers/NavigationWrapper.tsx';
import CategoryWrapper from './components/wrappers/CategoryWrapper.tsx';
import ProductWrapper from './components/wrappers/ProductWrapper.tsx';
import ErrorPage from '../error-page.tsx';

class App extends React.Component {
    render() {
        return (
            <Router>
                <NavigationProvider>
                    <NavigationBarWrapper />
                    <Routes>
                        <Route path="/" element={<DefaultCategoryRedirect />} />
                        <Route path="/:category" element={<CategoryWrapper />} />
                        <Route path="/:cat_name/:productId" element={<ProductWrapper />} />
                        <Route path="*" element={<ErrorPage />} />
                    </Routes>
                </NavigationProvider>
            </Router>
        );
    }
}

export default App;
