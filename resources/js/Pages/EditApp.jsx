import React, { useState } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout';
import InputLabel from '../Components/InputLabel';
import TextInput from '../Components/TextInput';
import InputError from '../Components/InputError';
import PrimaryButton from '../Components/PrimaryButton';

export default function EditApp({ client, success }) {
    const [showSecret, setShowSecret] = useState(false);
    const [copiedItems, setCopiedItems] = useState({});

    const { data, setData, post, processing, errors } = useForm({
        name: client.name || '',
        redirect: client.redirect || '',
        description: client.description || '',
        use_auth_types: client.use_auth_types || [],
        pass_type: client.pass_type || 'password',
        registration_enabled: client.registration_enabled || false,
    });

    const handleAuthTypeChange = (authType) => {
        const currentTypes = data.use_auth_types || [];
        if (currentTypes.includes(authType)) {
            setData('use_auth_types', currentTypes.filter(type => type !== authType));
        } else {
            setData('use_auth_types', [...currentTypes, authType]);
        }
    };

    const copyToClipboard = async (text, itemKey) => {
        try {
            await navigator.clipboard.writeText(text);
            setCopiedItems(prev => ({ ...prev, [itemKey]: true }));
            setTimeout(() => {
                setCopiedItems(prev => ({ ...prev, [itemKey]: false }));
            }, 2000);
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    };

    const handleRegenerateSecret = () => {
        if (confirm('Are you sure you want to revoke and regenerate this client secret? This action cannot be undone and any applications using this secret will need to be updated.')) {
            router.post(route('v2.clients.regenerate-secret', client.id), {}, {
                preserveScroll: false,
                onSuccess: () => {
                    // The page will reload with the new secret
                },
            });
        }
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('v2.clients.update', client.id), {
            preserveScroll: false,
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Edit Application" />

            <div className="md:mt-20 mb-20 mt-5 dark:text-gray-200 font-poppins">
                <div className="mb-6">
                    <h1 className="text-2xl font-bold">Edit Application</h1>
                    <p className="text-gray-600 dark:text-gray-400 mt-1">Update your OAuth application settings.</p>
                </div>

                {success && (
                    <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-800 dark:text-green-100 dark:border-green-700">
                        {success}
                    </div>
                )}

                <div className="bg-gray-100 dark:bg-slate-800 rounded-lg p-6 shadow-md">
                    <form onSubmit={submit}>
                        <div className="grid grid-cols-1 gap-6">
                            {/* Application Name */}
                            <div>
                                <InputLabel htmlFor="name" value="Application Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    className="block mt-1 w-full dark:text-white"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    required
                                    autoFocus
                                />
                                <InputError message={errors.name} className="mt-2" />
                            </div>

                            {/* Redirect URL */}
                            <div>
                                <InputLabel htmlFor="redirect" value="Redirect URL" />
                                <TextInput
                                    id="redirect"
                                    type="text"
                                    className="block mt-1 w-full dark:text-white"
                                    value={data.redirect}
                                    onChange={(e) => setData('redirect', e.target.value)}
                                    required
                                />
                                <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    The URL in your application where users will be redirected after authorization.
                                </p>
                                <InputError message={errors.redirect} className="mt-2" />
                            </div>

                            {/* Description */}
                            <div>
                                <InputLabel htmlFor="description" value="Description" />
                                <textarea
                                    id="description"
                                    rows="3"
                                    className="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                />
                                <InputError message={errors.description} className="mt-2" />
                            </div>

                            {/* Authentication Method */}
                            <div>
                                <InputLabel value="Authentication Method" />
                                <div className="mt-2 space-y-2">
                                    <label className="inline-flex items-center">
                                        <input
                                            type="checkbox"
                                            checked={data.use_auth_types.includes('email')}
                                            onChange={() => handleAuthTypeChange('email')}
                                            className="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        />
                                        <span className="ml-2">Email</span>
                                    </label>
                                    <label className="inline-flex items-center ml-6">
                                        <input
                                            type="checkbox"
                                            checked={data.use_auth_types.includes('phone')}
                                            onChange={() => handleAuthTypeChange('phone')}
                                            className="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        />
                                        <span className="ml-2">Phone</span>
                                    </label>
                                </div>
                                <InputError message={errors.use_auth_types} className="mt-2" />
                            </div>

                            {/* Authentication Type */}
                            <div>
                                <InputLabel value="Authentication Type" />
                                <div className="mt-2 space-y-2">
                                    <label className="inline-flex items-center">
                                        <input
                                            type="radio"
                                            name="pass_type"
                                            value="password"
                                            checked={data.pass_type === 'password'}
                                            onChange={(e) => setData('pass_type', e.target.value)}
                                            className="rounded-full border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        />
                                        <span className="ml-2">Password</span>
                                    </label>
                                    <label className="inline-flex items-center ml-6">
                                        <input
                                            type="radio"
                                            name="pass_type"
                                            value="otp"
                                            checked={data.pass_type === 'otp'}
                                            onChange={(e) => setData('pass_type', e.target.value)}
                                            className="rounded-full border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        />
                                        <span className="ml-2">OTP (One-Time Password)</span>
                                    </label>
                                </div>
                                <InputError message={errors.pass_type} className="mt-2" />
                            </div>

                            {/* Registration Enabled */}
                            <div>
                                <label className="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        checked={data.registration_enabled}
                                        onChange={(e) => setData('registration_enabled', e.target.checked)}
                                        className="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    />
                                    <span className="ml-2">Enable Registration</span>
                                </label>
                                <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Allow new users to register via this application.
                                </p>
                            </div>

                            {/* Client Credentials */}
                            <div className="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <h3 className="text-sm font-medium text-gray-700 dark:text-gray-300">Client Credentials</h3>
                                <div className="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {/* Client ID */}
                                    <div>
                                        <label className="block text-xs font-medium text-gray-500 dark:text-gray-400">Client ID</label>
                                        <div className="mt-1 p-2 bg-gray-50 dark:bg-gray-700 rounded relative flex justify-between items-center">
                                            <p className="text-sm break-all pr-8">{client.id}</p>
                                            <button
                                                type="button"
                                                onClick={() => copyToClipboard(client.id, 'client-id')}
                                                className="absolute right-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400"
                                                title="Copy to clipboard"
                                            >
                                                {copiedItems['client-id'] ? (
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

                                    {/* Client Secret */}
                                    <div>
                                        <label className="flex text-xs font-medium text-gray-500 dark:text-gray-400 justify-between items-center">
                                            <span>Client Secret</span>
                                            <button
                                                type="button"
                                                onClick={handleRegenerateSecret}
                                                className="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 transition-colors"
                                            >
                                                Regenerate Secret
                                            </button>
                                        </label>
                                        <div className="mt-1 p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                            <div className="flex justify-between items-center mb-1">
                                                <button
                                                    type="button"
                                                    onClick={() => setShowSecret(!showSecret)}
                                                    className="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                                >
                                                    {showSecret ? 'Hide' : 'Show'}
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => copyToClipboard(client.secret, 'client-secret')}
                                                    className="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400 ml-2"
                                                    title="Copy to clipboard"
                                                >
                                                    {copiedItems['client-secret'] ? (
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
                                            <div className="mt-1">
                                                {showSecret ? (
                                                    <p className="text-sm break-all">{client.secret}</p>
                                                ) : (
                                                    <p className="text-sm">••••••••••••••••••••••••••••••</p>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="flex items-center justify-end mt-6">
                            <Link
                                href={route('v2.clients.index')}
                                className="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4"
                            >
                                Cancel
                            </Link>
                            <PrimaryButton
                                className="bg-primary text-white px-4 py-2 rounded-lg shadow-md hover:bg-primary-600 transition-colors duration-300"
                                disabled={processing}
                            >
                                {processing ? 'Updating...' : 'Update Application'}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

