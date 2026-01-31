import React, { useState, useEffect, useRef } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import ApplicationLogo from './ApplicationLogo';

export default function Navbar() {
    const page = usePage();
    const url = page.url || window.location.pathname;
    const auth = page.props.auth;
    const user = auth?.user;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [isDark, setIsDark] = useState(false);
    const [userDropdownOpen, setUserDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

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
    }, []);

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
        
        // Special case: Don't match home route (/v2) as a prefix of other routes
        // Only allow prefix matching for routes that are not the home route
        const isHomeRoute = targetPath === '/v2' || targetPath === '';
        if (isHomeRoute) {
            return false; // Home route should only match exactly
        }
        
        // For nested routes (e.g., /v2/clients/123/edit should match /v2/clients)
        // Only match if the target path is a prefix and followed by a slash
        if (targetPath && currentPath.startsWith(targetPath + '/')) {
            return true;
        }
        
        return false;
    };

    return (
        <>
            <nav className="flex justify-between items-center py-4 bg-transparent">
                <Link href={route('v2.welcome')} className="h-10">
                    <ApplicationLogo />
                </Link>
                
                {/* Desktop Navigation */}
                <ul className="sm:flex items-center gap-3 hidden">
                    <NavLink href={route('v2.welcome')} active={isActive(route('v2.welcome'))}>
                        Home
                    </NavLink>
                    <NavLink href={route('v2.services')} active={isActive(route('v2.services'))}>
                        About
                    </NavLink>
                    <NavLink href={route('v2.documentation')} active={isActive(route('v2.documentation'))}>
                        Documentation
                    </NavLink>
                    <NavLink href={route('v2.contact')} active={isActive(route('v2.contact'))}>
                        Contact us
                    </NavLink>
                </ul>
                
                <div className="flex items-center gap-3">
                    {/* Mobile Menu Button */}
                    <button onClick={toggleMobileMenu} className="sm:hidden">
                        <svg width="24" height="25" className="dark:stroke-gray-50 stroke-gray-800" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 10.5H7C9 10.5 10 9.5 10 7.5V5.5C10 3.5 9 2.5 7 2.5H5C3 2.5 2 3.5 2 5.5V7.5C2 9.5 3 10.5 5 10.5Z" strokeWidth="1.5" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
                            <path d="M17 10.5H19C21 10.5 22 9.5 22 7.5V5.5C22 3.5 21 2.5 19 2.5H17C15 2.5 14 3.5 14 5.5V7.5C14 9.5 15 10.5 17 10.5Z" strokeWidth="1.5" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
                            <path d="M17 22.5H19C21 22.5 22 21.5 22 19.5V17.5C22 15.5 21 14.5 19 14.5H17C15 14.5 14 15.5 14 17.5V19.5C14 21.5 15 22.5 17 22.5Z" strokeWidth="1.5" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
                            <path d="M5 22.5H7C9 22.5 10 21.5 10 19.5V17.5C10 15.5 9 14.5 7 14.5H5C3 14.5 2 15.5 2 17.5V19.5C2 21.5 3 22.5 5 22.5Z" strokeWidth="1.5" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
                        </svg>
                    </button>
                </div>

                {/* Desktop Auth Section with Dark Mode Toggle */}
                <div className="sm:flex items-center gap-4 hidden">
                    {/* Dark Mode Toggle */}
                    <button onClick={toggleTheme} type="button" className="text-gray-500 dark:text-gray-400 focus:outline-none text-sm p-2">
                        {isDark ? (
                            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fillRule="evenodd" clipRule="evenodd"></path>
                            </svg>
                        ) : (
                            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        )}
                    </button>

                    {/* Auth buttons or User dropdown */}
                    {user ? (
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
                    ) : (
                        <div className="flex items-center gap-3">
                            <Link href={route('v2.login')} className="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                Login
                            </Link>
                            <Link href={route('v2.authflow.get-otp') + '?for=register'} className="border border-primary bg-primary text-white px-4 py-1.5 rounded-lg hover:bg-primary-600 transition-colors">
                                Register
                            </Link>
                        </div>
                    )}
                </div>
            </nav>

            {/* Divider line */}
            <div className="flex items-center container m-auto">
                <div className="flex-grow bg-gray-500 h-px"></div>
                <div className="flex-grow bg-gray-600 h-px"></div>
            </div>

            {/* Mobile Navigation Menu */}
            <section 
                className={`${mobileMenuOpen ? '' : 'hidden'} shadow-sm w-full sm:w-2/3 rounded-lg dark:bg-slate-800 bg-white py-2 pb-6 px-4 absolute top-0 right-0 left-0 z-50 flex flex-col gap-7 ease-in-out duration-300 transition-all`}
            >
                <div className="flex justify-between items-center">
                    <Link href={route('v2.welcome')} className="w-36">
                        <ApplicationLogo />
                    </Link>
                    <button onClick={toggleMobileMenu} className="cursor-pointer">
                        <svg width="24" height="24" className="dark:fill-gray-300 fill-gray-800" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fillRule="evenodd" clipRule="evenodd" d="M5.46967 5.46967C5.76256 5.17678 6.23744 5.17678 6.53033 5.46967L12 10.9393L17.4697 5.46967C17.7626 5.17678 18.2374 5.17678 18.5303 5.46967C18.8232 5.76256 18.8232 6.23744 18.5303 6.53033L13.0607 12L18.5303 17.4697C18.8232 17.7626 18.8232 18.2374 18.5303 18.5303C18.2374 18.8232 17.7626 18.8232 17.4697 18.5303L12 13.0607L6.53033 18.5303C6.23744 18.8232 5.76256 18.8232 5.46967 18.5303C5.17678 18.2374 5.17678 17.7626 5.46967 17.4697L10.9393 12L5.46967 6.53033C5.17678 6.23744 5.17678 5.76256 5.46967 5.46967Z" />
                        </svg>
                    </button>
                </div>

                <div className="flex flex-col gap-4">
                    <NavLink href={route('v2.welcome')} active={isActive(route('v2.welcome'))} className="w-full">
                        Home
                    </NavLink>
                    <NavLink href={route('v2.services')} active={isActive(route('v2.services'))} className="w-full">
                        About
                    </NavLink>
                    <NavLink href={route('v2.documentation')} active={isActive(route('v2.documentation'))} className="w-full">
                        Documentation
                    </NavLink>
                    <NavLink href={route('v2.contact')} active={isActive(route('v2.contact'))} className="w-full">
                        Contact us
                    </NavLink>
                </div>

                {/* Mobile Auth Links */}
                <div className="mt-4">
                    <div className="flex justify-end mb-4">
                        <button onClick={toggleTheme} type="button" className="text-gray-500 dark:text-gray-400 focus:outline-none text-sm p-2">
                            {isDark ? (
                                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fillRule="evenodd" clipRule="evenodd"></path>
                                </svg>
                            ) : (
                                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            )}
                        </button>
                    </div>

                    {user ? (
                        <div className="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-4">
                            <div className="flex items-center gap-3 mb-3">
                                <div className="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
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
                                <div>
                                    <div className="font-medium dark:text-white">{user?.name || 'User'}</div>
                                    <div className="text-sm text-gray-500 dark:text-gray-400">{user?.email || ''}</div>
                                </div>
                            </div>
                            
                            <div className="space-y-2">
                                <Link href={route('v2.account')} className="block w-full py-2 px-3 text-left rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 transition">
                                    My Account
                                </Link>
                                <Link href={route('v2.clients.index')} className="block w-full py-2 px-3 text-left rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 transition">
                                    My Applications
                                </Link>
                                <form onSubmit={handleLogout}>
                                    <button type="submit" className="block w-full py-2 px-3 text-left rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200 text-red-600 dark:text-red-400 transition">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    ) : (
                        <div className="flex flex-col gap-3">
                            <Link href={route('v2.login')} className="block w-full py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-center dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Login
                            </Link>
                            <Link href={route('v2.authflow.get-otp') + '?for=register'} className="block w-full py-3 rounded-lg bg-primary border border-primary text-white text-center hover:bg-primary-600 transition">
                                Register
                            </Link>
                        </div>
                    )}
                </div>
            </section>
        </>
    );
}

function NavLink({ href, active, children, className = '' }) {
    return (
        <Link
            href={href}
            className={`${active ? 'border-b-primary dark:border-b-secondary border-b-2' : ''} dark:text-white ${className}`}
        >
            {children}
        </Link>
    );
}

