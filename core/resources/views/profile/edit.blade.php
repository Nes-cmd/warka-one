<x-app-layout>

    <!-- <div>
        Hello {{ Auth::user()->name }}
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
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

    <section class="max-w-6xl mx-auto md:my-5 ">
        <div class="w-full h-40 border rounded-md bg-gradient-to-r from-gray-200 to-primary relative">
            <div id="initial" class="bg-primary w-20 h-20 rounded-full absolute -bottom-12 left-10 flex justify-center items-center text-white font-bold text-4xl">

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
    </section>
</x-app-layout>

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
    document.getElementById("initial").textContent = firstLetter;
</script>