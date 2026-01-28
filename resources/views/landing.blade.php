@extends('layouts.public')

@section('title', 'NOCIS - Sport Workforce Information System')

@section('content')
<div class="landing-scope">
  @include('landing.partials.nav')
  @include('landing.partials.hero')

  {{-- bagian bawahnya sesuai contoh lama kamu --}}
  @include('landing.partials.flow')
  @include('landing.partials.jobs')
  @include('landing.partials.features')
  @include('landing.partials.news')
  @include('landing.partials.about')
  @include('landing.partials.cta')
  @include('landing.partials.footer')
</div>
@endsection
