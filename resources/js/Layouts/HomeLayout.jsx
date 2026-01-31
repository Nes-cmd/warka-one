import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '../Components/Navbar';
import Footer from '../Components/Footer';

export default function HomeLayout({ children, title }) {
    return (
        <>
            <Head title={title || 'Laravel'} />
            <div className="font-sans text-gray-900 dark:bg-[#0F172A] bg-[#EAE9F0] justify-center items-center flex-column min-h-screen">
                <header className="lg:px-24 md:px-12 px-4">
                    <Navbar />
                </header>
                <main className="lg:px-24 md:px-12 px-4">
                    {children}
                </main>
                <footer className="lg:px-24 md:px-12 px-4">
                    <Footer />
                </footer>
            </div>
        </>
    );
}

