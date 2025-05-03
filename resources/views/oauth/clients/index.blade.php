<x-app-layout>
    <main class="max-w-[1480px] mx-auto md:mt-20 mb-20 mt-5 px-4 dark:text-gray-200 font-poppins">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Applications</h1>
            <a href="{{ route('clients.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg shadow-md hover:bg-primary-600 transition-colors duration-300">
                Create New App
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-800 dark:text-green-100 dark:border-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid md:grid-cols-1 gap-6">
            @forelse($clients as $client)
                <div class="bg-gray-100 dark:bg-slate-800 rounded-lg p-6 shadow-md" x-data="{ showSecret: false }">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-semibold">{{ $client->name }}</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('clients.edit', $client->id) }}" class="text-primary dark:text-secondary-400 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $client->description ?: 'No description provided' }}</p>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Client ID</h3>
                            <div class="mt-1 bg-gray-50 dark:bg-gray-700 p-2 rounded relative flex justify-between items-center">
                                <p class="text-sm break-all pr-8">{{ $client->id }}</p>
                                <button 
                                    class="absolute right-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400" 
                                    x-data="{ copied: false }"
                                    x-on:click="
                                        navigator.clipboard.writeText('{{ $client->id }}');
                                        copied = true;
                                        setTimeout(() => copied = false, 2000);
                                    "
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
                        <div class="col-span-2 md:col-span-1">
                            <div class="flex justify-between items-center">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Client Secret</h3>
                                <div class="flex items-center">
                                    <button 
                                        @click="showSecret = !showSecret" 
                                        class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors mr-2"
                                        x-text="showSecret ? 'Hide' : 'Show'"
                                    ></button>
                                    
                                    <button 
                                        class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400" 
                                        x-data="{ copied: false }"
                                        x-on:click="
                                            navigator.clipboard.writeText('{{ $client->secret }}');
                                            copied = true;
                                            setTimeout(() => copied = false, 2000);
                                        "
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
                            <div class="mt-1 bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                <p x-show="showSecret" class="text-sm break-all animate-fade-in">{{ $client->secret }}</p>
                                <p x-show="!showSecret" class="text-sm">••••••••••••••••••••••••••••••</p>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Redirect URL</h3>
                            <div class="mt-1 lg:w-[50%] bg-gray-50 dark:bg-gray-700 p-2 rounded relative flex justify-between items-center">
                                <p class="text-sm break-all pr-8">{{ $client->redirect }}</p>
                                <button 
                                    class="absolute right-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-secondary-400" 
                                    x-data="{ copied: false }"
                                    x-on:click="
                                        navigator.clipboard.writeText('{{ $client->redirect }}');
                                        copied = true;
                                        setTimeout(() => copied = false, 2000);
                                    "
                                    title="Copy to clipboard">
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
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Auth Methods</h3>
                            <p class="mt-1 text-sm">
                                {{ is_string($client->use_auth_types) ? implode(', ', json_decode($client->use_auth_types, true)) : implode(', ', $client->use_auth_types) }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Auth Type</h3>
                            <p class="mt-1 text-sm">{{ ucfirst($client->pass_type) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Registration</h3>
                            <p class="mt-1 text-sm">{{ $client->registration_enabled ? 'Enabled' : 'Disabled' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Created on</h3>
                            <p class="mt-1 text-sm">{{ $client->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-100 dark:bg-slate-800 rounded-lg p-6 text-center">
                    <p>You haven't created any applications yet.</p>
                    <a href="{{ route('clients.create') }}" class="mt-2 inline-block text-primary dark:text-secondary-400 hover:underline">
                        Create your first app
                    </a>
                </div>
            @endforelse
        </div>
    </main>
</x-app-layout> 