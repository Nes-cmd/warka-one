<x-app-layout>
    <main class="max-w-[1480px] mx-auto md:mt-20 mb-20 mt-5 px-4 dark:text-gray-200 font-poppins">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Create New Application</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Register a new OAuth application to integrate with your account.</p>
        </div>

        <div class="bg-gray-100 dark:bg-slate-800 rounded-lg p-6 shadow-md">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Application Name')" />
                        <x-text-input id="name" class="block mt-1 w-full dark:text-white" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="redirect" :value="__('Redirect URL')" />
                        <x-text-input id="redirect" class="block mt-1 w-full dark:text-white" type="text" name="redirect" :value="old('redirect')" required placeholder="https://your-app.com/callback" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The URL in your application where users will be redirected after authorization.</p>
                        <x-input-error :messages="$errors->get('redirect')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label :value="__('Authentication Method')" />
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="use_auth_types[]" value="email" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ in_array('email', old('use_auth_types', [])) ? 'checked' : '' }}>
                                <span class="ml-2">Email</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="checkbox" name="use_auth_types[]" value="phone" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ in_array('phone', old('use_auth_types', [])) ? 'checked' : '' }}>
                                <span class="ml-2">Phone</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('use_auth_types')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label :value="__('Authentication Type')" />
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="pass_type" value="password" class="rounded-full border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ old('pass_type') == 'password' ? 'checked' : '' }}>
                                <span class="ml-2">Password</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="pass_type" value="otp" class="rounded-full border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ old('pass_type') == 'otp' ? 'checked' : '' }}>
                                <span class="ml-2">OTP (One-Time Password)</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('pass_type')" class="mt-2" />
                    </div>
                    
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="registration_enabled" value="1" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ old('registration_enabled') ? 'checked' : '' }}>
                            <span class="ml-2">Enable Registration</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Allow new users to register via this application.</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('clients.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
                        Cancel
                    </a>
                    <x-primary-button class="bg-primary text-white px-4 py-2 rounded-lg shadow-md hover:bg-primary-600 transition-colors duration-300">
                        Create Application
                    </x-primary-button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout> 