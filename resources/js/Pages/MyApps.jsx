import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout';
import DangerButton from '../Components/DangerButton';
import SecondaryButton from '../Components/SecondaryButton';

export default function MyApps({ clients, success }) {
    const [showSecrets, setShowSecrets] = useState({});
    const [copiedItems, setCopiedItems] = useState({});
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [clientToDelete, setClientToDelete] = useState(null);

    const toggleSecret = (clientId) => {
        setShowSecrets(prev => ({
            ...prev,
            [clientId]: !prev[clientId]
        }));
    };

    const copyToClipboard = async (text, itemKey) => {
        try {
            await navigator.clipboard.writeText(text);
            setCopiedItems(prev => ({ ...prev, [itemKey]: true }));
            setTimeout(() => {
                setCopiedItems(prev => ({ ...prev, [itemKey]: false }));
            }, 1500); // Reduced from 2000ms to 1500ms for faster feedback
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    };

    const handleDeleteClick = (clientId, clientName) => {
        setClientToDelete({ id: clientId, name: clientName });
        setShowDeleteModal(true);
    };

    const handleDeleteConfirm = () => {
        if (clientToDelete) {
            router.delete(route('v2.clients.destroy', clientToDelete.id), {
                preserveScroll: false,
                onSuccess: () => {
                    setShowDeleteModal(false);
                    setClientToDelete(null);
                },
            });
        }
    };

    const handleDeleteCancel = () => {
        setShowDeleteModal(false);
        setClientToDelete(null);
    };

    const formatAuthTypes = (authTypes) => {
        if (!authTypes) return 'N/A';
        if (typeof authTypes === 'string') {
            try {
                const parsed = JSON.parse(authTypes);
                return Array.isArray(parsed) ? parsed.join(', ') : parsed;
            } catch {
                return authTypes;
            }
        }
        if (Array.isArray(authTypes)) {
            return authTypes.join(', ');
        }
        return 'N/A';
    };

    const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    };

    return (
        <AuthenticatedLayout>
            <Head title="My Applications" />

            <div className="md:mt-20 mb-20 mt-5 dark:text-gray-200 font-poppins">
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-2xl font-bold">My Applications</h1>
                    <Link 
                        href={route('v2.clients.create')} 
                        className="bg-primary text-white px-4 py-2 rounded-lg shadow-md hover:bg-primary-600 transition-colors duration-300"
                    >
                        Create New App
                    </Link>
                </div>

                {success && (
                    <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-800 dark:text-green-100 dark:border-green-700">
                        {success}
                    </div>
                )}

                <div className="grid md:grid-cols-1 gap-6">
                    {clients && clients.length > 0 ? (
                        clients.map((client) => (
                            <div key={client.id} className="bg-gray-100 dark:bg-slate-800 rounded-lg p-6 shadow-md">
                                <div className="flex justify-between items-start">
                                    <h2 className="text-xl font-semibold">{client.name}</h2>
                                    <div className="flex space-x-2">
                                        <Link 
                                            href={route('v2.clients.edit', client.id)} 
                                            className="text-primary dark:text-secondary-400 hover:underline"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </Link>
                                        <button
                                            onClick={() => handleDeleteClick(client.id, client.name)}
                                            className="text-red-500 hover:text-red-700"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fillRule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clipRule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div className="mt-4">
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        {client.description || 'No description provided'}
                                    </p>
                                </div>

                                <div className="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {/* Client ID */}
                                    <div className="col-span-2 md:col-span-1">
                                        <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Client ID</h3>
                                        <div className="mt-1 bg-gray-50 dark:bg-gray-700 p-2 rounded relative flex justify-between items-center">
                                            <p className="text-sm break-all pr-8">{client.id}</p>
                                            <div className="absolute right-2 flex items-center gap-2">
                                                {copiedItems[`client-id-${client.id}`] && (
                                                    <span className="text-xs text-green-600 dark:text-green-400 font-medium">Copied!</span>
                                                )}
                                                <button
                                                    onClick={() => copyToClipboard(client.id, `client-id-${client.id}`)}
                                                    className="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400"
                                                    title="Copy to clipboard"
                                                >
                                                    {copiedItems[`client-id-${client.id}`] ? (
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-green-500 dark:text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                        </svg>
                                                    ) : (
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                                        </svg>
                                                    )}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Client Secret */}
                                    <div className="col-span-2 md:col-span-1">
                                        <div className="flex justify-between items-center">
                                            <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Client Secret</h3>
                                            <div className="flex items-center gap-2">
                                                <button
                                                    onClick={() => toggleSecret(client.id)}
                                                    className="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                                >
                                                    {showSecrets[client.id] ? 'Hide' : 'Show'}
                                                </button>
                                                {copiedItems[`client-secret-${client.id}`] && (
                                                    <span className="text-xs text-green-600 dark:text-green-400 font-medium">Copied!</span>
                                                )}
                                                <button
                                                    onClick={() => copyToClipboard(client.secret, `client-secret-${client.id}`)}
                                                    className="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400"
                                                    title="Copy to clipboard"
                                                >
                                                    {copiedItems[`client-secret-${client.id}`] ? (
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-green-500 dark:text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                        </svg>
                                                    ) : (
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                                        </svg>
                                                    )}
                                                </button>
                                            </div>
                                        </div>
                                        <div className="mt-1 bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                            {showSecrets[client.id] ? (
                                                <p className="text-sm break-all">{client.secret}</p>
                                            ) : (
                                                <p className="text-sm">••••••••••••••••••••••••••••••</p>
                                            )}
                                        </div>
                                    </div>

                                    {/* Redirect URL */}
                                    <div className="col-span-2">
                                        <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Redirect URL</h3>
                                        <div className="mt-1 lg:w-[50%] bg-gray-50 dark:bg-gray-700 p-2 rounded relative flex justify-between items-center">
                                            <p className="text-sm break-all pr-8">{client.redirect}</p>
                                            <div className="absolute right-2 flex items-center gap-2">
                                                {copiedItems[`redirect-${client.id}`] && (
                                                    <span className="text-xs text-green-600 dark:text-green-400 font-medium">Copied!</span>
                                                )}
                                                <button
                                                    onClick={() => copyToClipboard(client.redirect, `redirect-${client.id}`)}
                                                    className="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400"
                                                    title="Copy to clipboard"
                                                >
                                                    {copiedItems[`redirect-${client.id}`] ? (
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-green-500 dark:text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                        </svg>
                                                    ) : (
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                                        </svg>
                                                    )}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Auth Methods */}
                                    <div>
                                        <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Auth Methods</h3>
                                        <p className="mt-1 text-sm">{formatAuthTypes(client.use_auth_types)}</p>
                                    </div>

                                    {/* Auth Type */}
                                    <div>
                                        <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Auth Type</h3>
                                        <p className="mt-1 text-sm">{client.pass_type ? client.pass_type.charAt(0).toUpperCase() + client.pass_type.slice(1) : 'N/A'}</p>
                                    </div>

                                    {/* Registration */}
                                    <div>
                                        <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Registration</h3>
                                        <p className="mt-1 text-sm">{client.registration_enabled ? 'Enabled' : 'Disabled'}</p>
                                    </div>

                                    {/* Created on */}
                                    <div>
                                        <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Created on</h3>
                                        <p className="mt-1 text-sm">{formatDate(client.created_at)}</p>
                                    </div>
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="bg-gray-100 dark:bg-slate-800 rounded-lg p-6 text-center">
                            <p>You haven't created any applications yet.</p>
                            <Link 
                                href={route('v2.clients.create')} 
                                className="mt-2 inline-block text-primary dark:text-secondary-400 hover:underline"
                            >
                                Create your first app
                            </Link>
                        </div>
                    )}
                </div>
            </div>

            {/* Delete Confirmation Modal */}
            {showDeleteModal && clientToDelete && (
                <div className="fixed inset-0 z-50 overflow-y-auto">
                    <div className="flex items-center justify-center min-h-screen px-4">
                        <div 
                            className="fixed inset-0 bg-gray-500 opacity-40 transition-opacity dark:bg-gray-900 dark:opacity-40" 
                            onClick={handleDeleteCancel}
                        ></div>
                        <div className="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 z-10">
                            <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Delete Application
                            </h2>
                            <p className="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                                Are you sure you want to delete <strong className="font-semibold text-gray-900 dark:text-gray-100">"{clientToDelete.name}"</strong>? This action cannot be undone and will permanently delete the application and all associated data.
                            </p>
                            <div className="flex justify-end gap-3">
                                <SecondaryButton
                                    type="button"
                                    onClick={handleDeleteCancel}
                                >
                                    Cancel
                                </SecondaryButton>
                                <DangerButton
                                    type="button"
                                    onClick={handleDeleteConfirm}
                                >
                                    Delete Application
                                </DangerButton>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}

