@extends('layouts.app')

@section('content')
  <section class="error-404" aria-label="{{ __('Page not found', 'sage') }}">
    <div class="error-404__inner">
      <p class="error-404__code">404</p>
      <h1 class="error-404__title">{{ __('Page not found', 'sage') }}</h1>
      <p class="error-404__description">
        {{ __('The page you are looking for does not exist or has been moved.', 'sage') }}
      </p>
      <a href="{{ home_url('/') }}" class="error-404__home-link">{{ __('Go to homepage', 'sage') }}</a>
    </div>
  </section>
@endsection
