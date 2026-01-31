import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout';

export default function Account({ user, authorizedApps, sessions, status, flash_error }) {
    const formatDate = (dateString) => {
        if (!dateString) return 'Unknown';
        const date = new Date(dateString);
        return new Intl.RelativeTimeFormat('en', { numeric: 'auto' }).format(
            Math.round((date - new Date()) / (1000 * 60)),
            'minute'
        );
    };

    const formatDateLong = (dateString) => {
        if (!dateString) return 'Unknown';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        }).format(date);
    };

    const getBrowserIcon = (userAgent) => {
        const ua = (userAgent || '').toLowerCase();
        if (ua.includes('chrome')) return 'Chrome';
        if (ua.includes('firefox')) return 'Firefox';
        if (ua.includes('safari')) return 'Safari';
        return 'Unknown';
    };

    const handleRevokeToken = (tokenId) => {
        if (confirm('Are you sure you want to revoke access for this application?')) {
            router.post(route('profile.revoke-token'), { token_id: tokenId });
        }
    };

    return (
        <AuthenticatedLayout title="My Account">
            <div className="md:mt-20 mb-20 mt-5 dark:text-gray-200 font-poppins">
                {/* Status Messages */}
                {status && (
                    <div className="mb-4 font-medium text-sm text-green-600 dark:text-green-400 p-4 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        {status}
                    </div>
                )}

                {flash_error && (
                    <div className="mb-4 font-medium text-sm text-red-600 dark:text-red-400 p-4 bg-red-100 dark:bg-red-900/20 rounded-lg">
                        {flash_error}
                    </div>
                )}

                <section className="flex flex-col md:flex-row gap-5">
                    {/* Profile Card */}
                    <div className="w-full md:w-1/3 lg:w-1/4">
                        <div className="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                            <div className="flex flex-col items-center">
                                <div className="relative">
                                    <div className="h-24 w-24 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700">
                                        {user?.profile_photo_path ? (
                                            <img 
                                                src={`/storage/${user.profile_photo_path}`}
                                                alt={user?.name || 'User'} 
                                                className="h-full w-full object-cover"
                                            />
                                        ) : (
                                            <div className="h-full w-full flex items-center justify-center bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary">
                                                <span className="text-2xl font-bold">
                                                    {(user?.name || user?.email || 'U').charAt(0).toUpperCase()}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>
                                <h2 className="mt-4 text-xl font-semibold">{user?.name || 'User'}</h2>
                                <div className="mt-2">
                                    {user?.email && (
                                        <div className="flex items-center text-gray-600 dark:text-gray-400">
                                            <span>{user.email}</span>
                                            {user.email_verified_at && (
                                                <span className="ml-2 text-green-500" title="Verified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                                    </svg>
                                                </span>
                                            )}
                                        </div>
                                    )}
                                    
                                    {user?.phone && (
                                        <div className="flex items-center text-gray-600 dark:text-gray-400 mt-1">
                                            <span>{user.country?.dial_code || ''}{user.phone}</span>
                                            {user.phone_verified_at && (
                                                <span className="ml-2 text-green-500" title="Verified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                                    </svg>
                                                </span>
                                            )}
                                        </div>
                                    )}
                                </div>
                                
                                <div className="mt-6 w-full">
                                    <Link 
                                        href={route('v2.profile.setting')} 
                                        className="block w-full text-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-600 transition-colors"
                                    >
                                        Edit Profile
                                    </Link>
                                </div>
                            </div>
                        </div>

                        {/* Additional User Info Card */}
                        {user?.user_detail && (
                            <div className="mt-5 bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                                <h3 className="text-lg font-semibold mb-4">Personal Information</h3>
                                <div className="space-y-3">
                                    {user.user_detail.gender && (
                                        <div>
                                            <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</h4>
                                            <p>{user.user_detail.gender}</p>
                                        </div>
                                    )}
                                    
                                    {user.user_detail.birth_date && (
                                        <div>
                                            <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Date</h4>
                                            <p>{formatDateLong(user.user_detail.birth_date)}</p>
                                        </div>
                                    )}
                                    
                                    {user.user_detail.address && (
                                        <div>
                                            <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400">Address</h4>
                                            <p>{user.user_detail.address}</p>
                                        </div>
                                    )}
                                    
                                    {user.user_detail.city && (
                                        <div>
                                            <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400">City</h4>
                                            <p>{user.user_detail.city}</p>
                                        </div>
                                    )}
                                    
                                    {user.user_detail.country && (
                                        <div>
                                            <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400">Country</h4>
                                            <p>{user.user_detail.country.name}</p>
                                        </div>
                                    )}
                                    
                                    {user.created_at && (
                                        <div>
                                            <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</h4>
                                            <p>{formatDateLong(user.created_at)}</p>
                                        </div>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Main Content Area */}
                    <div className="w-full md:w-2/3 lg:w-3/4 mt-5 md:mt-0">
                        {/* Active Sessions */}
                        <div className="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-5">
                            <h3 className="text-lg font-semibold mb-4">Active Sessions</h3>
                            
                            <div className="overflow-hidden">
                                <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead className="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Device</th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Activity</th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        {sessions && sessions.length > 0 ? (
                                            sessions.map((session, index) => (
                                                <tr key={index}>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <div className="flex items-center">
                                                            <div className="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                                                                <svg className="h-6 w-6 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm-1 4a1 1 0 0 1 2 0v4a1 1 0 0 1-2 0V8z"/>
                                                                </svg>
                                                            </div>
                                                            <div className="ml-4">
                                                                <div className="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                    {session.browser || 'Unknown Browser'}
                                                                </div>
                                                                <div className="text-sm text-gray-500 dark:text-gray-400">
                                                                    {session.platform || 'Unknown Device'}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <div className="text-sm text-gray-900 dark:text-gray-100">{session.ip_address || 'Unknown'}</div>
                                                        <div className="text-sm text-gray-500 dark:text-gray-400">{session.location || 'Unknown location'}</div>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <div className="text-sm text-gray-900 dark:text-gray-100">
                                                            {formatDate(session.last_active)}
                                                        </div>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        {session.is_current_device ? (
                                                            <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                                Current Session
                                                            </span>
                                                        ) : (
                                                            <button 
                                                                onClick={() => handleRevokeToken(session.id)}
                                                                className="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                            >
                                                                Revoke
                                                            </button>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan="4" className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                    No active sessions found
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {/* Connected Applications */}
                        <div className="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-lg font-semibold">Connected Applications</h3>
                                <Link href={route('v2.clients.index')} className="text-sm text-primary dark:text-secondary-400 hover:underline">
                                    My Apps
                                </Link>
                            </div>
                            
                            <p className="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                These are applications you've authorized to access your account. You can revoke access at any time.
                            </p>
                            
                            <div className="space-y-4">
                                {authorizedApps && authorizedApps.length > 0 ? (
                                    authorizedApps.map((token) => (
                                        <div key={token.id} className="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div className="flex items-center">
                                                <div className="bg-primary/10 dark:bg-primary/20 p-3 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-primary dark:text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                </div>
                                                <div className="ml-4">
                                                    <div className="flex items-center gap-2">
                                                        <div className="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {token.client?.name || 'Unknown Application'}
                                                        </div>
                                                        {token.client?.redirect && (() => {
                                                            try {
                                                                const url = new URL(token.client.redirect);
                                                                const mainUrl = `${url.protocol}//${url.host}`;
                                                                return (
                                                                    <a 
                                                                        href={mainUrl}
                                                                        className="text-primary dark:text-secondary-400 hover:underline text-xs flex items-center gap-1"
                                                                        title="Visit application"
                                                                    >
                                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                        </svg>
                                                                        Visit
                                                                    </a>
                                                                );
                                                            } catch (e) {
                                                                return null;
                                                            }
                                                        })()}
                                                    </div>
                                                    <div className="text-sm text-gray-500 dark:text-gray-400">
                                                        Last used: {token.last_used_at ? formatDate(token.last_used_at) : 'Never'}
                                                    </div>
                                                    <div className="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        Authorized: {formatDateLong(token.created_at)}
                                                    </div>
                                                </div>
                                            </div>
                                            <button 
                                                onClick={() => handleRevokeToken(token.id)}
                                                className="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-800 transition-colors"
                                            >
                                                Revoke
                                            </button>
                                        </div>
                                    ))
                                ) : (
                                    <div className="py-4 text-center text-gray-500 dark:text-gray-400">
                                        <p>You haven't connected any applications yet.</p>
                                        <p className="mt-1 text-sm">When you authorize applications to access your account, they will appear here.</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </AuthenticatedLayout>
    );
}

