@php
    $isEdit = isset($doctor);
    $doctor = $doctor ?? null;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="lg:col-span-1">
        <label for="name" class="block text-sm font-semibold text-slate-700">Full name</label>
        <input id="name" name="name" type="text" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('name', optional($doctor?->user)->name) }}" placeholder="Dr. Jane Doe">
        @error('name')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-1">
        <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
        <input id="email" name="email" type="email" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('email', optional($doctor?->user)->email) }}" placeholder="doctor@clinic.com">
        @error('email')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-1">
        <label for="password" class="block text-sm font-semibold text-slate-700">
            {{ $isEdit ? 'New password (optional)' : 'Password' }}
        </label>
        <input id="password" name="password" type="password" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" placeholder="********">
        @error('password')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-1">
        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" placeholder="********">
    </div>

    <div class="lg:col-span-1">
        <label for="telephone" class="block text-sm font-semibold text-slate-700">Telephone</label>
        <input id="telephone" name="telephone" type="text" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('telephone', optional($doctor?->user)->telephone) }}" placeholder="01 23 45 67 89">
        @error('telephone')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-1">
        <label for="specialty_id" class="block text-sm font-semibold text-slate-700">Specialty</label>
        <select id="specialty_id" name="specialty_id" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
            <option value="">Select a specialty</option>
            @foreach($specialties as $specialty)
                <option value="{{ $specialty->id }}" @if(old('specialty_id', $doctor->specialty_id ?? '') == $specialty->id) selected @endif>
                    {{ $specialty->name }}
                </option>
            @endforeach
        </select>
        @error('specialty_id')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-2">
        <label for="biography" class="block text-sm font-semibold text-slate-700">Biography</label>
        <textarea id="biography" name="biography" rows="5" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" placeholder="Short professional bio">{{ old('biography', $doctor->biography ?? '') }}</textarea>
        @error('biography')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-2">
        <label class="inline-flex items-center gap-3">
            <input type="checkbox" name="available" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-400" @if(old('available', $doctor->available ?? false)) checked @endif>
            <span class="text-sm font-semibold text-slate-700">Available for appointments</span>
        </label>
        @error('available')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-8 flex flex-wrap items-center justify-end gap-3">
    <a href="{{ route('admin.doctors.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-semibold hover:border-slate-300 hover:text-slate-900 transition">Cancel</a>
    <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold shadow-sm hover:bg-emerald-700 transition">
        {{ $isEdit ? 'Update Doctor' : 'Create Doctor' }}
    </button>
</div>
