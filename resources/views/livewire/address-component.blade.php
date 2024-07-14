<section class="mt-10 max-w-4xl">
    <div class="grid md:grid-cols-2 grid-cols-1 md:gap-8 gap-4">
        <div class="flex flex-col gap-1">
            <label for="country">
                Country
            </label>
            <select wire:model="country_id" id="" wire:change="loadCites" required class="bg-transparent">
                <option value="">Choose</option>
                @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
            @error('country_id')<span class="text-danger-500">{{ $message }}</span>@enderror

        </div>
        <div class="flex flex-col gap-1">
            <label for="City">
                City
            </label>
            <select wire:model="city_id" id="" wire:change="loadSubCites" required class="bg-transparent">
                <option value="">Choose</option>
                @foreach($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
            @error('city_id')<span class="text-danger-500">{{ $message }}</span>@enderror
        </div>
        <div class="flex flex-col gap-1">
            <label for="SubCity">
                Sub City(optional)
            </label>
            <select wire:model="sub_city_id"  class="bg-transparent">
                <option value="">Choose</option>
                @foreach($sub_cities as $subcity)
                <option value="{{ $subcity->id }}">{{ $subcity->name }}</option>
                @endforeach
            </select>
            @error('sub_city_id')<span class="text-danger-500">{{ $message }}</span>@enderror
        </div>
        
        <div class="flex flex-col gap-1">
            <label for="Street">
                Specific location
            </label>
            <input wire:model="specific_location" class=" border-b border-0 bg-transparent" placeholder="Enter your specific location" required />
            @error('specific_location')<span class="text-danger-500">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="flex items-center gap-4 mt-10">
        <x-primary-button wire:click="save" class="w-40 py-2 rounded-full flex justify-center">{{ __('Save') }}</x-primary-button>

        @if($updated)
        <p x-transition class="text-sm text-gray-600">{{ __('Saved.') }}</p>
        @endif
    </div>
</section>