import { Component } from "react";
import { NavLink } from "react-router-dom";
import Cart from "./cart/Cart";
import { Query } from "@apollo/client/react/components";
import { GET_ALL_CATEGORIES } from "../graphql/queries.ts";
import PropTypes from "prop-types";
import { CartContext, CartContextType } from "../context/CartContext.tsx";
import { QueryResult } from "@apollo/client";

interface Category {
    category_name: string;
}

interface CategoriesData {
    categories: Category[];
}

class NavigationBar extends Component<{ pathName: string }> {
    static contextType = CartContext;

    static propTypes = {
        pathName: PropTypes.string.isRequired,
    };

    // @ts-ignore
    declare context!: CartContextType;

    render() {
        return (
            <Query query={GET_ALL_CATEGORIES}>
                {({ loading, error, data }: QueryResult<CategoriesData>) => {
                    if (loading) return <p>Loading...</p>;
                    if (error) return <p>Error: {error.message}</p>;

                    return (
                        <div>
                            <nav
                                className="relative flex justify-between bg-white text-black w-full px-5 xl:px-20 py-6 z-20"
                            >
                                {/* left section */}
                                <div className="flex items-center space-x-5">
                                    <ul className="hidden md:flex px-4 font-light text-sm space-x-12">
                                        {data?.categories.map((category, index) => (
                                            <li key={index}>
                                                <NavLink
                                                    to={`/${category.category_name.toLowerCase()}`}
                                                    className={
                                                        this.props.pathName.startsWith(
                                                            `/${category.category_name.toLowerCase()}`
                                                        )
                                                            ? "text-green-500 pb-navigation-bottom mb-[-2px] border-b-2 border-green-500"
                                                            : "text-black"
                                                    }
                                                    data-testid={
                                                        this.props.pathName.startsWith(
                                                            `/${category.category_name.toLowerCase()}`
                                                        )
                                                            ? "active-category-link"
                                                            : "category-link"
                                                    }
                                                >
                                                    {category.category_name.toUpperCase()}
                                                </NavLink>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                                {/* Center section for logo */}
                                <div className="flex justify-center flex-1">
                                    <a href="#" className="font-heading pr-24">
                                        <svg
                                            width="33"
                                            height="31"
                                            viewBox="0 0 33 31"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            {/* SVG paths */}
                                        </svg>
                                    </a>
                                </div>
                                {/* Right section for icons (hidden on small screens) */}
                                <div className="group relative xl:flex items-center space-x-5">
                                    <a
                                        className="flex items-center hover:text-gray-200"
                                        href="#"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            // Check if context is undefined before accessing
                                            if (this.context) {
                                                this.context.toggleCart();
                                            }
                                        }}
                                    >
                                        <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.5613 3.87359C19.1822 3.41031 18.5924 3.12873 17.9821 3.12873H5.15889L4.75914 1.63901C4.52718 0.773016 3.72769 0.168945 2.80069 0.168945H0.653099C0.295301 0.168945 0 0.450523 0 0.793474C0 1.13562 0.294459 1.418 0.653099 1.418H2.80069C3.11654 1.418 3.39045 1.61936 3.47434 1.92139L6.04306 11.7077C6.27502 12.5737 7.07451 13.1778 8.00152 13.1778H16.4028C17.3289 13.1778 18.1507 12.5737 18.3612 11.7077L19.9405 5.50575C20.0877 4.941 19.9619 4.33693 19.5613 3.87365L19.5613 3.87359ZM18.6566 5.22252L17.0773 11.4245C16.9934 11.7265 16.7195 11.9279 16.4036 11.9279H8.00154C7.68569 11.9279 7.41178 11.7265 7.32789 11.4245L5.49611 4.39756H17.983C18.1936 4.39756 18.4042 4.49824 18.5308 4.65948C18.6567 4.81994 18.7192 5.0213 18.6567 5.22266L18.6566 5.22252Z"
                                                fill="#43464E"/>
                                            <path
                                                d="M8.44437 13.9814C7.2443 13.9814 6.25488 14.9276 6.25488 16.0751C6.25488 17.2226 7.24439 18.1688 8.44437 18.1688C9.64445 18.1696 10.6339 17.2234 10.6339 16.0757C10.6339 14.928 9.64436 13.9812 8.44437 13.9812V13.9814ZM8.44437 16.9011C7.9599 16.9011 7.58071 16.5385 7.58071 16.0752C7.58071 15.6119 7.9599 15.2493 8.44437 15.2493C8.92885 15.2493 9.30804 15.6119 9.30804 16.0752C9.30722 16.5188 8.90748 16.9011 8.44437 16.9011Z"
                                                fill="#43464E"/>
                                            <path
                                                d="M15.6875 13.9814C14.4875 13.9814 13.498 14.9277 13.498 16.0752C13.498 17.2226 14.4876 18.1689 15.6875 18.1689C16.8875 18.1689 17.877 17.2226 17.877 16.0752C17.8565 14.9284 16.8875 13.9814 15.6875 13.9814ZM15.6875 16.9011C15.2031 16.9011 14.8239 16.5385 14.8239 16.0752C14.8239 15.612 15.2031 15.2493 15.6875 15.2493C16.172 15.2493 16.5512 15.612 16.5512 16.0752C16.5512 16.5188 16.1506 16.9011 15.6875 16.9011Z"
                                                fill="#43464E"/>
                                        </svg>

                                        {this.context.items.length > 0 && (
                                            <span
                                                className="absolute top-0 right-0 -mt-0.5 -mr-2 bg-black text-white text-xs font-semibold rounded-full h-4 w-4 flex items-center justify-center"
                                            >
                                                {this.context.items.length}
                                            </span>
                                        )}
                                    </a>
                                    {this.context.isCartOpen && <Cart/>}
                                </div>
                            </nav>
                            {this.context.isCartOpen && (
                                <div className="fixed inset-0 bg-gray-600 bg-opacity-30 z-10"></div>
                            )}
                        </div>
                    );
                }}
            </Query>
        );
    }
}

export default NavigationBar;
