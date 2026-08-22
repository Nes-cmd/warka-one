import React, { useState, useEffect } from 'react';
import { Head, useForm, Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import GuestLayout from '../Layouts/GuestLayout';
import CountryPhoneSelect from '../Components/CountryPhoneSelect';

export default function GetOtp({ otpIsFor, options: initialOptions, countries, selectedCountry: initialSelectedCountry }) {
    const [authWith, setAuthWith] = useState(() => {
        const stored = localStorage.getItem('authwith');
        return stored || 'email';
    });

    const [selectedCountry, setSelectedCountry] = useState(initialSelectedCountry || (countries && countries.length > 0 ? countries[0] : null));
    const options = initialOptions || ['email', 'phone'];
    const numberofoptions = options.length;

    // Persist authWith to localStorage
    useEffect(() => {
        localStorage.setItem('authwith', authWith);
    }, [authWith]);

    const { data, setData, post, processing, errors } = useForm({
        for: otpIsFor,
        authwith: authWith,
        phone: '',
        email: '',
        country_id: selectedCountry?.id || '',
    });

    // Update form data when authWith or country changes
    useEffect(() => {
        setData('authwith', authWith);
        setData('country_id', selectedCountry?.id || '');
    }, [authWith, selectedCountry]);

    const submit = (e) => {
        e.preventDefault();
        post(route('v2.authflow.get-otp.store'), {
            onSuccess: () => {
                // Redirect to verify page on success
                router.visit(route('v2.authflow.verify'));
            },
        });
    };

    const toggleAuthWith = (newAuthWith) => {
        setAuthWith(newAuthWith);
        setData('email', '');
        setData('phone', '');
    };

    return (
        <GuestLayout title="Get Verification Code">
            <div className="text-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">Get Verification Code</h1>
                <p className="text-gray-600 dark:text-gray-400">Enter your details to receive a verification code</p>
            </div>

            {otpIsFor === 'reset-password' ? (
                <div className="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
                    Don't worry if you forgot your password. Simply provide us with your phone/email, and we'll send a verification code. Once verified, you can easily reset your password.
                </div>
            ) : (
                <div className="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
                    Please enter your phone/email. You will receive a text message to verify your account.
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                <input type="hidden" name="for" value={otpIsFor} />
                <input type="hidden" name="authwith" value={authWith} />
                <input type="hidden" name="country_id" value={selectedCountry?.id || ''} />

                {/* Email Address */}
                {(authWith === 'email' || (numberofoptions === 1 && options[0] === 'email')) && (
                    <div className="space-y-2">
                        <div className="flex items-center justify-between">
                            <label htmlFor="email" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Address
                            </label>
                            {numberofoptions > 1 && options.includes('phone') && (
                                <button
                                    type="button"
                                    onClick={() => toggleAuthWith('phone')}
                                    className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                                >
                                    Use phone instead
                                </button>
                            )}
                        </div>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                            placeholder="Enter your email address"
                            required
                        />
                        {errors.email && (
                            <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.email}</p>
                        )}
                    </div>
                )}

                {/* Phone Number */}
                {(authWith === 'phone' || (numberofoptions === 1 && options[0] === 'phone')) && (
                    <div className="space-y-2">
                        <div className="flex items-center justify-between">
                            <label htmlFor="phone" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Phone Number
                            </label>
                            {numberofoptions > 1 && options.includes('email') && (
                                <button
                                    type="button"
                                    onClick={() => toggleAuthWith('email')}
                                    className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                                >
                                    Use email instead
                                </button>
                            )}
                        </div>
                        <div className="flex">
                            <CountryPhoneSelect
                                countries={countries}
                                selectedCountry={selectedCountry}
                                onSelect={setSelectedCountry}
                            />

                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value={data.phone}
                                onChange={(e) => setData('phone', e.target.value)}
                                className="block w-full rounded-r-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                placeholder="Enter your phone number"
                                required
                            />
                        </div>
                        {errors.phone && (
                            <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.phone}</p>
                        )}
                    </div>
                )}

                {/* Error Messages */}
                {errors.general && (
                    <div className="bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <p className="text-sm">{errors.general}</p>
                    </div>
                )}

                {/* Submit Button */}
                <div className="pt-4 flex items-center justify-center">
                    <button
                        type="submit"
                        disabled={processing}
                        className={`w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:cursor-not-allowed disabled:transform-none ${
                            processing ? 'bg-primary-500' : 'bg-primary-600 hover:bg-primary-700'
                        } text-white`}
                    >
                        {processing ? (
                            <span className="flex items-center justify-center">
                                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        ) : (
                            <span className="flex items-center">
                                <i className="fas fa-paper-plane mr-2"></i>
                                Get Verification Code
                            </span>
                        )}
                    </button>
                </div>

                {/* Back to Login */}
                <div className="text-center pt-6">
                    <Link
                        href={route('v2.login')}
                        className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                    >
                        Back to login
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}

