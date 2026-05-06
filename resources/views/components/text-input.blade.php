@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 focus:border-emerald-500 focus:ring-emerald-400 rounded-xl shadow-sm bg-white/90']) }}>
