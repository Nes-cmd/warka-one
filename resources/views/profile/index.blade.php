<x-app-layout>
    <main class="max-w-[1480px] mx-auto md:mt-20 mb-20 mt-5 px-4 dark:text-gray-200 font-poppins">
        <section class="flex flex-col md:flex-row gap-5">
            <!-- Profile Card -->
            <div class="w-full md:w-1/3 lg:w-1/4">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="h-24 w-24 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700">
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary">
                                        <span class="text-2xl font-bold">{{ substr(auth()->user()->name ?? auth()->user()->email, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h2 class="mt-4 text-xl font-semibold">{{ auth()->user()->name }}</h2>
                        <div class="mt-2">
                            @if(auth()->user()->email)
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <span>{{ auth()->user()->email }}</span>
                                @if(auth()->user()->email_verified_at)
                                    <span class="ml-2 text-green-500" title="Verified">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            @endif
                            
                            @if(auth()->user()->phone)
                                <div class="flex items-center text-gray-600 dark:text-gray-400 mt-1">
                                    <span>{{ auth()->user()->country?->dial_code }}{{ auth()->user()->phone }}</span>
                                    @if(auth()->user()->phone_verified_at)
                                        <span class="ml-2 text-green-500" title="Verified">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-6 w-full">
                            <a href="{{ route('profile.update-profile') }}" 
                               class="block w-full text-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-600 transition-colors">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Additional User Info Card -->
                @if(auth()->user()->detail)
                <div class="mt-5 bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                    <div class="space-y-3">
                        @if(auth()->user()->detail->gender)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</h4>
                                <p>{{ auth()->user()->detail->gender }}</p>
                            </div>
                        @endif
                        
                        @if(auth()->user()->detail->birth_date)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Date</h4>
                                <p>{{ \Carbon\Carbon::parse(auth()->user()->detail->birth_date)->format('F j, Y') }}</p>
                            </div>
                        @endif
                        
                        @if(auth()->user()->detail->address)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</h4>
                                <p>{{ auth()->user()->detail->address }}</p>
                            </div>
                        @endif
                        
                        @if(auth()->user()->detail->city)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">City</h4>
                                <p>{{ auth()->user()->detail->city }}</p>
                            </div>
                        @endif
                        
                        @if(auth()->user()->detail->country_id && auth()->user()->detail->country)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Country</h4>
                                <p>{{ auth()->user()->detail->country->name }}</p>
                            </div>
                        @endif
                        
                        @if(auth()->user()->detail->created_at)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</h4>
                                <p>{{ auth()->user()->created_at->format('F j, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Main Content Area -->
            <div class="w-full md:w-2/3 lg:w-3/4 mt-5 md:mt-0">
                <!-- Active Sessions -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mb-5">
                    <h3 class="text-lg font-semibold mb-4">Active Sessions</h3>
                    
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Device</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Activity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse(request()->session()->get('sessions', []) as $session)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                                                    @if(Str::contains(strtolower($session['user_agent'] ?? ''), 'chrome'))
                                                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm-1 4a1 1 0 0 1 2 0v4a1 1 0 0 1-2 0V8z"/></svg>
                                                    @elseif(Str::contains(strtolower($session['user_agent'] ?? ''), 'firefox'))
                                                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm-1 4a1 1 0 0 1 2 0v4a1 1 0 0 1-2 0V8z"/></svg>
                                                    @elseif(Str::contains(strtolower($session['user_agent'] ?? ''), 'safari'))
                                                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm-1 4a1 1 0 0 1 2 0v4a1 1 0 0 1-2 0V8z"/></svg>
                                                    @else
                                                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm-1 4a1 1 0 0 1 2 0v4a1 1 0 0 1-2 0V8z"/></svg>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $session['browser'] ?? 'Unknown Browser' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $session['platform'] ?? 'Unknown Device' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $session['ip_address'] ?? 'Unknown' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $session['location'] ?? 'Unknown location' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $session['last_active'] ? \Carbon\Carbon::parse($session['last_active'])->diffForHumans() : 'Unknown' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($session['is_current_device'] ?? false)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                    Current Session
                                                </span>
                                            @else
                                                <form action="{{ route('profile.revoke-session') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="session_id" value="{{ $session['id'] }}">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Revoke
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No active sessions found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Connected Applications -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Connected Applications</h3>
                        <a href="{{ route('clients.index') }}" class="text-sm text-primary dark:text-secondary-400 hover:underline">
                            My Apps
                        </a>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        These are applications you've authorized to access your account. You can revoke access at any time.
                    </p>
                    
                    <div class="space-y-4">
                        @forelse($authorizedApps as $token)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-primary/10 dark:bg-primary/20 p-3 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $token->client->name ?? 'Unknown Application' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Last used: {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Authorized: {{ $token->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('profile.revoke-token') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token_id" value="{{ $token->id }}">
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 text-xs font-medium rounded-full hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                        Revoke
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="py-4 text-center text-gray-500 dark:text-gray-400">
                                <p>You haven't connected any applications yet.</p>
                                <p class="mt-1 text-sm">When you authorize applications to access your account, they will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination -->
                    @if($authorizedApps->hasPages())
                        <div class="mt-6">
                            {{ $authorizedApps->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>