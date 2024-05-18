<x-app-layout>
    <main class="max-w-7xl  mx-auto mt-16 px-6">
        <div>
            <p class="text-lg font-poppins font-bold">
                Account Setting
            </p>
            <p class="text-sm font-poppins font-light mt-1">Here you manage your Account </p>
        </div>
        <section class="mt-10">
            <div class="flex flex-row justify-evenly overflow-x-auto border-b">
                <div onclick="openTab(event, 'tab1')" class="tablink  cursor-default flex flex-col gap-1 items-center border-b flex-shrink-0 p-4">
                    <img class="w-5 h-5" src="{{ asset('assets/icons/vuesax/linear/user.svg') }}" alt="" srcset="">
                    <p>User Profile</p>
                </div>
                <div onclick="openTab(event, 'tab2')" class="tablink cursor-default flex flex-col gap-1 items-center border-b flex-shrink-0 p-4">
                    <img class="w-5 h-5" src="{{ asset('assets/icons/vuesax/linear/location.svg') }}" alt="" srcset="">
                    <p>Update Address</p>
                </div>
                <div onclick="openTab(event, 'tab3')" class="tablink cursor-default flex flex-col gap-1 items-center border-b flex-shrink-0 p-4">
                    <img class="w-5 h-5" src="{{ asset('assets/icons/vuesax/linear/key.svg') }}" alt="" srcset="">
                    <p>Password Manager</p>
                </div>
                <div onclick="openTab(event, 'tab4')" class="tablink cursor-default flex flex-col gap-1 items-center border-b flex-shrink-0 p-4">
                    <img class="w-5 h-5" src="{{ asset('assets/icons/vuesax/linear/scan.svg') }}" alt="" srcset="">
                    <p>Verification Center</p>
                </div>
                <div onclick="openTab(event, 'tab5')" class="tablink cursor-default  flex flex-col gap-1 items-center border-b flex-shrink-0 p-4">
                    <img class="w-5 h-5" src="{{ asset('assets/icons/vuesax/linear/toggle-on-circle.svg') }}" alt="" srcset="">
                    <p>Account Setting</p>
                </div>
            </div>
        </section>
        <section>
            <div id="tab1" class="tabcontent p-4 hidden  rounded-b-lg  transition-all ease-in-out duration-300">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div id="tab2" class="tabcontent p-4 hidden  rounded-b-lg  transition-all ease-in-out duration-300">
                @include('profile.partials.address-form')
            </div>
            <div id="tab3" class="tabcontent p-4 hidden  rounded-b-lg  transition-all ease-in-out duration-300">
                @include('profile.partials.update-password-form')
            </div>
            <div id="tab4" class="tabcontent p-4 hidden  rounded-b-lg  transition-all ease-in-out duration-300">
                @include('profile.partials.verify-user-document')
            </div>
            </sectiion>
            <script>
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
            </script>
    </main>
</x-app-layout>