@extends('layouts.app')

@section('title', 'Admin - Edit Patient')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.patients.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition">Back to patients</a>
        <h1 class="text-3xl font-bold text-slate-900 mt-2">Edit Patient</h1>
        <p class="text-slate-600 mt-1">Update patient account details.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form method="POST" action="{{ route('admin.patients.update', $patient) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700">Full name</label>
                    <input id="name" name="name" type="text" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('name', $patient->name) }}">
                    @error('name')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('email', $patient->email) }}">
                    @error('email')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-semibold text-slate-700">Telephone</label>
                    <input id="telephone" name="telephone" type="text" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('telephone', $patient->telephone) }}">
                    @error('telephone')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_naissance" class="block text-sm font-semibold text-slate-700">Birth date</label>
                    <input id="date_naissance" name="date_naissance" type="date" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('date_naissance', $patient->date_naissance) }}">
                    @error('date_naissance')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="adresse" class="block text-sm font-semibold text-slate-700">Address</label>
                <input id="adresse" name="adresse" type="text" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-emerald-400" value="{{ old('adresse', $patient->adresse) }}">
                @error('adresse')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-semibold hover:border-slate-300 hover:text-slate-900 transition">Cancel</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold shadow-sm hover:bg-emerald-700 transition">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
