import React from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedNavbar from '../Components/AuthenticatedNavbar';

export default function AuthenticatedLayout({ children, title }) {
    return (
        <div className="min-h-screen bg-white dark:bg-[#0F172A]">
            <Head title={title || 'Account'} />
            <AuthenticatedNavbar />
            <main className="max-w-[1480px] mx-auto px-4 lg:px-24 md:px-12">
                {children}
            </main>
        </div>
    );
}

