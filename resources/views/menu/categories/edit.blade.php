@extends('layouts.app')

@section('title', 'Edit Job Category - ARISE')
@section('page-title')
    Edit Job Category
@endsection

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('super-admin.job-categories.index') }}"
       class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold text-sm transition">
        <i class="fas fa-arrow-left mr-2"></i> Back to Categories
    </a>

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Job Category: {{ $category->name }}</h2>

        <form method="POST" action="{{ route('super-admin.job-categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('menu.categories.partials.form-fields')

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('super-admin.job-categories.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-xs transition">
                    <i class="fas fa-save mr-2"></i> Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
