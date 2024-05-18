<section>
    <!-- <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-verify-user-document')" class=" flex flex-row gap-3 border-b pb-3 w-full">

        <div class="flex flex-row gap-3 w-full">
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
    <!-- <x-modal name="confirm-verify-user-document" :show="$errors->isNotEmpty()" focusable> -->
    <section class="mt-10 flex flex-row items-center justify-around max-w-3xl mx-auto">
        <div onclick="openVerify(event, 'ID')" class="link py-3 border-b">
            Identity Verification
        </div>
        <div onclick="openVerify(event, 'AD')" class="link py-3 border-b">
            Address Verification
        </div>
    </section>
    <section id="ID" class="content hidden max-w-4xl mx-auto mt-5">
        <form class="p-6">
            <p class="font-poppins text-center font-semibold text-lg">
                Verify your identity
            </p>
            <p class="text-sm font-poppins my-2 text-center font-light">
                Add your government id or passport for verifying your identity
            </p>
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" />
                </label>
            </div>
        </form>
    </section>
    <section id="AD" class="content hidden max-w-4xl mx-auto mt-5">
        <form class="p-6">
            <p class="font-poppins text-center font-semibold text-lg">
                Verify your Residency
            </p>
            <p class="text-sm font-poppins my-2 text-center font-light">
                Add Proof of residency like bank statement
            </p>
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" />
                </label>
            </div>
        </form>
    </section>
    <!-- </x-modal> -->

    <script>
        // When the DOM is loaded
        document.getElementById("ID").classList.remove("hidden");

        // Function to switch between tabs
        function openVerify(evt, tabName) {
            // Hide all tab contents
            var i, content, links;
            content = document.getElementsByClassName("content");
            for (i = 0; i < content.length; i++) {
                content[i].classList.add("hidden");
            }

            // Deactivate all tab links
            links = document.getElementsByClassName("link");
            for (i = 0; i < links.length; i++) {
                links[i].classList.remove("text-secondary");
                links[i].classList.remove("border-secondary");

            }

            // Show the selected tab content and set the button as active
            document.getElementById(tabName).classList.remove("hidden");
            evt.currentTarget.classList.add("text-secondary");
            evt.currentTarget.classList.add("border-secondary");
        }
        window.onload = function() {
            var firstTabButton = document.querySelector(".tablink");
            openVerify({
                currentTarget: firstTabButton
            }, "ID");
        };

        // Get the user's name
        var username = "{{ Auth::user()->name }}";

        // Extract the first letter
        var firstLetter = username.charAt(0).toUpperCase();

        // Replace the "B" with the first letter
        document.getElementById("initial2").textContent = firstLetter;
    </script>

</section>