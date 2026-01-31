import React from 'react';
export default function TextInput({ 
    type = 'text', 
    className = '', 
    disabled = false,
    ...props 
}) {
    const baseClasses = 'border-gray-300 dark:border-slate-700 bg-transparent focus:border-indigo-500 dark:focus:border-slate-500 focus:ring-indigo-500 dark:focus:ring-slate-500 rounded-md shadow-sm';
    
    return (
        <input
            type={type}
            disabled={disabled}
            className={`${baseClasses} ${className}`}
            {...props}
        />
    );
}

