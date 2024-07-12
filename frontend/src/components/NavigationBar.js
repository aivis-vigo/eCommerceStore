import React from 'react';

export default function Navbar() {
    return (
        <section className="relative mx-auto w-full">
            {/* Navbar */}
            <nav className="flex justify-between bg-white text-black w-full">
                <div className="px-5 xl:px-12 py-6 flex w-full items-center justify-between">
                    {/* Left section for links and icons */}
                    <div className="flex items-center space-x-5">
                        {/* Nav Links */}
                        <ul className="hidden md:flex px-4 font-semibold font-heading space-x-12">
                            <li><a className="hover:text-gray-200" href="#">Tech</a></li>
                            <li><a className="hover:text-gray-200" href="#">Fashion</a></li>
                        </ul>
                    </div>
                    {/* Center section for logo */}
                    <div className="flex justify-center flex-1">
                        <a href="#" className="text-3xl font-bold font-heading">
                            {/* Replace with your logo or text */}
                            Logo Here
                        </a>
                    </div>
                    {/* Right section for icons (hidden on small screens) */}
                    <div className="hidden xl:flex items-center space-x-5">
                        <a className="flex items-center hover:text-gray-200" href="#">
                            {/* SVG content */}
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {/* Notification dot */}
                            <span className="flex absolute -mt-5 ml-4">
                                <span className="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-pink-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-3 w-3 bg-pink-500"></span>
                            </span>
                        </a>
                    </div>
                    {/* Responsive Navbar */}
                    <div className="flex items-center space-x-5 xl:hidden">
                        <a href="#" className="hover:text-gray-200">
                            {/* SVG content */}
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            {/* Notification dot */}
                            <span className="flex absolute -mt-5 ml-4">
                                <span className="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-pink-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-3 w-3 bg-pink-500"></span>
                            </span>
                        </a>
                        {/* Navbar Burger */}
                        <a className="navbar-burger self-center" href="#">
                            {/* SVG content */}
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 hover:text-gray-200" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </nav>
        </section>
    );
}
