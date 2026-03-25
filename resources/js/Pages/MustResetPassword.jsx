import React, { useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import GuestLayout from '../Layouts/GuestLayout';

export default function MustResetPassword({ status, errors: propErrors }) {
    const [showCurrentPassword, setShowCurrentPassword] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('password.must-reset.store'));
    };

    return (
        <GuestLayout title="Password Reset Required">
            <div className="text-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">Password Reset Required</h1>
                <p className="text-gray-600 dark:text-gray-400">You must reset your password before continuing</p>
            </div>

            {(status === 'password-reset-success' || propErrors?.general) && (
                <div
                    className={`mb-6 border px-4 py-3 rounded-lg ${
                        status === 'password-reset-success'
                            ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-300'
                            : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300'
                    }`}
                >
                    <p className="text-sm font-medium">
                        {status === 'password-reset-success'
                            ? 'Password updated successfully.'
                            : propErrors?.general}
                    </p>
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <div className="space-y-2">
                    <label htmlFor="current_password" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Current Password
                    </label>
                    <div className="relative">
                        <input
                            id="current_password"
                            name="current_password"
                            type={showCurrentPassword ? 'text' : 'password'}
                            value={data.current_password}
                            onChange={(e) => setData('current_password', e.target.value)}
                            className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm pr-12"
                            placeholder="Enter your current password"
                            required
                            autoFocus
                            autoComplete="current-password"
                        />
                        <button
                            type="button"
                            onClick={() => setShowCurrentPassword((s) => !s)}
                            className="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                        >
                            {showCurrentPassword ? (
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
                    {errors.current_password && (
                        <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.current_password}</p>
                    )}
                </div>

                <div className="space-y-2">
                    <label htmlFor="password" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        New Password
                    </label>
                    <div className="relative">
                        <input
                            id="password"
                            name="password"
                            type={showPassword ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm pr-12"
                            placeholder="Enter your new password"
                            required
                            autoComplete="new-password"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword((s) => !s)}
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
                            placeholder="Confirm your new password"
                            required
                            autoComplete="new-password"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPasswordConfirmation((s) => !s)}
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

                <div className="pt-4">
                    <button
                        type="submit"
                        disabled={processing}
                        className={`w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none ${
                            processing ? 'bg-primary-400 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700'
                        } text-white`}
                    >
                        {processing ? (
                            <span className="flex items-center justify-center">
                                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                        ) : (
                            <span className="flex items-center justify-center">
                                <i className="fas fa-key mr-2"></i>
                                Reset Password
                            </span>
                        )}
                    </button>
                </div>

                <div className="text-center pt-6">
                    <Link
                        href={route('logout')}
                        method="post"
                        as="button"
                        className="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 font-medium"
                    >
                        Logout
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}

