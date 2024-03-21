<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{authwith : '{{ $authflowData['authwith'] }}'}">
        @csrf

        <input type="hidden" name="country_id" value="{{ $authflowData['country']->id }}" id="">
        <!-- Name -->
        
        <!-- Phone number -->
        @if( $authflowData['authwith'] == 'phone')
        <div class="mb-6" x-show="authwith == 'phone'">
            <x-input-label for="phone" :value="__('Phone')" />
            <div class="flex">
                    <div>
                        <!-- Button -->
                        <button x-ref="button" type="button" class="flex items-center bg-gray-100 px-2 py-2.5 rounded-md shadow">
                            <img class="w-[20px]" src="{{ asset($authflowData['country']->flag_url) }}" alt="">
                            <span>({{ $authflowData['country']->dial_code}})</span>
                        </button>
                    </div>
                <x-text-input id="phone" disabled class="block bg-gray-100 py-2 w-[90%]" value="{{ $authflowData['phone'] }}" type="tel" name="phone" required />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        @endif

        @if( $authflowData['authwith'] == 'email')
        <!-- Email Address -->
        <div class="mt-4 mb-6" x-show="authwith == 'email'">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" disabled class="block mt-1 w-full bg-gray-100" type="email" name="email" :value="old('email')" value="{{ $authflowData['email'] }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        @endif

        <div class="mb-3 mt-3">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
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
        <div class="w-full flex justify-center mt-2">
            <x-primary-button class="my-3 xl:w-2/3 w-full py-2 flex justify-center items-center text-xl rounded-full">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        <div class="flex items-center justify-center mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>


        </div>
    </form>
</x-guest-layout>