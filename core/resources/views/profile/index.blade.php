<x-app-layout>
    <main class="max-w-[1480px] mx-auto  md:mt-20 mb-20 mt-5 px-4 dark:text-gray-200   font-poppins">
        <section class="flex flex-col md:flex-row gap-5">
            <section class="md:w-1/2 w-full rounded-2xl bg-gray-100 dark:bg-slate-800 p-5 flex flex-col ">
                <div class="flex gap-1 self-end items-center">
                    <div class="w-2 h-2 rounded-full bg-red-600"></div>
                    <p class=" font-poppins font-light">
                        Unverified User
                    </p>
                </div>
                <div class=" w-20 lg:w-36 aspect-square rounded-full  ">
                    <img class="w-full h-full" src="{{ asset('assets/image/user.png') }}" alt="" srcset="">
                </div>
                <p class=" font-poppins font-bold mt-3 px-2 text-lg capitalize">
                    {{ Auth::user()->name }}
                </p>
                <p class=" font-poppins font-light mt-2 px-2">Female</p>
                <p class=" font-poppins font-light mt-2 px-2">bettykassaw@gmail.com</p>

                <p class=" font-poppins font-light mt-1 px-2">+251904189653</p>
                <a href="/profile-setting" class=" w-40 dark:text-gray-200 self-end flex gap-3 font-poppins rounded-lg text-primary font-semibold ">
                    <img class="dark:invert" src="{{ asset('assets/icons/vuesax/linear/magicpen.svg') }}" alt="" srcset="">
                    <span>
                        Edit Profile
                    </span>
                </a>
            </section>
            <section class="md:w-1/2 w-full flex flex-col ">
                <p class="text-lg font-poppins font-bold">
                    Subscribed services
                </p>
                <p class="text-sm font-poppins font-light mt-1">here you see your subscribed services </p>
                <div class="grid lg:grid-cols-2 md:grid-cols-1 gap-5 mt-5 grid-cols-4">
                    <div class="bg-gray-100 dark:bg-slate-800 w-full flex flex-col rounded-lg ">
                        <div class="md:w-6 md:h-6 h-5 w-5 m-1 self-end">
                            <img class="w-full h-full" src="{{ asset('assets/icons/vuesax/linear/tick-circle.svg') }}" alt="" srcset="">
                        </div>
                        <div class="md:px-5 px-1 flex flex-col -translate-y-3">
                            <div class="">
                                <img src="{{ asset('assets/icons/vuesax/linear/card-pos.svg') }}" alt="" srcset="">
                            </div>
                            <p class="text-secondary dark:text-gray-200 font-poppins font-bold md:text-base text-sm mt-1">
                                Ker Wallet
                            </p>
                            <p class="text-sm font-light font-poppins text-gray-600 dark:text-gray-400 md:block hidden">
                                Transfer, Receive & Enjoy
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-100 dark:bg-slate-800 w-full flex flex-col rounded-lg ">
                        <div class="md:w-6 md:h-6 h-5 w-5 m-1 self-end">
                            <img class="w-full h-full" src="{{ asset('assets/icons/vuesax/linear/close-circle.svg') }}" alt="" srcset="">
                        </div>
                        <div class="md:px-5 px-1 flex flex-col -translate-y-3">
                            <div class="">
                                <img class="dark:invert" src="{{ asset('assets/icons/vuesax/linear/buildings.svg') }}" alt="" srcset="">
                            </div>
                            <p class="text-primary dark:text-gray-200 font-poppins font-bold md:text-base text-sm mt-1 md:block hidden">
                                Apartment managment
                            </p>
                            <p class="text-primary dark:text-gray-200 font-poppins font-bold md:text-base text-sm mt-1 md:hidden block">
                                KAMS
                            </p>
                            <p class="text-sm font-light font-poppins text-gray-600 dark:text-gray-400 md:block hidden">
                                You build we manage
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <section class="flex flex-col md:flex-row gap-5 mt-10">
            <section class=" md:w-[50%] w-full  md:bg-gray-100 md:dark:bg-slate-800 bg-transparent rounded-2xl flex flex-col py-4 md:px-4">
                <p class="font-poppins font-bold text-lg mb-3">
                    Address
                </p>

                <hr>
                <table class="mt-5 table-fixed ">
                    <tbody>

                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">Country</td>
                            <td class="font-poppins font-light "> Ethiopia</td>
                        </tr>
                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">City</td>
                            <td class="font-poppins font-light "> Addis Ababa</td>
                        </tr>
                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">SubCity</td>
                            <td class="font-poppins font-light "> Bole</td>
                        </tr>
                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">Woreda</td>
                            <td class="font-poppins font-light "> 06</td>
                        </tr>
                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">Street</td>
                            <td class="font-poppins font-light "> mozambique st</td>
                        </tr>
                    </tbody>
                </table>


            </section>
            <section class="md:w-[50%] w-full   md:bg-gray-100 md:dark:bg-slate-800 bg-transparent rounded-2xl flex flex-col py-4 md:px-4">
                <p class="font-poppins font-bold text-lg mb-3">
                    Other Informations
                </p>
                <hr />
                <table class="mt-5 table-fixed ">
                    <tbody>

                        <tr class="border-b  dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">Fayda ID Number:</td>
                            <td class="font-poppins font-light "> 1234567890123</td>
                        </tr>
                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">Government ID:</td>
                            <td class="font-poppins font-light "> 1234567890123</td>
                        </tr>
                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">Birth Date:</td>
                            <td class="font-poppins font-light "> 19 Mar 1999</td>
                        </tr>
                        <tr class="border-b dark:border-slate-700">
                            <td class="font-poppins font-medium py-3">Account Created Date:</td>
                            <td class="font-poppins font-light "> 20 May 2021</td>
                        </tr>
                    </tbody>
                </table>

            </section>
        </section>
        <section class="md:w-[49%] w-full  md:bg-gray-100 md:dark:bg-slate-800 bg-transparent rounded-2xl flex flex-col py-4 md:px-4 mt-10">
            <p class="font-poppins font-bold text-lg ">
                Documents
            </p>
            <p class="text-sm font-poppins font-light mt-1 mb-3">
                Documents that make you a verified user </p>
            <hr>

            <div class="flex flex-col gap-2 mt-5">
                <p class="font-poppins font-medium">
                    Fayda ID
                </p>
                <div class="md:w-[380px] h-60 w-full">
                    <img src="" alt="" srcset="">
                </div>

            </div>
            <hr />
            <div class="flex flex-col gap-2 mt-2">
                <p class="font-poppins font-medium">
                    Government ID
                </p>
                <div class="md:w-[380px] h-60 w-full">
                    <img src="" alt="" srcset="">
                </div>

            </div>
            <hr />
        </section>
    </main>
</x-app-layout>