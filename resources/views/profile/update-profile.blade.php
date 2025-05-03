<x-app-layout>
    <main class="max-w-[1480px] mx-auto md:mt-20 mb-20 mt-5 px-4 dark:text-gray-200 font-poppins">
        @if(session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 p-4 bg-green-100 dark:bg-green-900/20 rounded-lg">
            {{ session('status') }}
        </div>
        @endif

        @if(session('flash_error'))
        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400 p-4 bg-red-100 dark:bg-red-900/20 rounded-lg">
            {{ session('flash_error') }}
        </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar Navigation -->
            <div class="w-full md:w-1/5">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md">
                    <div class="flex flex-col">
                        <button
                            id="profile-tab-btn"
                            class="w-full text-left px-4 py-3 border-l-4 border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 font-medium"
                            onclick="showTab('profile')">
                            Profile Information
                        </button>
                        <button
                            id="password-tab-btn"
                            class="w-full text-left px-4 py-3 border-l-4 border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/10"
                            onclick="showTab('password')">
                            Update Password
                        </button>
                        <button
                            id="kyc-tab-btn"
                            class="w-full text-left px-4 py-3 border-l-4 border-transparent text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/10"
                            onclick="showTab('kyc')">
                            KYC Verification
                        </button>
                        <button
                            id="danger-tab-btn"
                            class="w-full text-left px-4 py-3 border-l-4 border-transparent text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 mt-4"
                            onclick="showTab('danger')">
                            Danger Zone
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab Content Area -->
            <div class="w-full md:w-4/5">
                <!-- Profile Information Tab -->
                <div id="profile-tab" class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-md mb-6">
                    <h2 class="text-xl font-semibold mb-4">Profile Information</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Update your account's profile information and email address.
                    </p>

                    <form id="profile-form" method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <div class="flex mt-1">
                                <select
                                    name="country_id"
                                    
                                    class="rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 shadow-sm"
                                   >
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->dial_code }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-text-input
                                    id="phone"
                                    name="phone"
                                    :value="old('phone', $user->phone)"
                                    class="block w-full rounded-l-none {{ !$user->phone_verified_at && $user->phone ? 'rounded-r-none border-r-0' : '' }}"
                                    type="text"
                                    />

                            </div>
                        </div>

                        <!-- Additional User Details -->
                        <div class="pt-4 mt-6 flex gap-6">
                            

                            <!-- Gender -->
                            <div class="mb-4">
                                <x-input-label for="gender" :value="__('Gender')" />
                                <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                    <option value="">Select Gender</option>
                                    @foreach($genders as $gender)
                                    <option value="{{ $gender->value }}" {{ old('gender', $user->userDetail->gender?->value ?? '') == $gender->value ? 'selected' : '' }}>
                                        {{ $gender->value }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>

                            <!-- Date of Birth -->
                           
                            <div class="mb-4">
                                <x-input-label for="birth_date" :value="__('Date of Birth')" />
                               <x-text-input 
                                        id="birth_date" 
                                        class="block mt-1 w-full" 
                                        type="date" 
                                        name="birth_date" 
                                        :value="$user->userDetail->birth_date?->format('Y-m-d') ?? ''" 
                                    />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>

                            
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="bg-primary-600 hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 px-6 py-2.5 text-white font-medium transition-all duration-200">
                                {{ __('Save Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Password Update Tab (Hidden by default) -->
                <div id="password-tab" class="hidden bg-white dark:bg-slate-800 rounded-lg p-6 shadow-md mb-6">
                    <h2 class="text-xl font-semibold mb-4">Update Password</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Ensure your account is using a long, random password to stay secure.
                    </p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <!-- Current Password -->
                        <div class="mb-4">
                            <x-input-label for="current_password" :value="__('Current Password')" />
                            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('New Password')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="bg-primary-600 hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 px-6 py-2.5 text-white font-medium transition-all duration-200">
                                {{ __('Update Password') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- KYC Verification Tab (Hidden by default) -->
                <div id="kyc-tab" class="hidden">
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-secondary-500 dark:text-secondary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Advanced Account Verification</h2>
                            <p class="text-lg text-slate-600 dark:text-slate-400 mb-6">
                                Our KYC verification system is coming soon. This feature will allow you to verify your identity for enhanced security and access to premium features.
                            </p>
                            <div class="inline-flex items-center px-4 py-2 bg-secondary-100 dark:bg-secondary-900/30 text-secondary-800 dark:text-secondary-300 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone Tab (Hidden by default) -->
                <div id="danger-tab" class="hidden bg-white dark:bg-slate-800 rounded-lg p-6 shadow-md mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-red-600 dark:text-red-400">Danger Zone</h2>
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 mb-6 border border-red-200 dark:border-red-800">
                        <h3 class="text-lg font-medium text-red-800 dark:text-red-300 mb-2">Delete Account</h3>
                        <p class="text-sm text-red-700 dark:text-red-400 mb-4">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Before
                            deleting your account, please download any data or information that you wish to retain.
                        </p>
                        <div class="flex justify-end">
                            <x-danger-button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                class="bg-red-600 hover:bg-red-700 focus:ring-red-500">{{ __('Delete Account') }}</x-danger-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Password') }}" />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

        <script>
            // Tab switching functionality
            function showTab(tabName) {
                // Hide all tabs
                document.getElementById('profile-tab').classList.add('hidden');
                document.getElementById('password-tab').classList.add('hidden');
                document.getElementById('kyc-tab').classList.add('hidden');
                document.getElementById('danger-tab').classList.add('hidden');

                // Reset all tab button styles
                document.getElementById('profile-tab-btn').classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20', 'text-primary-700', 'dark:text-primary-300');
                document.getElementById('password-tab-btn').classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20', 'text-primary-700', 'dark:text-primary-300');
                document.getElementById('kyc-tab-btn').classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20', 'text-primary-700', 'dark:text-primary-300');
                document.getElementById('danger-tab-btn').classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-300');

                // Set default inactive styles
                document.getElementById('profile-tab-btn').classList.add('border-transparent', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700/10');
                document.getElementById('password-tab-btn').classList.add('border-transparent', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700/10');
                document.getElementById('kyc-tab-btn').classList.add('border-transparent', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700/10');
                document.getElementById('danger-tab-btn').classList.add('border-transparent', 'text-red-600', 'dark:text-red-400', 'hover:bg-red-50', 'dark:hover:bg-red-900/10');

                // Show selected tab and highlight its button
                document.getElementById(tabName + '-tab').classList.remove('hidden');

                if (tabName === 'profile' || tabName === 'password' || tabName === 'kyc') {
                    // Apply active styles for regular tabs
                    document.getElementById(tabName + '-tab-btn').classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20', 'text-primary-700', 'dark:text-primary-300');
                    document.getElementById(tabName + '-tab-btn').classList.remove('border-transparent', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-50', 'dark:hover:bg-gray-700/10');
                } else if (tabName === 'danger') {
                    // Apply active styles for danger tab
                    document.getElementById('danger-tab-btn').classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-300');
                    document.getElementById('danger-tab-btn').classList.remove('border-transparent', 'hover:bg-red-50', 'dark:hover:bg-red-900/10');
                }
            }

            // Initialize with error handling
            document.addEventListener('DOMContentLoaded', function() {
                // If we have password update errors, show that tab
                if (@json($errors->updatePassword->isNotEmpty())) {
                    showTab('password');
                }
                // If we have deletion errors, show the danger tab
                else if (@json($errors->userDeletion->isNotEmpty())) {
                    showTab('danger');
                }
                // Otherwise show the profile tab (default)
                else {
                    showTab('profile');
                }
            });
        </script>
    </main>
</x-app-layout>