import React from 'react';
import { Head } from '@inertiajs/react';

export default function Test({ message, timestamp }) {
    return (
        <>
            <Head title="Inertia Test Page" />
            <div className="min-h-screen bg-white dark:bg-[#0F172A] flex items-center justify-center">
                <div className="max-w-2xl mx-auto px-4 py-16">
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                        <h1 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                            🎉 Inertia.js + React is Working!
                        </h1>
                        <p className="text-lg text-gray-600 dark:text-gray-300 mb-6">
                            This is a test page to verify that Inertia.js is properly configured with React.
                        </p>
                        <div className="space-y-4">
                            <div className="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <p className="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">
                                    Message from Laravel:
                                </p>
                                <p className="text-blue-900 dark:text-blue-100">
                                    {message || 'No message provided'}
                                </p>
                            </div>
                            <div className="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <p className="text-sm font-semibold text-green-800 dark:text-green-200 mb-2">
                                    Server Timestamp:
                                </p>
                                <p className="text-green-900 dark:text-green-100">
                                    {timestamp || 'No timestamp provided'}
                                </p>
                            </div>
                        </div>
                        <div className="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                Features Demonstrated:
                            </h2>
                            <ul className="space-y-2 text-gray-600 dark:text-gray-300">
                                <li className="flex items-center">
                                    <span className="text-green-500 mr-2">✓</span>
                                    Inertia.js server-side rendering
                                </li>
                                <li className="flex items-center">
                                    <span className="text-green-500 mr-2">✓</span>
                                    React component rendering
                                </li>
                                <li className="flex items-center">
                                    <span className="text-green-500 mr-2">✓</span>
                                    Data passing from Laravel to React
                                </li>
                                <li className="flex items-center">
                                    <span className="text-green-500 mr-2">✓</span>
                                    Dark mode support
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

