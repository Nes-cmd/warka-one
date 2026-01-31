import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout';
import InputLabel from '../Components/InputLabel';
import TextInput from '../Components/TextInput';
import InputError from '../Components/InputError';
import PrimaryButton from '../Components/PrimaryButton';

export default function CreateApp() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        redirect: '',
        description: '',
        use_auth_types: [],
        pass_type: 'password',
        registration_enabled: false,
    });

    const handleAuthTypeChange = (authType) => {
        const currentTypes = data.use_auth_types || [];
        if (currentTypes.includes(authType)) {
            setData('use_auth_types', currentTypes.filter(type => type !== authType));
        } else {
            setData('use_auth_types', [...currentTypes, authType]);
        }
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('v2.clients.store'), {
            preserveScroll: false,
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Create New Application" />

            <div className="md:mt-20 mb-20 mt-5 dark:text-gray-200 font-poppins">
                <div className="mb-6">
                    <h1 className="text-2xl font-bold">Create New Application</h1>
                    <p className="text-gray-600 dark:text-gray-400 mt-1">Register a new OAuth application to integrate with your account.</p>
                </div>

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
                                    placeholder="https://your-app.com/callback"
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
                                {processing ? 'Creating...' : 'Create Application'}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

