import React, { useState, useRef, useEffect } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export default function GuestLayout({ children, title }) {
    const page = usePage();
    const auth = page.props.auth;
    const user = auth?.user;
    const [userDropdownOpen, setUserDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

    useEffect(() => {
        // Close dropdown when clicking outside
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setUserDropdownOpen(false);
            }
        };

        if (userDropdownOpen) {
            document.addEventListener('mousedown', handleClickOutside);
        }

        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [userDropdownOpen]);

    const handleLogout = (e) => {
        e.preventDefault();
        router.post(route('logout'), {}, {
            onSuccess: () => {
                router.visit(route('v2.login'));
            },
            onError: () => {
                router.visit(route('v2.login'));
            }
        });
    };

    return (
        <>
            <Head title={title || 'Login'} />
            <div className="font-sans text-gray-900 dark:bg-[#0F172A] bg-[#EAE9F0] antialiased h-screen overflow-hidden justify-center items-center flex relative">
                {/* Navigation Bar - Only show if authenticated */}
                {user && (
                    <div className="absolute top-0 left-0 right-0 z-50 bg-transparent">
                        <nav className="container mx-auto px-4 py-4 flex justify-end">
                            <div className="relative" ref={dropdownRef}>
                                <button 
                                    onClick={() => setUserDropdownOpen(!userDropdownOpen)}
                                    className="flex items-center gap-2 border border-gray-600 dark:border-gray-400 p-1 px-3 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition bg-white dark:bg-gray-800"
                                >
                                    <div className="w-8 h-8 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        {user?.profile_photo_path ? (
                                            <img 
                                                src={`/storage/${user.profile_photo_path}`}
                                                alt={user?.name || 'User'} 
                                                className="h-full w-full object-cover"
                                            />
                                        ) : (
                                            <span className="text-lg font-bold text-primary dark:text-secondary">
                                                {(user?.name || user?.email || 'U').charAt(0).toUpperCase()}
                                            </span>
                                        )}
                                    </div>
                                    <span className="text-sm font-medium dark:text-gray-200">
                                        {user?.name ? (user.name.length > 12 ? user.name.substring(0, 12) + '...' : user.name) : 'User'}
                                    </span>
                                    <svg 
                                        width="16" 
                                        height="16" 
                                        viewBox="0 0 24 24" 
                                        className="stroke-gray-500 dark:stroke-gray-300" 
                                        fill="none" 
                                        xmlns="http://www.w3.org/2000/svg"
                                        style={{ transform: userDropdownOpen ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }}
                                    >
                                        <path d="M19.9201 8.94995L13.4001 15.47C12.6301 16.24 11.3701 16.24 10.6001 15.47L4.08008 8.94995" strokeWidth="1.5" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
                                    </svg>
                                </button>
                                
                                {/* Dropdown Menu */}
                                {userDropdownOpen && (
                                    <div className="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <Link 
                                            href={route('v2.account')} 
                                            onClick={() => setUserDropdownOpen(false)}
                                            className="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                            My Account
                                        </Link>
                                        <Link 
                                            href={route('v2.clients.index')} 
                                            onClick={() => setUserDropdownOpen(false)}
                                            className="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                            My Applications
                                        </Link>
                                        <hr className="my-1 border-gray-200 dark:border-gray-700" />
                                        <form onSubmit={handleLogout}>
                                            <button 
                                                type="submit" 
                                                className="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                            >
                                                Sign out
                                            </button>
                                        </form>
                                    </div>
                                )}
                            </div>
                        </nav>
                    </div>
                )}

                {/* Background Elements */}
                <div className="moon"></div>
                <div className="shooting-star"></div>
                <div className="shooting-star"></div>
                <div className="shooting-star"></div>
                <div className="shooting-star"></div>
                <div className="shooting-star"></div>
                
                {/* Floating particles for dark mode */}
                <div className="floating-particle"></div>
                <div className="floating-particle"></div>
                <div className="floating-particle"></div>
                <div className="floating-particle"></div>
                <div className="floating-particle"></div>

                <div className="flex lg:flex-row flex-col items-center justify-center px-2 w-[100%] md:w-[90%] h-[85%]">
                    <div className="lg:w-1/2 w-full flex flex-col gap-6 bg-primary-50 dark:bg-[#1f2533] rounded-lg md:rounded-none items-center justify-center h-full">
                        <div className="md:w-[75%] w-[90%] flex flex-col p gap-4">
                            <div className="pb-4 relative">
                                {children}
                                <div className="absolute md:hidden -top-20 left-1/3 w-52 h-[100px] rotate-30 bg-gradient-to-t from-primary-300/40 to-primary-100/40 blur-3xl"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

