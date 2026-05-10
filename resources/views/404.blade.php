@extends('layouts.app')

@section('content')
  <section class="error-404" aria-label="{{ \App\theme_translate('Page not found') }}">
    <div class="error-404__inner">
      <p class="error-404__code">404</p>
      <h1 class="error-404__title">{{ \App\theme_translate('Page not found') }}</h1>
      <p class="error-404__description">
        {{ \App\theme_translate('The page you are looking for does not exist or has been moved.') }}
      </p>
      <a href="{{ home_url('/') }}" class="error-404__home-link">{{ \App\theme_translate('Go to homepage') }}</a>
    </div>
  </section>
@endsection
