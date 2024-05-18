<section class="font-poppins max-w-2xl">
    <!-- <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-edit-password')" class=" flex flex-row gap-3 border-b pb-3 w-full">

        <div>
            <img src="{{ asset('assets/icons/vuesax/linear/key.svg') }}" alt="" srcset="">

        </div>
        <p class="font-medium">
            Change Password
        </p>


    </button> -->
    <!-- <x-modal name="confirm-user-edit-password" :show="$errors->isNotEmpty()" focusable> -->
    <form method="post" action="{{ route('password.update') }}" class="mt-2 space-y-6 p-6">
        @csrf
        @method('put')
        <div>
            <p class="text-base font-poppins font-bold">
                Update Personal information
            </p>
            <p class="text-sm font-poppins font-light mt-1">Edit or Fill out the information needed </p>
        </div>
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="w-40 py-2 rounded-full flex justify-center">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
    <!-- </x-modal> -->
</section>