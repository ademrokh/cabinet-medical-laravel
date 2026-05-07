@extends('layouts.app')

@section('title', 'Admin - Create Doctor')

@section('content')
<style>
    @keyframes fade-up {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-up {
        animation: fade-up 0.45s ease-out both;
    }
</style>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 fade-up">
        <a href="{{ route('admin.doctors.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition">Back to doctors</a>
        <h1 class="text-3xl font-bold text-slate-900 mt-2">Create Doctor</h1>
        <p class="text-slate-600 mt-1">Add a new doctor profile linked to a user.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 fade-up">
        <form method="POST" action="{{ route('admin.doctors.store') }}">
            @csrf
            @include('admin.doctors._form')
        </form>
    </div>
</div>
@endsection
