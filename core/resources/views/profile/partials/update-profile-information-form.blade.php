<section class="font-poppins mt-10">
<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
      filter: invert(0) grayscale(0) brightness(1);
      transition: filter 0.3s ease;
    }

    .dark input[type="date"]::-webkit-calendar-picker-indicator {
      filter: invert(1) grayscale(100%) brightness(200%);
    }
  </style>
    <!-- <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-edit-profile')" class="bg-transparent  font-poppins w-full">

        <div class=" flex flex-row gap-3 border-b pb-3">

            <div>
                <img src="{{ asset('assets/icons/vuesax/linear/user.svg') }}" alt="" srcset="">

            </div>
            <p class="font-medium  font-poppins">
                Update profile
            </p>


        </div>
    </button> -->

    <!-- <x-modal name="confirm-user-edit-profile" :show="$errors->isNotEmpty()" focusable> -->
    <div class="max-w-2xl ">
        <div>
            <p class="text-base font-poppins font-bold">
                Update Personal information
            </p>
            <p class="text-sm font-poppins font-light mt-1">Edit or Fill out the information needed </p>
        </div>
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-10 space-y-6">
            @csrf
            @method('patch')
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="name" :value="__('Gender')" />
                <select required name="gender" class="w-full border-gray-300 dark:border-slate-700 bg-transparent focus:border-indigo-500 dark:focus:border-slate-500 focus:ring-indigo-500 dark:focus:ring-slate-500 rounded-md shadow-sm">
                    <option value="">Choose</option>
                    @foreach($genders as $gender)
                        <option class="rounded bg-primary-300" value="{{ $gender->value }}" {{$user->userDetail->gender == $gender?'selected':''}}>{{ $gender->name }}</option>
                    @endforeach
                </select> 
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div> 

            <div>
                <x-input-label for="name" :value="__('Birth date')" /> 
                <x-text-input name="birth_date" class="w-full" :value="old('birth_date', $user->userDetail->birth_date->format('Y-m-d') )" type="date" />
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <div> 
                <x-input-label for="name" :value="__('Phone')" />
                <x-text-input style="{{$user->phone?'background-color:#94a3b8':''}}" disabled="{{$user->phone?1:0}}" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" required />
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input style="{{$user->email?'background-color:#94a3b8':''}}" disabled="{{$user->email?1:0}}" id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-500">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-500 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                    @endif  
                </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button class="w-40 py-2 rounded-full flex justify-center">{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
    <!-- </x-modal> -->


</section>