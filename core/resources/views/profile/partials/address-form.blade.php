<main class="mt-10">
    <div>
        <p class="text-base font-poppins font-bold">
            Update your Address
        </p>
        <p class="text-sm font-poppins font-light mt-1">You can Edit/update your Address information </p>
    </div>

    <section class="mt-10 max-w-4xl">
        <div class="grid md:grid-cols-2 grid-cols-1 md:gap-8 gap-4">
            <div class="flex flex-col gap-1">
                <label for="country">
                    Country
                </label>
                <input class=" border-b border-0" placeholder="Enter your Country" />
            </div>
            <div class="flex flex-col gap-1">
                <label for="City">
                    City
                </label>
                <input class=" border-b border-0" placeholder="Enter your City" />
            </div>
            <div class="flex flex-col gap-1">
                <label for="SubCity">
                    SubCity
                </label>
                <input class=" border-b border-0" placeholder="Enter your SubCity" />
            </div>
            <div class="flex flex-col gap-1">
                <label for="Woreda">
                    Woreda
                </label>
                <input class=" border-b border-0" placeholder="Enter your Woreda" />
            </div>
            <div class="flex flex-col gap-1">
                <label for="Street">
                    Street
                </label>
                <input class=" border-b border-0" placeholder="Enter your Street" />
            </div>
        </div>
        <div class="flex items-center gap-4 mt-10">
            <x-primary-button class="w-40 py-2 rounded-full flex justify-center">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-transition class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </section>
</main>