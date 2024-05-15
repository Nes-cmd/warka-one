<!-- <div>
        Hello {{ Auth::user()->name }}
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                   
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div> -->

<!-- <section class="max-w-6xl mx-auto md:my-5 ">
        <div class="w-full h-40 border rounded-md bg-gradient-to-r from-gray-200 to-primary relative">
            <div id="initial2" class="bg-primary w-20 h-20 rounded-full absolute -bottom-12 left-10 flex justify-center items-center text-white font-bold text-4xl">

            </div>
            <div class="absolute -bottom-9 left-32 capitalize text-xl font-semibold">
                {{ Auth::user()->name }}
            </div>
        </div>

        <div class="my-20 max-w-4xl mx-auto">
            <ul class="w-full flex justify-between gap-6 mb-10 font-bold text-lg border-b px-4 ">
                <button onclick="openTab(event, 'tab1')" class="tablink border-b  text-primary  px-6 py-3 transition-all ease-in-out duration-300">Profile information</button>
                <button onclick="openTab(event, 'tab2')" class="tablink border-b  text-primary  px-6 py-3 transition-all ease-in-out duration-300">Update Password</button>
                <button onclick="openTab(event, 'tab3')" class="tablink border-b  text-primary  px-6 py-3 transition-all ease-in-out duration-300">Delete Password</button>

            </ul>
            <div id="tab1" class="tabcontent p-4 hidden  rounded-b-lg  transition-all ease-in-out duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            <div id="tab2" class="tabcontent p-4  hidden rounded-b-lg  transition-all ease-in-out duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            <div id="tab3" class="tabcontent p-4 hidden  rounded-b-lg  transition-all ease-in-out duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section> -->


<!-- <script>
    // When the DOM is loaded
    document.getElementById("tab1").classList.remove("hidden");

    // Function to switch between tabs
    function openTab(evt, tabName) {
        // Hide all tab contents
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.add("hidden");
        }

        // Deactivate all tab links
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("text-secondary");
            tablinks[i].classList.remove("border-secondary");

        }

        // Show the selected tab content and set the button as active
        document.getElementById(tabName).classList.remove("hidden");
        evt.currentTarget.classList.add("text-secondary");
        evt.currentTarget.classList.add("border-secondary");
    }
    window.onload = function() {
        var firstTabButton = document.querySelector(".tablink");
        openTab({
            currentTarget: firstTabButton
        }, "tab1");
    };

    // Get the user's name
    var username = "{{ Auth::user()->name }}";

    // Extract the first letter
    var firstLetter = username.charAt(0).toUpperCase();

    // Replace the "B" with the first letter
    document.getElementById("initial2").textContent = firstLetter;
</script> -->




















<x-app-layout>
    <main class="max-w-[1480px] mx-auto  md:mt-28 mb-20 mt-5 px-4  font-poppins">
        <section class="flex flex-col md:flex-row gap-5">
            <section class="md:w-[60%] w-full rounded-2xl bg-gray-100  p-5 flex flex-col ">
                <div class="flex gap-1 self-end items-center">
                    <div class="w-2 h-2 rounded-full bg-red-600"></div>
                    <p class=" font-poppins font-light">
                        Unverified User
                    </p>
                </div>
                <div class=" w-20 lg:w-36 aspect-square rounded-full border "> </div>
                <p class=" font-poppins font-bold mt-3 px-2 text-lg capitalize">
                    {{ Auth::user()->name }}
                </p>
                <p class=" font-poppins font-light mt-2 px-2">bettykassaw@gmail.com</p>
                <p class=" font-poppins font-light mt-2 px-2">baldaras,yeka subcity, coet delevour st</p>

                <p class=" font-poppins font-light mt-1 px-2">+251904189653</p>
                <button class="bg-primary w-32 self-end font-poppins rounded-lg text-white font-bold py-2">
                    Verify
                </button>
            </section>
            <section class="md:w-[40%] w-full flex flex-col ">
                <p class="text-lg font-poppins font-bold">
                    Subscribed services
                </p>
                <p class="text-sm font-poppins font-light mt-1">here you see your subscribed services </p>
                <div class="grid lg:grid-cols-2 md:grid-cols-1 gap-5 mt-5 grid-cols-4">
                    <div class="bg-gray-100 w-full flex flex-col rounded-lg ">
                        <div class="md:w-6 md:h-6 h-5 w-5 m-1 self-end">
                            <img class="w-full h-full" src="{{ asset('assets/icons/vuesax/linear/tick-circle.svg') }}" alt="" srcset="">
                        </div>
                        <div class="md:px-5 px-1 flex flex-col -translate-y-3">
                            <div class="">
                                <img src="{{ asset('assets/icons/vuesax/linear/card-pos.svg') }}" alt="" srcset="">
                            </div>
                            <p class="text-secondary font-poppins font-bold md:text-base text-sm mt-1">
                                Ker Wallet
                            </p>
                            <p class="text-sm font-light font-poppins text-gray-600 md:block hidden">
                                Transfer, Receive & Enjoy
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-100 w-full flex flex-col rounded-lg ">
                        <div class="md:w-6 md:h-6 h-5 w-5 m-1 self-end">
                            <img class="w-full h-full" src="{{ asset('assets/icons/vuesax/linear/close-circle.svg') }}" alt="" srcset="">
                        </div>
                        <div class="md:px-5 px-1 flex flex-col -translate-y-3">
                            <div class="">
                                <img src="{{ asset('assets/icons/vuesax/linear/buildings.svg') }}" alt="" srcset="">
                            </div>
                            <p class="text-primary font-poppins font-bold md:text-base text-sm mt-1 md:block hidden">
                                Apartment managment
                            </p>
                            <p class="text-primary font-poppins font-bold md:text-base text-sm mt-1 md:hidden block">
                                KAMS
                            </p>
                            <p class="text-sm font-light font-poppins text-gray-600 md:block hidden">
                                You build we manage
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <section class=" md:w-[59%] w-full mt-10 md:bg-gray-100 bg-transparent rounded-2xl flex flex-col py-4 md:px-4">
            <p class="font-poppins font-bold text-lg">
                Account Management
            </p>
            <p class="text-sm font-poppins font-light mt-1 mb-3">
                Manage your profile
            </p>
            <hr>
            <div class="flex flex-col mt-10 gap-5">
                <!-- <button class=" flex flex-row gap-3 border-b pb-3">

                    <div>
                        <img src="{{ asset('assets/icons/vuesax/linear/key.svg') }}" alt="" srcset="">

                    </div>
                    <p class="font-medium">
                        Change Password
                    </p>


                </button> -->
                @include('profile.partials.update-password-form')

                <!-- <button class=" flex flex-row gap-3 border-b pb-3">

                    <div>
                        <img src="{{ asset('assets/icons/vuesax/linear/user.svg') }}" alt="" srcset="">

                    </div>
                    <p class="font-medium">
                        Update profile
                    </p>


                </button> -->
                @include('profile.partials.update-profile-information-form')

                <!-- <button class=" flex flex-row justify-between items-center border-b pb-3">
                    <div class="flex flex-row gap-3">
                        <div>
                            <img src="{{ asset('assets/icons/vuesax/linear/folder-open.svg') }}" alt="" srcset="">

                        </div>
                        <p class="font-medium">
                            Verify Document
                        </p>
                    </div>
                    <div class="w-24 rounded bg-transparent border border-primary text-primary font-semibold py-1">
                        Verify
                    </div>
                </button> -->
                @include('profile.partials.verify-user-document')


                <div class="mt-10 border-t pt-5">
                    <!-- <button class=" flex flex-row gap-3  pb-3 mt-3">

                        <div>
                            <img src="{{ asset('assets/icons/vuesax/linear/security.svg') }}" alt="" srcset="">

                        </div>
                        <div class="flex flex-col items-start">
                            <p class="text-red-600">
                                Update profile
                            </p>
                            <p class="text-xs text-start">
                                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                            </p>

                        </div>


                    </button> -->
                    @include('profile.partials.delete-user-form')

                </div>
            </div>
        </section>
    </main>
</x-app-layout>