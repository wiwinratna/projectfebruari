@extends('layouts.app')

@php($hideSidebar = true)

@section('title', ($post->title ?? 'News') . ' - NOCIS')

@section('page-title')
  News
@endsection

@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-2xl font-bold text-gray-800">{{ $post->title }}</h2>
      <p class="text-gray-600 mt-1">
        {{ $post->source_name ?? 'NOCIS' }}
        @if($post->published_at)
          • {{ \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') }}
        @endif
      </p>
    </div>

    <a href="{{ route('news.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
      <i class="fas fa-arrow-left mr-2"></i> Back to News
    </a>
  </div>

  {{-- Cover --}}
  @if($post->cover_image)
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <img src="{{ asset('storage/'.$post->cover_image) }}"
           class="w-full max-h-[420px] object-cover"
           alt="cover">
    </div>
  @endif

  {{-- Content --}}
  <div class="bg-white rounded-lg shadow">
    <div class="p-6">
      @if($post->excerpt)
        <div class="p-4 bg-red-50 border border-red-100 text-red-700 rounded-lg mb-5">
          <div class="flex items-start gap-2">
            <i class="fas fa-bullhorn mt-0.5"></i>
            <div class="text-sm font-medium">{{ $post->excerpt }}</div>
          </div>
        </div>
      @endif

      <div class="prose max-w-none">
        {!! nl2br(e($post->content ?? '')) !!}
      </div>

      @if($post->source_url)
        <div class="mt-6">
          <a href="{{ $post->source_url }}" target="_blank"
             class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold">
            Source Link <i class="fas fa-arrow-right text-sm"></i>
          </a>
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
