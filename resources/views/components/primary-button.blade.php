<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center  bg-primary border border-transparent  font-semibold  text-white  tracking-widest hover:bg-primary-400 focus:bg-primary-600 active:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>