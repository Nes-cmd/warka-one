import React, { useState, useEffect, useRef } from 'react';
import { Head, useForm, Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import GuestLayout from '../Layouts/GuestLayout';

export default function VerifyOtp({ 
    authwith, 
    email, 
    phone, 
    country, 
    verificationFor,
    resendin: initialResendin 
}) {
    const [code, setCode] = useState(['', '', '', '', '', '']);
    const [currentIndex, setCurrentIndex] = useState(0);
    
    // Get countdown from localStorage or use initial value
    const getStoredCountdown = () => {
        const stored = localStorage.getItem(`otp_countdown_${authwith}_${email || phone}`);
        if (stored) {
            try {
                const parsed = JSON.parse(stored);
                const elapsed = Math.floor((Date.now() - parsed.timestamp) / 1000);
                const remaining = Math.max(0, parsed.countdown - elapsed);
                return remaining > 0 ? remaining : 90;
            } catch (e) {
                return initialResendin || 90;
            }
        }
        return initialResendin || 90;
    };
    
    const initialCountdown = getStoredCountdown();
    const [resendin, setResendin] = useState(initialCountdown);
    const [canResend, setCanResend] = useState(initialCountdown === 90);
    const [resendMessage, setResendMessage] = useState('');
    const [resendMessageType, setResendMessageType] = useState(''); // 'success' or 'error'
    const inputRefs = useRef([]);

    const { data, setData, post, processing, errors } = useForm({
        verificationCode: '',
    });

    // Handle countdown for resend and persist to localStorage
    useEffect(() => {
        // Update canResend based on countdown
        setCanResend(resendin === 90);
        
        if (resendin < 90 && resendin > 0) {
            // Store countdown in localStorage
            localStorage.setItem(`otp_countdown_${authwith}_${email || phone}`, JSON.stringify({
                countdown: resendin,
                timestamp: Date.now()
            }));
            
            const timer = setTimeout(() => {
                setResendin(resendin - 1);
            }, 1000);
            return () => clearTimeout(timer);
        } else if (resendin === 0) {
            setCanResend(true);
            setResendin(90);
            // Clear stored countdown
            localStorage.removeItem(`otp_countdown_${authwith}_${email || phone}`);
        } else if (resendin === 90) {
            // Clear stored countdown when at 90
            localStorage.removeItem(`otp_countdown_${authwith}_${email || phone}`);
        }
    }, [resendin, authwith, email, phone]);

    // Update verification code when code array changes
    useEffect(() => {
        setData('verificationCode', code.join(''));
    }, [code]);

    // Auto-focus first input on mount
    useEffect(() => {
        if (inputRefs.current[0]) {
            inputRefs.current[0].focus();
        }
    }, []);

    // Auto-send code on mount only for 'must-verify'
    useEffect(() => {
        if (verificationFor === 'must-verify' && canResend) {
            handleResend(true);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []); // Only run on mount

    const handleCodeInput = (index, event) => {
        const value = event.target.value;
        
        if (value.length > 0) {
            const newCode = [...code];
            newCode[index] = value.slice(-1);
            setCode(newCode);
            
            if (index < 5) {
                setCurrentIndex(index + 1);
                setTimeout(() => {
                    if (inputRefs.current[index + 1]) {
                        inputRefs.current[index + 1].focus();
                    }
                }, 0);
            }
        } else {
            const newCode = [...code];
            newCode[index] = '';
            setCode(newCode);
        }
    };

    const handleKeyDown = (index, event) => {
        if (event.key === 'Backspace' && code[index] === '') {
            if (index > 0) {
                setCurrentIndex(index - 1);
                setTimeout(() => {
                    if (inputRefs.current[index - 1]) {
                        inputRefs.current[index - 1].focus();
                    }
                }, 0);
            }
        }
    };

    const handlePaste = (event) => {
        event.preventDefault();
        const pastedData = event.clipboardData.getData('text').trim();
        if (/^\d{6}$/.test(pastedData)) {
            const newCode = pastedData.split('');
            setCode(newCode);
            setData('verificationCode', pastedData);
            if (inputRefs.current[5]) {
                inputRefs.current[5].focus();
            }
        }
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('v2.authflow.verify.store'), {
            // Backend will handle the redirect, so we don't need onSuccess
        });
    };

    const handleResend = async (autoSend = false) => {
        // For auto-send, don't check canResend
        if (!autoSend && !canResend) return;
        
        setCanResend(false);
        setResendin(89);
        setResendMessage(''); // Clear previous message
        
        try {
            const response = await fetch(route('v2.authflow.resend-otp'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    authwith: authwith,
                }),
            });

            const result = await response.json();
            if (result.success) {
                // Store countdown in localStorage
                localStorage.setItem(`otp_countdown_${authwith}_${email || phone}`, JSON.stringify({
                    countdown: 89,
                    timestamp: Date.now()
                }));
                setResendMessage('Verification code sent successfully!');
                setResendMessageType('success');
            } else {
                setCanResend(true);
                setResendin(90);
                localStorage.removeItem(`otp_countdown_${authwith}_${email || phone}`);
                setResendMessage(result.message || 'Failed to send verification code. Please try again.');
                setResendMessageType('error');
            }
        } catch (error) {
            console.error('Error resending OTP:', error);
            setCanResend(true);
            setResendin(90);
            localStorage.removeItem(`otp_countdown_${authwith}_${email || phone}`);
            setResendMessage('An error occurred. Please try again later.');
            setResendMessageType('error');
        }
    };

    const displayContact = authwith === 'email' 
        ? email 
        : `${country?.dial_code || ''} ${phone}`;

    return (
        <GuestLayout title="Verify Your Code">
            <div className="text-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">Verify Your Code</h1>
                <p className="text-gray-600 dark:text-gray-400">Enter the verification code sent to your device</p>
            </div>

            <div className="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
                We have sent a 6-digit verification code to your {authwith}. Please enter the code below to continue.
            </div>

            <form onSubmit={submit} className="">
                {/* Contact Information Display */}
                <div className="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-3">
                            <div className="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                <i className={`fas fa-${authwith === 'email' ? 'envelope' : 'phone'} text-primary-600 dark:text-primary-400`}></i>
                            </div>
                            <div>
                                <p className="text-sm text-gray-500 dark:text-gray-400">Code sent to:</p>
                                <p className="font-medium text-gray-900 dark:text-white">
                                    {displayContact}
                                </p>
                            </div>
                        </div>
                        
                        {/* Resend Button */}
                        <button
                            type="button"
                            onClick={handleResend}
                            disabled={!canResend}
                            className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 px-3 py-2 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {canResend ? 'Resend Code' : `Resend in ${resendin}s`}
                        </button>
                    </div>
                </div>
                {/* Resend Status Message */}
                    {resendMessage && (
                        <div className={`text-sm p-2 rounded-lg mt-0 ${
                            resendMessageType === 'success' 
                                ? 'text-green-500' 
                                : 'text-red-500'
                        }`}>
                            {resendMessage}
                        </div>
                    )}
                {/* Verification Code Input */}
                <div className="space-y-4 mt-2">
                    <label htmlFor="verificationCode" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Enter 6-digit code
                    </label>
                    
                    <div className="flex justify-center space-x-2" onPaste={handlePaste}>
                        {[0, 1, 2, 3, 4, 5].map((index) => (
                            <input
                                key={index}
                                ref={(el) => (inputRefs.current[index] = el)}
                                id={`code-${index}`}
                                type="text"
                                inputMode="numeric"
                                pattern="[0-9]*"
                                maxLength="1"
                                value={code[index]}
                                onChange={(e) => handleCodeInput(index, e)}
                                onKeyDown={(e) => handleKeyDown(index, e)}
                                className={`w-12 h-12 text-center text-xl font-semibold border-2 rounded-lg focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 bg-white dark:bg-gray-700 dark:text-white transition-all duration-200 ${
                                    code[index] !== '' 
                                        ? 'border-primary-500 dark:border-primary-400' 
                                        : 'border-gray-300 dark:border-gray-600'
                                }`}
                                autoComplete="one-time-code"
                            />
                        ))}
                    </div>
                    
                    {errors.verificationCode && (
                        <p className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.verificationCode}</p>
                    )}
                </div>

                {/* Submit Button */}
                <div className="pt-4 mt-4">
                    <button
                        type="submit"
                        disabled={processing || code.join('').length !== 6}
                        className={`w-full py-3 flex items-center justify-center text-lg font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none ${
                            processing || code.join('').length !== 6
                                ? 'bg-primary-400' 
                                : 'bg-primary-600 hover:bg-primary-700'
                        } text-white`}
                    >
                        {processing ? (
                            <svg className="animate-spin h-5 w-5 text-white mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        ) : (
                            <i className="fas fa-check-circle mr-2"></i>
                        )}
                        Verify Code
                    </button>
                </div>

                {/* Back Link */}
                <div className="text-center pt-6">
                    <Link
                        href={route('v2.authflow.get-otp') + `?for=${verificationFor || 'register'}`}
                        className="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                    >
                        <i className="fas fa-arrow-left mr-1"></i>
                        Back to get code
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}

