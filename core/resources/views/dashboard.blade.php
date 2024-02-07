<x-app-layout>
    <x-slot name="header">
        <!-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2> -->
    </x-slot>

    <div class="flex  md:flex-row flex-col md:justify-between items-center">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 sm:order-2 order-1">
            <div class="w-96 h-64 sm:w-[500px] sm:h-[272px] overflow-hidden relative border rounded-3xl bg-gradient-to-b from-primary-950  to-secondary-200">
                <div class="h-[370px] w-[550px] rounded-[50%] absolute -top-[210px] -right-[320px] bg-black opacity-[10%]"></div>
                <div class="h-[370px] w-[550px] rounded-[50%] absolute -bottom-[210px] -left-[320px] bg-black opacity-[10%]"></div>
            </div>
        </div>
    </div>
</x-app-layout>