<x-app-layout>
    <main class="max-w-[1480px] mx-auto md:mt-20 mb-20 mt-5 px-4 dark:text-gray-200 font-poppins">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Edit Application</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Update your OAuth application settings.</p>
        </div>

        <div class="bg-gray-100 dark:bg-slate-800 rounded-lg p-6 shadow-md">
            <form action="{{ route('clients.update', $client->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Application Name')" />
                        <x-text-input id="name" class="block mt-1 w-full dark:text-white" type="text" name="name" :value="old('name', $client->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="redirect" :value="__('Redirect URL')" />
                        <x-text-input id="redirect" class="block mt-1 w-full dark:text-white" type="text" name="redirect" :value="old('redirect', $client->redirect)" required />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The URL in your application where users will be redirected after authorization.</p>
                        <x-input-error :messages="$errors->get('redirect')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $client->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label :value="__('Authentication Method')" />
                        <div class="mt-2 space-y-2">
                            @php
                                // Handle different data formats
                                $authTypes = $client->use_auth_types;
                                

                            @endphp

                            <label class="inline-flex items-center">
                                <input type="checkbox" name="use_auth_types[]" value="email" 
                                    class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                                    {{ in_array('email', $authTypes) ? 'checked' : '' }}>
                                <span class="ml-2">Email</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="checkbox" name="use_auth_types[]" value="phone" 
                                    class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                                    {{ in_array('phone', $authTypes) ? 'checked' : '' }}>
                                <span class="ml-2">Phone</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('use_auth_types')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label :value="__('Authentication Type')" />
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="pass_type" value="password" class="rounded-full border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ old('pass_type', $client->pass_type) == 'password' ? 'checked' : '' }}>
                                <span class="ml-2">Password</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="pass_type" value="otp" class="rounded-full border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ old('pass_type', $client->pass_type) == 'otp' ? 'checked' : '' }}>
                                <span class="ml-2">OTP (One-Time Password)</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('pass_type')" class="mt-2" />
                    </div>
                    
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="registration_enabled" value="1" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" {{ old('registration_enabled', $client->registration_enabled) ? 'checked' : '' }}>
                            <span class="ml-2">Enable Registration</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Allow new users to register via this application.</p>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Client Credentials</h3>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Client ID</label>
                                <div class="mt-1 p-2 bg-gray-50 dark:bg-gray-700 rounded relative flex justify-between items-center">
                                    <p class="text-sm break-all pr-8">{{ $client->id }}</p>
                                    <button 
                                        class="absolute right-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400" 
                                        x-data="{ copied: false }"
                                        x-on:click="
                                            navigator.clipboard.writeText('{{ $client->id }}');
                                            copied = true;
                                            setTimeout(() => copied = false, 2000);
                                        "
                                        type="button"
                                        title="Copy to clipboard"
                                    >
                                        <span x-show="!copied">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                                <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                            </svg>
                                        </span>
                                        <span x-show="copied" class="text-green-500 dark:text-green-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 flex justify-between items-center">
                                    <span>Client Secret</span>
                                    <a href="{{ route('clients.regenerate-secret', $client->id) }}" 
                                       class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 transition-colors"
                                       onclick="return confirm('Are you sure you want to revoke and regenerate this client secret? This action cannot be undone and any applications using this secret will need to be updated.')">
                                        Regenerate Secret
                                    </a>
                                </label>
                                <div class="mt-1 p-2 bg-gray-50 dark:bg-gray-700 rounded" x-data="{ show: false, copied: false }">
                                    <div class="flex justify-between items-center mb-1">
                                        <button 
                                            @click="show = !show" 
                                            type="button"
                                            class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                                            x-text="show ? 'Hide' : 'Show'"
                                        ></button>
                                        
                                        <button 
                                            class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400 ml-2" 
                                            @click="
                                                navigator.clipboard.writeText('{{ $client->secret }}');
                                                copied = true;
                                                setTimeout(() => copied = false, 2000);
                                            "
                                            type="button"
                                            title="Copy to clipboard"
                                        >
                                            <span x-show="!copied">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                                </svg>
                                            </span>
                                            <span x-show="copied" class="text-green-500 dark:text-green-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="mt-1">
                                        <p x-show="show" class="text-sm break-all animate-fade-in">{{ $client->secret }}</p>
                                        <p x-show="!show" class="text-sm">••••••••••••••••••••••••••••••</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('clients.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
                        Cancel
                    </a>
                    <x-primary-button>
                        Update Application
                    </x-primary-button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout> 