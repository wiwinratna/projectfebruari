@extends('layouts.app')

@section('title', 'Edit News - NOCIS')
@section('page-title')
    Edit News <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit News</h2>
            <p class="text-gray-600 mt-1">Update news details and publication status</p>
        </div>
        <a href="{{ route('super-admin.news.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to News
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">News Information</h3>
        </div>

        <form action="{{ route('super-admin.news.update', $post->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-6">
        @csrf
        @method('PUT')

        @include('menu.admin.news.form', ['post' => $post])
        </form>
    </div>

</div>
@endsection
