import React from 'react';
export default function InputLabel({ htmlFor, value, children, className = '' }) {
    const labelContent = value || children || '';
    
    return (
        <label 
            htmlFor={htmlFor} 
            className={`block font-medium text-sm text-gray-500 dark:text-gray-300 ${className}`}
        >
            {labelContent}
        </label>
    );
}

