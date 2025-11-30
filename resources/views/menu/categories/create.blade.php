@extends('layouts.app')

@section('title', 'Create Job Category - NOCIS')
@section('page-title')
    Create Job Category
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf
            @include('menu.categories.partials.form-fields')

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection