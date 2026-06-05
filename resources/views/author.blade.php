@extends('layouts.app')

@section('content')
  @php
    $author = get_queried_object();
  @endphp

  <section class="author-page">
    <header class="author-page__header">
      <h1 class="author-page__title">
        {{ \App\theme_translate('Posts by') }} {{ $author->display_name ?? \App\theme_translate('Author') }}
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
                @if ($post_category = \App\theme_primary_post_category())
                  <span class="category-post-card__term">{{ $post_category->name }}</span>
                @endif
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
        {!! \App\theme_translate('No posts were found for this author.') !!}
      </x-alert>
    @endif
  </section>
@endsection

@section('sidebar')
@endsection
