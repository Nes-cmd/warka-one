import React from 'react';
export default function SecondaryButton({ 
    type = 'button', 
    className = '', 
    disabled = false,
    children,
    ...props 
}) {
    return (
        <button
            type={type}
            disabled={disabled}
            className={`inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 ${className}`}
            {...props}
        >
            {children}
        </button>
    );
}

