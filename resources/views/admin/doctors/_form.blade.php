@php
    $isEdit = isset($doctor);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="lg:col-span-1">
        <label for="user_id" class="block text-sm font-semibold text-slate-700">Doctor User</label>
        <select id="user_id" name="user_id" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400">
            <option value="">Select a doctor user</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @if(old('user_id', $doctor->user_id ?? '') == $user->id) selected @endif>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
        @error('user_id')
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
