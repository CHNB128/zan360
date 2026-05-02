@extends('layouts.app')

@section('content')
  @php
    $author = get_queried_object();
  @endphp

  <section class="author-page">
    <header class="author-page__header">
      <h1 class="author-page__title">
        {{ __('Posts by', 'sage') }} {{ $author->display_name ?? __('Author', 'sage') }}
      </h1>
      @if (! empty(get_the_author_meta('description', $author->ID ?? 0)))
        <p class="author-page__description">
          {{ get_the_author_meta('description', $author->ID) }}
        </p>
      @endif
    </header>

    @if (have_posts())
      <div class="author-page__list">
        @while (have_posts()) @php(the_post())
          <article @php(post_class('category-post-card author-post-card'))>
            <a href="{{ get_permalink() }}" class="category-post-card__link author-post-card__link">
              @if (has_post_thumbnail())
                {!! get_the_post_thumbnail(null, 'large', ['class' => 'category-post-card__image author-post-card__image']) !!}
              @else
                <div class="category-post-card__image category-post-card__image--placeholder author-post-card__image" aria-hidden="true"></div>
              @endif

              <div class="category-post-card__content">
                <span class="category-post-card__term">{{ get_the_category()[0]->name ?? __('News', 'sage') }}</span>
                <h2 class="category-post-card__title">{{ get_the_title() }}</h2>
                <time class="category-post-card__date" datetime="{{ get_the_date('c') }}">
                  {{ get_the_date('F j, Y') }}
                </time>
                <p class="author-post-card__excerpt">{{ wp_trim_words(get_the_excerpt(), 30, '...') }}</p>
              </div>
            </a>
          </article>
        @endwhile
      </div>

      <div class="author-page__pagination">
        {!! get_the_posts_navigation() !!}
      </div>
    @else
      <x-alert type="warning">
        {!! __('No posts were found for this author.', 'sage') !!}
      </x-alert>
    @endif
  </section>
@endsection

@section('sidebar')
@endsection
