import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import HomeLayout from '../Layouts/HomeLayout';

export default function Contact({ captcha_num1: initialCaptcha1, captcha_num2: initialCaptcha2, success, error }) {
    const [captchaNum1, setCaptchaNum1] = useState(initialCaptcha1);
    const [captchaNum2, setCaptchaNum2] = useState(initialCaptcha2);
    
    const { data, setData, post, processing, errors, reset } = useForm({
        contact: '',
        title: '',
        message: '',
        captcha: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('v2.contact.submit'), {
            onSuccess: (page) => {
                // Update captcha if new values are provided
                if (page.props.captcha_num1 && page.props.captcha_num2) {
                    setCaptchaNum1(page.props.captcha_num1);
                    setCaptchaNum2(page.props.captcha_num2);
                }
                // Reset form only on success
                if (page.props.success) {
                    reset();
                }
            },
        });
    };

    return (
        <HomeLayout title="Contact Us">
            <section className="max-w-7xl mx-auto my-32 lg:px-0 px-4 relative">
                <h1 className="text-5xl font-semibold sm:text-start text-center dark:text-white text-slate-900">
                    Let's talk
                </h1>
                <div className="w-full flex sm:flex-row flex-col items-center my-10 gap-5">
                    <p className="dark:text-white text-slate-900 sm:w-1/3 sm:px-0 px-4 self-start">
                        Whether you have questions about our products and services, need technical support, or want to explore potential partnerships, our Contact Us page is your gateway to reaching out. Simply fill out the provided form, and we'll get back to you promptly. We appreciate your time and look forward to hearing from you soon!
                    </p>

                    <div className="sm:w-2/3 w-[90%]">
                        {success && (
                            <div className="sm:w-2/3 w-[90%] p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-800/20 dark:text-green-400" role="alert">
                                {success}
                            </div>
                        )}

                        {error && (
                            <div className="sm:w-2/3 w-[90%] p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-800/20 dark:text-red-400" role="alert">
                                {error}
                            </div>
                        )}

                        <form onSubmit={submit} className="border dark:border-white/40 border-gray-400 flex flex-col p-5 justify-center gap-5 rounded-lg">
                            <h1 className="dark:text-white text-slate-900 text-center">
                                Get in touch
                            </h1>
                            
                            <div className="flex flex-col gap-4">
                                <label className="dark:text-white text-slate-900" htmlFor="contact">Phone or Email</label>
                                <input
                                    id="contact"
                                    name="contact"
                                    value={data.contact}
                                    onChange={(e) => setData('contact', e.target.value)}
                                    className="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                                    type="text"
                                />
                                {errors.contact && (
                                    <span className="text-red-600 dark:text-red-400 text-sm">{errors.contact}</span>
                                )}
                            </div>

                            <div className="flex flex-col gap-4">
                                <label className="dark:text-white text-slate-900" htmlFor="title">Title your request</label>
                                <input
                                    id="title"
                                    name="title"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    className="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                                    type="text"
                                />
                                {errors.title && (
                                    <span className="text-red-600 dark:text-red-400 text-sm">{errors.title}</span>
                                )}
                            </div>

                            <div className="flex flex-col gap-4">
                                <label className="dark:text-white text-slate-900" htmlFor="message">Your request</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="4"
                                    value={data.message}
                                    onChange={(e) => setData('message', e.target.value)}
                                    className="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                                ></textarea>
                                {errors.message && (
                                    <span className="text-red-600 dark:text-red-400 text-sm">{errors.message}</span>
                                )}
                            </div>

                            {/* Simple CAPTCHA */}
                            <div className="flex flex-col gap-4">
                                <label className="dark:text-white text-slate-900" htmlFor="captcha">
                                    Verify you're human: What is {captchaNum1} + {captchaNum2}?
                                </label>
                                <input
                                    id="captcha"
                                    name="captcha"
                                    type="number"
                                    value={data.captcha}
                                    onChange={(e) => setData('captcha', e.target.value)}
                                    className="border dark:text-white text-slate-900 bg-transparent border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                                    required
                                />
                                {errors.captcha && (
                                    <span className="text-red-600 dark:text-red-400 text-sm">{errors.captcha}</span>
                                )}
                            </div>

                            <div className="flex justify-center">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="dark:text-white text-slate-900 px-4 py-2 my-7 rounded-full border border-slate-900 dark:border-white/40 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer inline-flex items-center disabled:opacity-75 disabled:cursor-not-allowed"
                                >
                                    <span>Submit</span>
                                    {processing && (
                                        <svg className="w-5 h-5 ml-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </HomeLayout>
    );
}

