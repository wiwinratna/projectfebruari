@extends('layouts.app')

@section('title', 'Create News - NOCIS')
@section('page-title')
    Create News <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create News</h2>
            <p class="text-gray-600 mt-1">Publish updates to show on landing page</p>
        </div>
        <a href="{{ route('super-admin.news.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to News
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">News Information</h3>
        </div>

        <form action="{{ route('super-admin.news.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @include('menu.admin.news.form', ['post' => null])
        </form>
    </div>

</div>
@endsection
