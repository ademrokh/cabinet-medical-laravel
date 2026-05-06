<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 via-teal-600 to-sky-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-emerald-700 hover:via-teal-700 hover:to-sky-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md']) }}>
    {{ $slot }}
</button>
