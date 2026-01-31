import React, { useState, useEffect } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout';
import InputLabel from '../Components/InputLabel';
import TextInput from '../Components/TextInput';
import InputError from '../Components/InputError';
import PrimaryButton from '../Components/PrimaryButton';
import DangerButton from '../Components/DangerButton';
import SecondaryButton from '../Components/SecondaryButton';

export default function EditProfile({ user, genders, countries, status, flash_error }) {
    const [activeTab, setActiveTab] = useState('profile');
    const [showDeleteModal, setShowDeleteModal] = useState(false);

    const profileForm = useForm({
        name: user.name || '',
        email: user.email || '',
        phone: user.phone || '',
        country_id: user.country_id || '',
        gender: user.user_detail?.gender || '',
        birth_date: user.user_detail?.birth_date || '',
    });

    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const deleteForm = useForm({
        password: '',
    });

    useEffect(() => {
        // Show password tab if there are password errors
        if (passwordForm.errors && Object.keys(passwordForm.errors).length > 0) {
            setActiveTab('password');
        }
        // Show danger tab if there are deletion errors
        if (deleteForm.errors && Object.keys(deleteForm.errors).length > 0) {
            setActiveTab('danger');
            setShowDeleteModal(true);
        }
    }, []);

    const submitProfile = (e) => {
        e.preventDefault();
        profileForm.patch(route('v2.profile.update'), {
            preserveScroll: false,
            onSuccess: () => {
                // Redirect handled by backend
            },
            onError: (errors) => {
                console.log('Validation errors:', errors);
                // Errors will be displayed automatically by InputError components
            },
        });
    };

    const submitPassword = (e) => {
        e.preventDefault();
        passwordForm.put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => {
                passwordForm.reset();
            },
            onError: () => {
                setActiveTab('password');
            },
        });
    };

    const submitDelete = (e) => {
        e.preventDefault();
        deleteForm.delete(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => {
                router.visit(route('v2.login'));
            },
        });
    };

    const getTabButtonClass = (tabName) => {
        const baseClass = "w-full text-left px-4 py-3 border-l-4 transition-colors";
        if (activeTab === tabName) {
            if (tabName === 'danger') {
                return `${baseClass} border-red-500 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 font-medium`;
            }
            return `${baseClass} border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 font-medium`;
        }
        if (tabName === 'danger') {
            return `${baseClass} border-transparent text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10`;
        }
        return `${baseClass} border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/10`;
    };

    const getTabContentClass = (tabName) => {
        return activeTab === tabName ? "bg-white dark:bg-slate-800 rounded-lg p-6 shadow-md mb-6" : "hidden";
    };

    return (
        <AuthenticatedLayout>
            <Head title="Edit Profile" />

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

                {/* General Form Errors */}
                {profileForm.errors && Object.keys(profileForm.errors).length > 0 && (
                    <div className="mb-4 font-medium text-sm text-red-600 dark:text-red-400 p-4 bg-red-100 dark:bg-red-900/20 rounded-lg">
                        <p className="font-semibold mb-2">Please fix the following errors:</p>
                        <ul className="list-disc list-inside">
                            {Object.entries(profileForm.errors).map(([key, error]) => (
                                <li key={key}>{Array.isArray(error) ? error[0] : error}</li>
                            ))}
                        </ul>
                    </div>
                )}

                <div className="flex flex-col md:flex-row gap-6">
                    {/* Sidebar Navigation */}
                    <div className="w-full md:w-1/5">
                        <div className="bg-white dark:bg-slate-800 rounded-lg shadow-md">
                            <div className="flex flex-col">
                                <button
                                    onClick={() => setActiveTab('profile')}
                                    className={getTabButtonClass('profile')}
                                >
                                    Profile Information
                                </button>
                                <button
                                    onClick={() => setActiveTab('password')}
                                    className={getTabButtonClass('password')}
                                >
                                    Update Password
                                </button>
                                <button
                                    onClick={() => setActiveTab('kyc')}
                                    className={getTabButtonClass('kyc')}
                                >
                                    KYC Verification
                                </button>
                                <button
                                    onClick={() => setActiveTab('danger')}
                                    className={getTabButtonClass('danger')}
                                >
                                    Danger Zone
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* Tab Content Area */}
                    <div className="w-full md:w-4/5">
                        {/* Profile Information Tab */}
                        <div className={getTabContentClass('profile')}>
                            <h2 className="text-xl font-semibold mb-4">Profile Information</h2>
                            <p className="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                Update your account's profile information and email address.
                            </p>

                            <form onSubmit={submitProfile}>
                                {/* Name */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="name" value="Name" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        className="block mt-1 w-full"
                                        value={profileForm.data.name}
                                        onChange={(e) => profileForm.setData('name', e.target.value)}
                                        required
                                        autoFocus
                                    />
                                    <InputError message={profileForm.errors.name} className="mt-2" />
                                </div>

                                {/* Email */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="email" value="Email" />
                                    <TextInput
                                        id="email"
                                        type="email"
                                        className="block mt-1 w-full"
                                        value={profileForm.data.email}
                                        onChange={(e) => profileForm.setData('email', e.target.value)}
                                        required
                                    />
                                    <InputError message={profileForm.errors.email} className="mt-2" />
                                </div>

                                {/* Phone */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="phone" value="Phone Number" />
                                    <div className="flex mt-1">
                                        <select
                                            name="country_id"
                                            value={profileForm.data.country_id || ''}
                                            onChange={(e) => profileForm.setData('country_id', e.target.value || null)}
                                            className="rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 shadow-sm"
                                        >
                                            <option value="">Select Country</option>
                                            {countries && countries.length > 0 ? (
                                                countries.map((country) => (
                                                    <option key={country.id} value={country.id}>
                                                        {country.dial_code} - {country.name}
                                                    </option>
                                                ))
                                            ) : (
                                                <option value="">No countries available</option>
                                            )}
                                        </select>
                                        <TextInput
                                            id="phone"
                                            type="text"
                                            className={`block w-full rounded-l-none ${!user.phone_verified_at && user.phone ? 'rounded-r-none border-r-0' : ''}`}
                                            value={profileForm.data.phone}
                                            onChange={(e) => {
                                                profileForm.setData('phone', e.target.value);
                                                // Clear country_id if phone is cleared
                                                if (!e.target.value.trim()) {
                                                    profileForm.setData('country_id', null);
                                                }
                                            }}
                                            placeholder="Enter phone number"
                                        />
                                    </div>
                                    <InputError message={profileForm.errors.phone} className="mt-2" />
                                    <InputError message={profileForm.errors.country_id} className="mt-2" />
                                </div>

                                {/* Additional User Details */}
                                <div className="pt-4 mt-6 flex gap-6">
                                    {/* Gender */}
                                    <div className="mb-4 flex-1">
                                        <InputLabel htmlFor="gender" value="Gender" />
                                        <select
                                            id="gender"
                                            name="gender"
                                            value={profileForm.data.gender}
                                            onChange={(e) => profileForm.setData('gender', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                                        >
                                            <option value="">Select Gender</option>
                                            {genders.map((gender) => (
                                                <option key={gender} value={gender}>
                                                    {gender}
                                                </option>
                                            ))}
                                        </select>
                                        <InputError message={profileForm.errors.gender} className="mt-2" />
                                    </div>

                                    {/* Date of Birth */}
                                    <div className="mb-4 flex-1">
                                        <InputLabel htmlFor="birth_date" value="Date of Birth" />
                                        <TextInput
                                            id="birth_date"
                                            type="date"
                                            className="block mt-1 w-full"
                                            value={profileForm.data.birth_date}
                                            onChange={(e) => profileForm.setData('birth_date', e.target.value)}
                                        />
                                        <InputError message={profileForm.errors.birth_date} className="mt-2" />
                                    </div>
                                </div>

                                <div className="flex items-center justify-end mt-6">
                                    <PrimaryButton
                                        className="bg-primary-600 hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 px-6 py-2.5 text-white font-medium transition-all duration-200"
                                        disabled={profileForm.processing}
                                    >
                                        {profileForm.processing ? 'Saving...' : 'Save Profile'}
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        {/* Password Update Tab */}
                        <div className={getTabContentClass('password')}>
                            <h2 className="text-xl font-semibold mb-4">Update Password</h2>
                            <p className="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                Ensure your account is using a long, random password to stay secure.
                            </p>

                            <form onSubmit={submitPassword}>
                                {/* Current Password */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="current_password" value="Current Password" />
                                    <TextInput
                                        id="current_password"
                                        type="password"
                                        className="mt-1 block w-full"
                                        value={passwordForm.data.current_password}
                                        onChange={(e) => passwordForm.setData('current_password', e.target.value)}
                                        autoComplete="current-password"
                                    />
                                    <InputError message={passwordForm.errors.current_password} className="mt-2" />
                                </div>

                                {/* New Password */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="password" value="New Password" />
                                    <TextInput
                                        id="password"
                                        type="password"
                                        className="mt-1 block w-full"
                                        value={passwordForm.data.password}
                                        onChange={(e) => passwordForm.setData('password', e.target.value)}
                                        autoComplete="new-password"
                                    />
                                    <InputError message={passwordForm.errors.password} className="mt-2" />
                                </div>

                                {/* Confirm Password */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="password_confirmation" value="Confirm Password" />
                                    <TextInput
                                        id="password_confirmation"
                                        type="password"
                                        className="mt-1 block w-full"
                                        value={passwordForm.data.password_confirmation}
                                        onChange={(e) => passwordForm.setData('password_confirmation', e.target.value)}
                                        autoComplete="new-password"
                                    />
                                    <InputError message={passwordForm.errors.password_confirmation} className="mt-2" />
                                </div>

                                <div className="flex items-center justify-end mt-6">
                                    <PrimaryButton
                                        className="bg-primary-600 hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 px-6 py-2.5 text-white font-medium transition-all duration-200"
                                        disabled={passwordForm.processing}
                                    >
                                        {passwordForm.processing ? 'Updating...' : 'Update Password'}
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        {/* KYC Verification Tab */}
                        <div className={activeTab === 'kyc' ? "bg-white dark:bg-slate-800 rounded-lg shadow-md p-6" : "hidden"}>
                            <div className="text-center py-8">
                                <div className="mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-16 w-16 mx-auto text-secondary-500 dark:text-secondary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Advanced Account Verification</h2>
                                <p className="text-lg text-slate-600 dark:text-slate-400 mb-6">
                                    Our KYC verification system is coming soon. This feature will allow you to verify your identity for enhanced security and access to premium features.
                                </p>
                                <div className="inline-flex items-center px-4 py-2 bg-secondary-100 dark:bg-secondary-900/30 text-secondary-800 dark:text-secondary-300 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Coming Soon
                                </div>
                            </div>
                        </div>

                        {/* Danger Zone Tab */}
                        <div className={getTabContentClass('danger')}>
                            <h2 className="text-xl font-semibold mb-4 text-red-600 dark:text-red-400">Danger Zone</h2>
                            <div className="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 mb-6 border border-red-200 dark:border-red-800">
                                <h3 className="text-lg font-medium text-red-800 dark:text-red-300 mb-2">Delete Account</h3>
                                <p className="text-sm text-red-700 dark:text-red-400 mb-4">
                                    Once your account is deleted, all of its resources and data will be permanently deleted. Before
                                    deleting your account, please download any data or information that you wish to retain.
                                </p>
                                <div className="flex justify-end">
                                    <DangerButton
                                        onClick={() => setShowDeleteModal(true)}
                                        className="bg-red-600 hover:bg-red-700 focus:ring-red-500"
                                    >
                                        Delete Account
                                    </DangerButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Delete User Confirmation Modal */}
                {showDeleteModal && (
                    <div className="fixed inset-0 z-50 overflow-y-auto">
                        <div className="flex items-center justify-center min-h-screen px-4">
                            <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onClick={() => setShowDeleteModal(false)}></div>
                            <div className="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Are you sure you want to delete your account?
                                </h2>
                                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                                </p>
                                <form onSubmit={submitDelete}>
                                    <div className="mt-6">
                                        <InputLabel htmlFor="delete_password" value="Password" className="sr-only" />
                                        <TextInput
                                            id="delete_password"
                                            type="password"
                                            className="mt-1 block w-3/4"
                                            placeholder="Password"
                                            value={deleteForm.data.password}
                                            onChange={(e) => deleteForm.setData('password', e.target.value)}
                                        />
                                        <InputError message={deleteForm.errors.password} className="mt-2" />
                                    </div>
                                    <div className="mt-6 flex justify-end">
                                        <SecondaryButton
                                            type="button"
                                            onClick={() => setShowDeleteModal(false)}
                                        >
                                            Cancel
                                        </SecondaryButton>
                                        <DangerButton
                                            type="submit"
                                            className="ml-3"
                                            disabled={deleteForm.processing}
                                        >
                                            {deleteForm.processing ? 'Deleting...' : 'Delete Account'}
                                        </DangerButton>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}

