import React, { useState, useEffect, useRef } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import ApplicationLogo from './ApplicationLogo';

export default function AuthenticatedNavbar() {
    const page = usePage();
    const url = page.url || window.location.pathname;
    const auth = page.props.auth;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [isDark, setIsDark] = useState(false);
    const [userDropdownOpen, setUserDropdownOpen] = useState(false);
    const user = auth?.user;
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

    useEffect(() => {
        // Check initial theme
        const theme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const shouldBeDark = theme === 'dark' || (!theme && prefersDark);
        
        if (shouldBeDark) {
            document.documentElement.classList.add('dark');
            setIsDark(true);
        } else {
            document.documentElement.classList.remove('dark');
            setIsDark(false);
        }

        // Set user initial in avatar
        const initialElement = document.getElementById('initial');
        if (initialElement && user?.name) {
            const firstLetter = user.name.charAt(0).toUpperCase();
            initialElement.textContent = firstLetter;
        }
    }, [user]);

    const toggleTheme = () => {
        const newIsDark = !isDark;
        setIsDark(newIsDark);
        
        if (newIsDark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    };

    const toggleMobileMenu = () => {
        setMobileMenuOpen(!mobileMenuOpen);
        document.body.style.overflow = mobileMenuOpen ? '' : 'hidden';
    };

    const handleLogout = (e) => {
        e.preventDefault();
        router.post(route('logout'), {}, {
            onSuccess: () => {
                // Redirect to login page after successful logout
                router.visit(route('v2.login'));
            },
            onError: () => {
                // Even if there's an error, redirect to login
                router.visit(route('v2.login'));
            }
        });
    };

    const isActive = (routePath) => {
        if (!url || !routePath) return false;
        
        // Extract pathname from route URL (remove domain and query params)
        const getPath = (fullUrl) => {
            if (!fullUrl) return '';
            
            // If it's already a path (starts with /), return it without query params
            if (fullUrl.startsWith('/')) {
                return fullUrl.split('?')[0].replace(/\/$/, ''); // Remove trailing slash
            }
            
            // Try to parse as URL
            try {
                const urlObj = new URL(fullUrl);
                return urlObj.pathname.replace(/\/$/, ''); // Remove trailing slash
            } catch {
                // If parsing fails, assume it's a path and add leading slash if needed
                const path = fullUrl.split('?')[0].replace(/\/$/, ''); // Remove trailing slash
                return path.startsWith('/') ? path : '/' + path;
            }
        };
        
        const currentPath = getPath(url);
        const targetPath = getPath(routePath);
        
        // Exact match
        if (currentPath === targetPath) {
            return true;
        }
        
        // For nested routes (e.g., /v2/clients/123/edit should match /v2/clients)
        // Only match if the target path is a prefix and followed by a slash
        if (targetPath && currentPath.startsWith(targetPath + '/')) {
            return true;
        }
        
        return false;
    };

    return (
        <nav className="bg-white dark:bg-[#0F172A] border-b border-primary-200 dark:border-slate-700">
            <div className="max-w-[1480px] mx-auto px-4 lg:px-24 md:px-12">
                <div className="flex justify-between h-16">
                    <div className="flex items-center">
                        {/* Logo */}
                        <div className="shrink-0 flex items-center">
                            <Link href={route('v2.welcome')}>
                                <ApplicationLogo className="block w-auto fill-current text-gray-800" />
                            </Link>
                        </div>
                        {/* Navigation Links */}
                        <div className="flex space-x-4 pl-4 mt-4">
                            <Link 
                                href={route('v2.account')} 
                                className={`dark:text-white transition-colors ${isActive(route('v2.account')) ? 'border-b-primary dark:border-b-secondary border-b-2 font-medium text-primary dark:text-secondary' : 'hover:text-primary dark:hover:text-secondary'}`}
                            >
                                My Account
                            </Link>
                            <Link 
                                href={route('v2.profile.setting')} 
                                className={`dark:text-white transition-colors ${isActive(route('v2.profile.setting')) ? 'border-b-primary dark:border-b-secondary border-b-2 font-medium text-primary dark:text-secondary' : 'hover:text-primary dark:hover:text-secondary'}`}
                            >
                                Edit Profile
                            </Link>
                            <Link 
                                href={route('v2.clients.index')} 
                                className={`dark:text-white transition-colors ${isActive(route('v2.clients.index')) ? 'border-b-primary dark:border-b-secondary border-b-2 font-medium text-primary dark:text-secondary' : 'hover:text-primary dark:hover:text-secondary'}`}
                            >
                                My Apps
                            </Link>
                        </div>
                    </div>

                    {/* Settings Dropdown */}
                    <div className="flex items-center">
                        {/* Theme Toggle */}
                        <button 
                            id="theme-toggle"
                            type="button" 
                            onClick={toggleTheme}
                            className="text-gray-500 dark:text-gray-400 focus:outline-none text-sm p-2 lg:mr-3"
                        >
                            <svg 
                                id="theme-toggle-dark-icon" 
                                className={`w-6 h-6 ${isDark ? 'hidden' : ''}`} 
                                fill="currentColor" 
                                viewBox="0 0 20 20" 
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg 
                                id="theme-toggle-light-icon" 
                                className={`w-6 h-6 ${!isDark ? 'hidden' : ''}`} 
                                fill="currentColor" 
                                viewBox="0 0 20 20" 
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fillRule="evenodd" clipRule="evenodd"></path>
                            </svg>
                        </button>

                        {/* User Dropdown */}
                        <div className="relative" ref={dropdownRef}>
                            <button 
                                onClick={() => setUserDropdownOpen(!userDropdownOpen)}
                                className="inline-flex items-center text-sm leading-4 font-medium rounded-full hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                            >
                                <div 
                                    id="initial" 
                                    className="3xl:w-[41px] w-[32px] aspect-square text-xl text-primary flex items-center justify-center font-bold rounded-full bg-primary-100"
                                >
                                    {user?.name ? user.name.charAt(0).toUpperCase() : 'U'}
                                </div>
                            </button>
                            
                            {/* Dropdown Menu */}
                            {userDropdownOpen && (
                                <div className="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <Link 
                                        href={route('v2.account')} 
                                        onClick={() => setUserDropdownOpen(false)}
                                        className={`block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 ${isActive(route('v2.account')) ? 'bg-gray-100 dark:bg-gray-700 font-medium' : ''}`}
                                    >
                                        Profile
                                    </Link>
                                    <form onSubmit={handleLogout}>
                                        <button 
                                            type="submit" 
                                            className="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                        >
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    );
}
    
