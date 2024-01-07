<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{authwith : '{{ $authflowData['authwith'] }}'}">
        @csrf

        <input type="hidden" name="country_id" value="{{ $authflowData['country']->id }}" id="">
        <!-- Name -->
        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone number -->
        <div class="mb-16" x-show="authwith == 'phone'">
            <x-input-label for="phone" :value="__('Phone')" />
            <div class="relative">
                <x-text-input id="phone" disabled class="block bg-gray-100 mt-1 w-[72%] absolute right-0 rounded-l-none py-2" value="{{ $authflowData['phone'] }}" type="tel" name="phone" required />

                <div class="absolute left-0 py-1 w-[30%]">
                    <div class="relative">
                        <!-- Button -->
                        <button x-ref="button" type="button" class="flex items-center gap-2 bg-white px-5 py-[9px] rounded-md shadow">
                            <img class="w-[20px]" src="{{ asset($authflowData['country']->flag_url) }}" alt="">
                            <span>({{ $authflowData['country']->dial_code}})</span>
                        </button>
                    </div>
                </div>

            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4 mb-3" x-show="authwith == 'email'">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" disabled class="block mt-1 w-full bg-gray-100" type="email" name="email" :value="old('email')" value="{{ $authflowData['email'] }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>