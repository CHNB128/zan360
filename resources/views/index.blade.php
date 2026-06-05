@extends('layouts.app')

@section('content')
  @if (! (is_home() || is_front_page()))
    @include('partials.page-header')
  @endif

  @php
    $is_news_home = is_home() || is_front_page();
  @endphp

  @if ($is_news_home)
    <div class="home-page">
    @php
      $hero_posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'ignore_sticky_posts' => true,
      ]);
      $featured_post = $hero_posts[0] ?? null;
      $stacked_posts = array_slice($hero_posts, 1, 5);
      $hero_post_ids = array_map(static fn ($post) => $post->ID, $hero_posts);
      $more_news_posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'offset' => 6,
        'ignore_sticky_posts' => true,
      ]);

      $category_sections = [];
      $categories = \App\theme_visible_categories([
        'taxonomy' => 'category',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
      ]);

      foreach ($categories as $index => $category) {
        $posts_per_section = $index % 2 === 0 ? 2 : 3;
        $category_posts = get_posts([
          'post_type' => 'post',
          'post_status' => 'publish',
          'posts_per_page' => $posts_per_section,
          'cat' => $category->term_id,
          'ignore_sticky_posts' => true,
        ]);

        if (! empty($category_posts)) {
          $category_sections[] = [
            'category' => $category,
            'posts' => $category_posts,
            'columns' => $posts_per_section,
          ];
        }
      }
    @endphp

    @if ($featured_post)
      @php
        $featured_author = get_the_author_meta('display_name', $featured_post->post_author);
        $featured_category = \App\theme_primary_post_category($featured_post);
        $featured_time_ago = sprintf(
          \App\theme_translate('%s ago'),
          human_time_diff(get_post_time('U', true, $featured_post), current_time('timestamp'))
        );
      @endphp
      <section class="home-hero" aria-label="{{ \App\theme_translate('Featured stories') }}">
        <div class="home-hero__primary">
          <article class="home-hero__featured">
            <a href="{{ get_permalink($featured_post) }}" class="home-hero__featured-link">
              <div class="home-hero__featured-media">
                @if (has_post_thumbnail($featured_post))
                  {!! get_the_post_thumbnail($featured_post, 'large', ['class' => 'home-hero__featured-image']) !!}
                @else
                  <div class="home-hero__featured-image home-hero__featured-image--placeholder" aria-hidden="true"></div>
                @endif
              </div>

              <div class="home-hero__featured-content">
                @if ($featured_category)
                  <span class="home-hero__category home-hero__category--featured">{{ $featured_category->name }}</span>
                @endif
                <h2 class="home-hero__featured-title">{{ get_the_title($featured_post) }}</h2>
                <p class="home-hero__featured-meta">
                  <span>{{ $featured_author }}</span>
                  <span class="home-hero__featured-meta-sep" aria-hidden="true">-</span>
                  <time datetime="{{ get_post_time('c', true, $featured_post) }}">{{ $featured_time_ago }}</time>
                </p>
              </div>
            </a>
          </article>
        </div>

        <div class="home-hero__secondary">
          <header class="home-hero__sidebar-heading">
            <h2 class="home-hero__sidebar-title">{{ \App\theme_translate('Main') }}</h2>
          </header>

          <div class="home-hero__stacked-list">
            @foreach ($stacked_posts as $post)
              <article class="home-hero__stacked-item">
                <a href="{{ get_permalink($post) }}" class="home-hero__stacked-link">
                  @if (has_post_thumbnail($post))
                    {!! get_the_post_thumbnail($post, 'thumbnail', ['class' => 'home-hero__stacked-image']) !!}
                  @else
                    <div class="home-hero__stacked-image home-hero__stacked-image--placeholder" aria-hidden="true"></div>
                  @endif

                  <div class="home-hero__stacked-content">
                    <h3 class="home-hero__stacked-title">{{ get_the_title($post) }}</h3>
                    <div class="home-hero__stacked-meta">
                      <span class="home-hero__stacked-author">{{ get_the_author_meta('display_name', $post->post_author) }}</span>
                      <time class="home-hero__stacked-date" datetime="{{ get_the_date('c', $post) }}">
                        {{ get_the_date('d.m.Y', $post) }}
                      </time>
                    </div>
                  </div>
                </a>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    @if (! empty($more_news_posts))
      <section class="more-news" aria-label="{{ \App\theme_translate('More news') }}">
        <div class="more-news__grid">
          @foreach ($more_news_posts as $post)
            <article class="more-news__card">
              <a href="{{ get_permalink($post) }}" class="more-news__link">
                @if (has_post_thumbnail($post))
                  {!! get_the_post_thumbnail($post, 'medium_large', ['class' => 'more-news__image']) !!}
                @else
                  <div class="more-news__image more-news__image--placeholder" aria-hidden="true"></div>
                @endif

                <div class="more-news__content">
                  @if ($post_category = \App\theme_primary_post_category($post))
                    <span class="more-news__category">{{ $post_category->name }}</span>
                  @endif
                  <h3 class="more-news__card-title">{{ get_the_title($post) }}</h3>
                  <p class="more-news__excerpt">{{ wp_trim_words(get_the_excerpt($post), 18, '...') }}</p>
                </div>
              </a>
            </article>
          @endforeach
        </div>
      </section>
    @endif

    @foreach ($category_sections as $section)
      <section class="category-news category-news--{{ $section['columns'] }}" aria-label="{{ $section['category']->name }}">
        <div class="category-news__heading">
          <h2 class="category-news__title">{{ $section['category']->name }}</h2>
          <a href="{{ get_category_link($section['category']) }}" class="category-news__all-link">{{ \App\theme_translate('See all') }}</a>
        </div>

        <div class="category-news__grid">
          @foreach ($section['posts'] as $post)
            <article class="category-news__card">
              <a href="{{ get_permalink($post) }}" class="category-news__link">
                @if (has_post_thumbnail($post))
                  {!! get_the_post_thumbnail($post, 'medium_large', ['class' => 'category-news__image']) !!}
                @else
                  <div class="category-news__image category-news__image--placeholder" aria-hidden="true"></div>
                @endif

                <div class="category-news__content">
                  @if ($post_category = \App\theme_primary_post_category($post))
                    <span class="category-news__category">{{ $post_category->name }}</span>
                  @endif
                  <h3 class="category-news__card-title">{{ get_the_title($post) }}</h3>
                  <p class="category-news__excerpt">{{ wp_trim_words(get_the_excerpt($post), 18, '...') }}</p>
                </div>
              </a>
            </article>
          @endforeach
        </div>
      </section>
    @endforeach
    </div>
  @endif

  @if (! $is_news_home && ! have_posts())
    <x-alert type="warning">
      {!! \App\theme_translate('Sorry, no results were found.') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  @if (! $is_news_home)
    @while(have_posts()) @php(the_post())
      @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
    @endwhile

    {!! get_the_posts_navigation() !!}
  @endif
@endsection

@section('sidebar')
  @if (! (is_home() || is_front_page()))
    @include('sections.sidebar')
  @endif
@endsection
