import React, { useState, useEffect } from 'react';
import { Head, useForm, Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import GuestLayout from '../Layouts/GuestLayout';

export default function Register({ authflowData }) {
    const [showPassword, setShowPassword] = useState(false);
    const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);

    // Redirect if no authflow data
    useEffect(() => {
        if (!authflowData || !authflowData.authwith) {
            router.visit(route('v2.authflow.get-otp') + '?for=register');
        }
    }, [authflowData]);

    // Helper to get country ID
    const getCountryId = () => {
        if (authflowData?.country_id) return authflowData.country_id;
        if (authflowData?.country) {
            if (typeof authflowData.country === 'object' && 'id' in authflowData.country) {
                return authflowData.country.id;
            }
        }
        return '';
    };

    const { data, setData, post, processing, errors } = useForm({
        name: '',
        password: '',
        password_confirmation: '',
        country_id: getCountryId(),
    });

    // Update country_id when authflowData changes
    useEffect(() => {
        const countryId = getCountryId();
        if (countryId) {
            setData('country_id', countryId);
        }
    }, [authflowData]);

    const submit = (e) => {
        e.preventDefault();
        post(route('v2.register.store'), {
            onSuccess: () => {
                // User will be redirected by backend
            },
        });
    };

    const togglePasswordVisibility = () => {
        setShowPassword(!showPassword);
    };

    const togglePasswordConfirmationVisibility = () => {
        setShowPasswordConfirmation(!showPasswordConfirmation);
    };

    if (!authflowData || !authflowData.authwith) {
        return null; // Will redirect
    }

    // Helper to get dial code from country (handles object, array, or null)
    const getDialCode = () => {
        if (!authflowData.country) return '';
        if (typeof authflowData.country === 'object' && 'dial_code' in authflowData.country) {
            return authflowData.country.dial_code;
        }
        return '+251';
    };
    
    const displayContact = authflowData.authwith === 'email' 
        ? authflowData.email 
        : `${getDialCode()} ${authflowData.phone}`;

    return (
        <GuestLayout title="Create Account">
            <div className="text-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">Create Account</h1>
                <p className="text-gray-600 dark:text-gray-400">Join us and start your journey</p>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <input type="hidden" name="country_id" value={data.country_id} />

                {/* Contact Information Display */}
                <div className="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div className="flex items-center space-x-3">
                        <div className="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <i className={`fas fa-${authflowData.authwith === 'email' ? 'envelope' : 'phone'} text-primary-600 dark:text-primary-400`}></i>
                        </div>
                        <div>
                            <p className="text-sm text-gray-500 dark:text-gray-400">Registering with:</p>
                            <p className="font-medium text-gray-900 dark:text-white">
                                {displayContact}
                            </p>
                        </div>
                    </div>
                </div>

                {/* Full Name */}
                <div className="space-y-2">
                    <label htmlFor="name" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Full Name
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        placeholder="Enter your full name"
                        required
                        autoFocus
                        autoComplete="name"
                    />
                    {errors.name && (
                        <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.name}</p>
                    )}
                </div>

                {/* Password */}
                <div className="space-y-2">
                    <label htmlFor="password" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <div className="relative">
                        <input
                            id="password"
                            name="password"
                            type={showPassword ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm pr-12"
                            placeholder="Create a strong password"
                            required
                            autoComplete="new-password"
                        />
                        <button
                            type="button"
                            onClick={togglePasswordVisibility}
                            className="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                        >
                            {showPassword ? (
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            ) : (
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                                </svg>
                            )}
                        </button>
                    </div>
                    {errors.password && (
                        <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.password}</p>
                    )}
                </div>

                {/* Confirm Password */}
                <div className="space-y-2">
                    <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirm Password
                    </label>
                    <div className="relative">
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type={showPasswordConfirmation ? 'text' : 'password'}
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm pr-12"
                            placeholder="Confirm your password"
                            required
                            autoComplete="new-password"
                        />
                        <button
                            type="button"
                            onClick={togglePasswordConfirmationVisibility}
                            className="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                        >
                            {showPasswordConfirmation ? (
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            ) : (
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z" />
                                </svg>
                            )}
                        </button>
                    </div>
                    {errors.password_confirmation && (
                        <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.password_confirmation}</p>
                    )}
                </div>

                {/* Terms Agreement */}

                {/* General Error Message */}
                {errors.general && (
                    <div className="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <p className="text-sm font-medium">{errors.general}</p>
                    </div>
                )}
                <div className="pt-4">
                    <p className="text-xs text-gray-500 dark:text-gray-400 text-left">
                        By creating an account, you agree to our{' '}
                        <Link href="/privacy-policy" className="text-primary-600 hover:text-primary-500 dark:text-primary-400">
                            Terms of Service
                        </Link>{' '}
                        and{' '}
                        <Link href="/privacy-policy" className="text-primary-600 hover:text-primary-500 dark:text-primary-400">
                            Privacy Policy
                        </Link>.
                    </p>
                </div>

                {/* Submit Button */}
                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className={`w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none ${
                            processing 
                                ? 'bg-primary-400 cursor-not-allowed' 
                                : 'bg-primary-600 hover:bg-primary-700'
                        } text-white`}
                    >
                        {processing ? (
                            <span className="flex items-center justify-center">
                                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating Account...
                            </span>
                        ) : (
                            <span className="flex items-center justify-center">
                                <i className="fas fa-user-plus mr-2"></i>
                                Create Account
                            </span>
                        )}
                    </button>
                </div>

                {/* Login Link */}
                <div className="text-center pt-6">
                    <span className="text-sm text-gray-600 dark:text-gray-400">
                        Already have an account?{' '}
                    </span>
                    <Link
                        href={route('v2.login')}
                        className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                    >
                        Sign in
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}

