import React, { useState, useEffect, useRef } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import GuestLayout from '../Layouts/GuestLayout';

export default function Login({ 
    authwith: initialAuthWith, 
    authMethod: initialAuthMethod, 
    countries, 
    selectedCountry: initialSelectedCountry,
    options,
    registrationEnabled,
    success,
    error: errorMessage
}) {
    const [authWith, setAuthWith] = useState(() => {
        // Get from localStorage or use initial value
        const stored = localStorage.getItem('authwith');
        return stored || initialAuthWith || 'email';
    });
    
    const [authMethod, setAuthMethod] = useState(initialAuthMethod || 'password');
    const [selectedCountry, setSelectedCountry] = useState(initialSelectedCountry);
    const [countryDropdownOpen, setCountryDropdownOpen] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    const [otpMessage, setOtpMessage] = useState('');
    const [otpMessageType, setOtpMessageType] = useState('');
    
    const countryDropdownRef = useRef(null);
    const numberofoptions = options?.length || 2;

    // Get countdown from localStorage or use default
    const getStoredCountdown = () => {
        const stored = localStorage.getItem('login_otp_countdown');
        if (stored) {
            try {
                const parsed = JSON.parse(stored);
                const elapsed = Math.floor((Date.now() - parsed.timestamp) / 1000);
                const remaining = Math.max(0, parsed.countdown - elapsed);
                return remaining > 0 ? remaining : 60;
            } catch (e) {
                return 60;
            }
        }
        return 60;
    };
    
    const initialCountdown = getStoredCountdown();
    const [countdown, setCountdown] = useState(initialCountdown);
    const [otpRequested, setOtpRequested] = useState(initialCountdown < 60);
    const [authTypeMessage, setAuthTypeMessage] = useState('');
    const [authTypeMessageType, setAuthTypeMessageType] = useState('');

    // Persist authWith to localStorage
    useEffect(() => {
        localStorage.setItem('authwith', authWith);
    }, [authWith]);

    // Handle countdown timer and persist to localStorage
    useEffect(() => {
        if (otpRequested && countdown > 0) {
            // Store countdown in localStorage
            localStorage.setItem('login_otp_countdown', JSON.stringify({
                countdown: countdown,
                timestamp: Date.now()
            }));
            
            const timer = setTimeout(() => {
                setCountdown(countdown - 1);
            }, 1000);
            return () => clearTimeout(timer);
        } else if (countdown === 0) {
            // Countdown finished, reset and clear localStorage
            setOtpRequested(false);
            setCountdown(60);
            localStorage.removeItem('login_otp_countdown');
        } else if (!otpRequested && countdown === 60) {
            // Clear stored countdown when not requested
            localStorage.removeItem('login_otp_countdown');
        }
    }, [otpRequested, countdown]);

    // Close country dropdown when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (countryDropdownRef.current && !countryDropdownRef.current.contains(event.target)) {
                setCountryDropdownOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const { data, setData, post, processing, errors, reset } = useForm({
        authwith: authWith,
        auth_method: authMethod,
        email: '',
        phone: '',
        country_id: selectedCountry?.id || '',
        password: '',
        otp: '',
    });

    // Update form data when authWith changes
    useEffect(() => {
        setData('authwith', authWith);
        setData('auth_method', authMethod);
        setData('country_id', selectedCountry?.id || '');
    }, [authWith, authMethod, selectedCountry]);

    const submit = (e) => {
        e.preventDefault();
        post(route('v2.login.store'), {
            onSuccess: () => {
                // Reset form on success
                reset();
            },
        });
    };

    const requestLoginOTP = async () => {
        const email = authWith === 'email' ? data.email : '';
        const phone = authWith === 'phone' ? data.phone : '';

        if ((authWith === 'email' && !email) || (authWith === 'phone' && !phone)) {
            setAuthTypeMessage(`Please enter your ${authWith} first`);
            setAuthTypeMessageType('error');
            setOtpMessage('');
            return;
        }

        try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch('/request-login-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    auth_with: authWith,
                    email: email,
                    phone: phone,
                }),
            });

            const result = await response.json();

            if (result.success) {
                setOtpRequested(true);
                setCountdown(60);
                // Store countdown in localStorage
                localStorage.setItem('login_otp_countdown', JSON.stringify({
                    countdown: 60,
                    timestamp: Date.now()
                }));
                setOtpMessage('Verification code sent successfully');
                setOtpMessageType('success');
                setAuthTypeMessage('');
            } else {
                const isAuthTypeError = result.message && (
                    result.message.toLowerCase().includes('email') ||
                    result.message.toLowerCase().includes('phone')
                );

                if (isAuthTypeError) {
                    setAuthTypeMessage(result.message || 'Invalid email or phone number');
                    setAuthTypeMessageType('error');
                    setOtpMessage('');
                } else {
                    setOtpMessage(result.message || 'Failed to send verification code. Please try again.');
                    setOtpMessageType('error');
                    setAuthTypeMessage('');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            setOtpMessage('An error occurred. Please try again later.');
            setOtpMessageType('error');
            setAuthTypeMessage('');
        }
    };

    const toggleAuthWith = (newAuthWith) => {
        setAuthWith(newAuthWith);
        setOtpRequested(false);
        setCountdown(60);
        setAuthTypeMessage('');
        setOtpMessage('');
        // Clear countdown when switching auth type
        localStorage.removeItem('login_otp_countdown');
        reset('email', 'phone', 'password', 'otp');
    };

    const togglePasswordVisibility = () => {
        setShowPassword(!showPassword);
    };

    return (
        <GuestLayout title="Login">
            {/* Session Status */}
            <div className="text-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome back!</h1>
                <p className="text-gray-600 dark:text-gray-400">Sign in to your account</p>
            </div>

            {/* Success Message */}
            {success && (
                <div className="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                    <p className="text-sm font-medium">{success}</p>
                </div>
            )}

            {/* Error Message */}
            {errorMessage && (
                <div className="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                    <p className="text-sm font-medium">{errorMessage}</p>
                </div>
            )}

            <form onSubmit={submit} className="">
                <input type="hidden" name="authwith" value={authWith} />
                <input type="hidden" name="auth_method" value={authMethod} />

                {/* Email Address */}
                {(authWith === 'email' || (numberofoptions === 1 && options[0] === 'email')) && (
                    <div className="space-y-1 mb-4">
                        <div className="flex items-center justify-between">
                            <label htmlFor="email" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Address
                            </label>
                            {numberofoptions > 1 && (
                                <button
                                    type="button"
                                    onClick={() => toggleAuthWith('phone')}
                                    className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline"
                                >
                                    Use phone instead
                                </button>
                            )}
                        </div>
                        <div className="relative">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                placeholder="Enter your email address"
                            />
                            {/* Get Code Button - Only for OTP method, floating above input */}
                            {authMethod === 'otp' && (
                                <button
                                    type="button"
                                    onClick={requestLoginOTP}
                                    disabled={otpRequested && countdown > 0}
                                    className="absolute top-1/2 -translate-y-1/2 right-2 px-3 py-2 flex items-center text-xs font-medium bg-primary-200 dark:bg-primary-900/40 backdrop-blur-sm border border-primary-200/50 dark:border-primary-700/50 text-primary-700 dark:text-primary-300 hover:bg-primary-100/90 dark:hover:bg-primary-900/60 disabled:bg-gray-100/90 dark:disabled:bg-gray-800/90 disabled:text-gray-400 dark:disabled:text-gray-500 disabled:cursor-not-allowed transition-all duration-200 rounded-md shadow-md hover:shadow-lg z-10"
                                >
                                    {otpRequested && countdown > 0 ? `Resend (${countdown}s)` : 'Get Code'}
                                </button>
                            )}
                        </div>
                        {errors.email && (
                            <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.email}</p>
                        )}

                        {/* Authentication Type Status Messages */}
                        {authTypeMessage && (
                            <div className={`text-sm p-3 rounded-lg ${
                                authTypeMessageType === 'success' 
                                    ? 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400' 
                                    : 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400'
                            }`}>
                                {authTypeMessage}
                            </div>
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
                            {numberofoptions > 1 && (
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
                            <div className="relative" ref={countryDropdownRef}>
                                <button
                                    type="button"
                                    onClick={() => setCountryDropdownOpen(!countryDropdownOpen)}
                                    className="flex items-center bg-white dark:bg-gray-700 dark:text-white py-3 pl-3 pr-2 rounded-l-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                >
                                    <img className="w-5 h-5 mr-2" src={selectedCountry?.flag_url ? (selectedCountry.flag_url.startsWith('http') ? selectedCountry.flag_url : `/${selectedCountry.flag_url}`) : '/flags/et.svg'} alt="" />
                                    <span className="text-sm font-medium">{selectedCountry?.dial_code || '+251'}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 ml-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                                    </svg>
                                </button>

                                {/* Country Dropdown */}
                                {countryDropdownOpen && (
                                    <div className="absolute left-0 top-full z-10 mt-1 w-64 bg-white dark:bg-gray-700 shadow-lg rounded-lg border border-gray-200 dark:border-gray-600 max-h-60 overflow-y-auto">
                                        {countries?.map((country) => (
                                            <button
                                                key={country.id}
                                                type="button"
                                                onClick={() => {
                                                    setSelectedCountry(country);
                                                    setCountryDropdownOpen(false);
                                                }}
                                                className="flex items-center w-full px-3 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-600 first:rounded-t-lg last:rounded-b-lg"
                                            >
                                                <img className="w-5 h-5 mr-3" src={country.flag_url ? `/storage/${country.flag_url}` : '/flags/et.svg'} alt="" />
                                                <span className="font-medium">{country.dial_code}</span>
                                                <span className="ml-2 text-gray-500 dark:text-gray-400">{country.name}</span>
                                            </button>
                                        ))}
                                    </div>
                                )}
                            </div>

                            <div className="relative flex-1">
                                <input
                                    style={{height:'45px'}}
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    value={data.phone}
                                    onChange={(e) => setData('phone', e.target.value)}
                                    className="block w-full rounded-r-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    placeholder="Enter your phone number"
                                />
                                {/* Get Code Button - Only for OTP method, floating above input */}
                                {authMethod === 'otp' && (
                                    <button
                                        type="button"
                                        onClick={requestLoginOTP}
                                        disabled={otpRequested && countdown > 0}
                                        className="absolute top-1/2 -translate-y-1/2 right-2 px-3 py-2 flex items-center text-xs font-medium bg-primary-200 dark:bg-primary-900/40 backdrop-blur-sm border border-primary-200/50 dark:border-primary-700/50 text-primary-700 dark:text-primary-300 hover:bg-primary-100/90 dark:hover:bg-primary-900/60 disabled:bg-gray-100/90 dark:disabled:bg-gray-800/90 disabled:text-gray-400 dark:disabled:text-gray-500 disabled:cursor-not-allowed transition-all duration-200 rounded-md shadow-md hover:shadow-lg z-10"
                                    >
                                        {otpRequested && countdown > 0 ? `Resend(${countdown}s)` : 'Get Code'}
                                    </button>
                                )}
                            </div>
                        </div>
                        {errors.phone && (
                            <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.phone}</p>
                        )}

                        {/* Authentication Type Status Messages */}
                        {authTypeMessage && (
                            <div className={`text-sm p-3 rounded-lg ${
                                authTypeMessageType === 'success' 
                                    ? 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400' 
                                    : 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400'
                            }`}>
                                {authTypeMessage}
                            </div>
                        )}
                    </div>
                )}

                {/* Password Authentication */}
                {authMethod === 'password' && (
                    <div className="py-3 mt-4">
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
                                placeholder="Enter your password"
                                autoComplete="current-password"
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
                )}

                {/* OTP Authentication - Verification Code Input */}
                {authMethod === 'otp' && otpRequested && (
                    <div className="space-y-2 mt-4">
                        <label htmlFor="otp" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Verification Code
                        </label>
                        <input
                            id="otp"
                            name="otp"
                            type="text"
                            value={data.otp}
                            onChange={(e) => setData('otp', e.target.value)}
                            className="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                            placeholder="Enter verification code"
                            autoComplete="one-time-code"
                        />
                        {errors.otp && (
                            <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.otp}</p>
                        )}

                        {/* OTP Status Messages */}
                        {otpMessage && (
                            <div className={`text-sm p-3 rounded-lg ${
                                otpMessageType === 'success' 
                                    ? 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400' 
                                    : 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400'
                            }`}>
                                {otpMessage}
                            </div>
                        )}
                    </div>
                )}

                {/* Terms Agreement */}
                <div className="pt-4">
                    <p className="text-xs text-gray-500 dark:text-gray-400 text-left">
                        By continuing, you agree to our{' '}
                        <Link href={route('v2.privacy-policy')} className="text-primary-600 hover:text-primary-500 dark:text-primary-400">
                            Terms of Service
                        </Link>{' '}
                        and{' '}
                        <Link href={route('v2.privacy-policy')} className="text-primary-600 hover:text-primary-500 dark:text-primary-400">
                            Privacy Policy
                        </Link>.
                    </p>
                </div>

                {/* Submit Button */}
                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing}
                        className={`w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 ${
                            processing 
                                ? 'bg-primary-400 cursor-not-allowed' 
                                : 'bg-primary-600 hover:bg-primary-700'
                        } text-white`}
                    >
                        {!processing ? (
                            <span className="flex items-center justify-center">
                                <i className="fas fa-sign-in-alt mr-2"></i>
                                Sign In
                            </span>
                        ) : (
                            <span className="flex items-center justify-center">
                                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Signing In...
                            </span>
                        )}  
                    </button>
                </div>

                {/* Links Section */}
                <div className="space-y-4 pt-6">
                    {/* Forgot Password - Only show for password-based authentication */}
                    {authMethod === 'password' && (
                        <div className="text-center">
                            <Link
                                href={route('v2.password.request')}
                                className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                            >
                                Forgot your password?
                            </Link>
                        </div>
                    )}

                    {/* Sign Up */}
                    {registrationEnabled && (
                        <div className="text-center">
                            <span className="text-sm text-gray-600 dark:text-gray-400">
                                Don't have an account?{' '}
                            </span>
                            <Link
                                href={route('v2.authflow.get-otp') + '?for=register'}
                                className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                            >
                                Sign up
                            </Link>
                        </div>
                    )}
                </div>
            </form>
        </GuestLayout>
    );
}

