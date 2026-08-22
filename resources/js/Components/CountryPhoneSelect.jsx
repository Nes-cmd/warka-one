import React, { useState, useRef, useEffect, useMemo } from 'react';

function CountryFlag({ country, className }) {
    const src = country?.flag_url
        ? (country.flag_url.startsWith('http') ? country.flag_url : `/${country.flag_url}`)
        : '/flags/et.svg';

    return <img className={className} src={src} alt="" />;
}

/**
 * The dial-code prefix button + searchable, scroll-capped country dropdown used on every
 * phone-entry auth page (Login, ForgotPassword, GetOtp). Extracted so the search/scroll
 * behavior only has to be built and maintained once.
 */
export default function CountryPhoneSelect({ countries = [], selectedCountry, onSelect }) {
    const [open, setOpen] = useState(false);
    const [search, setSearch] = useState('');
    const containerRef = useRef(null);
    const searchInputRef = useRef(null);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (containerRef.current && !containerRef.current.contains(event.target)) {
                setOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    useEffect(() => {
        if (open) {
            setSearch('');
            const focusTimer = setTimeout(() => searchInputRef.current?.focus(), 0);
            return () => clearTimeout(focusTimer);
        }
    }, [open]);

    const filteredCountries = useMemo(() => {
        const query = search.trim().toLowerCase();
        if (!query) return countries;

        return countries.filter((country) =>
            country.name?.toLowerCase().includes(query) ||
            country.dial_code?.toLowerCase().includes(query) ||
            country.country_code?.toLowerCase().includes(query)
        );
    }, [countries, search]);

    return (
        <div className="relative" ref={containerRef}>
            <button
                type="button"
                onClick={() => setOpen((isOpen) => !isOpen)}
                className="flex items-center bg-white dark:bg-gray-700 dark:text-white py-2.5 pl-3 pr-2 rounded-l-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
                <CountryFlag country={selectedCountry} className="w-5 h-5 mr-2" />
                <span className="text-sm font-medium">{selectedCountry?.dial_code || '+251'}</span>
                <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 ml-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                </svg>
            </button>

            {open && (
                <div className="absolute left-0 top-full z-20 mt-1 w-72 max-h-[min(24rem,60vh)] flex flex-col bg-white dark:bg-gray-700 shadow-lg rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                    <div className="p-2 border-b border-gray-200 dark:border-gray-600 shrink-0">
                        <input
                            ref={searchInputRef}
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Search country or code..."
                            className="w-full text-sm px-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
                        />
                    </div>
                    <div className="overflow-y-auto">
                        {filteredCountries.length > 0 ? (
                            filteredCountries.map((country) => (
                                <button
                                    key={country.id}
                                    type="button"
                                    onClick={() => {
                                        onSelect(country);
                                        setOpen(false);
                                    }}
                                    className="flex items-center w-full px-3 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <CountryFlag country={country} className="w-5 h-5 mr-3 shrink-0" />
                                    <span className="font-medium">{country.dial_code}</span>
                                    <span className="ml-2 text-gray-500 dark:text-gray-400 truncate">{country.name}</span>
                                </button>
                            ))
                        ) : (
                            <div className="px-3 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                No countries found
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}
