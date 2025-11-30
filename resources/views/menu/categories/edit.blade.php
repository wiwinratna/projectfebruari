@extends('layouts.app')

@section('title', 'Edit Job Category - NOCIS')
@section('page-title')
    Edit Job Category
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('menu.categories.partials.form-fields')

            <div class="flex items-center justify-between">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Back to Categories
                </a>
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600">
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection