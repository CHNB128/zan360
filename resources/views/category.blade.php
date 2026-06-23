@extends('layouts.app')

@section('content')
  @php
    $category = get_queried_object();
    $current_tag_slug = \App\category_topic_slug();
    $current_paged = max(1, absint(get_query_var('paged')));
    $category_base_url = get_category_link($category);

    $latest_posts = empty($current_tag_slug)
      ? get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 4,
        'cat' => $category->term_id,
        'ignore_sticky_posts' => true,
      ])
      : [];

    $tag_terms = get_terms([
      'taxonomy' => 'post_tag',
      'hide_empty' => true,
      'object_ids' => get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'cat' => $category->term_id,
      ]),
    ]);

    $news_query_args = [
      'post_type' => 'post',
      'post_status' => 'publish',
      'posts_per_page' => 9,
      'paged' => $current_paged,
      'ignore_sticky_posts' => true,
      'tax_query' => [
        [
          'taxonomy' => 'category',
          'field' => 'term_id',
          'terms' => [$category->term_id],
        ],
      ],
    ];

    if (! empty($current_tag_slug)) {
      $news_query_args['tax_query'][] = [
        'taxonomy' => 'post_tag',
        'field' => 'slug',
        'terms' => [$current_tag_slug],
      ];
    }

    $category_news_query = new WP_Query($news_query_args);
  @endphp

  <section class="category-page">
    <header class="category-page__header">
      <h1 class="category-page__title">{{ single_cat_title('', false) }}</h1>
      @if (! empty(category_description()))
        <p class="category-page__description">{!! category_description() !!}</p>
      @endif
    </header>

    @if (! empty($latest_posts))
      <section class="category-latest" aria-label="{{ \App\theme_translate('Latest posts') }}">
        <h2 class="category-latest__title">{{ \App\theme_translate('Latest') }}</h2>
        <div class="category-latest__grid">
          @foreach ($latest_posts as $post)
            <article class="category-post-card">
              <a href="{{ get_permalink($post) }}" class="category-post-card__link">
                @if (has_post_thumbnail($post))
                  {!! get_the_post_thumbnail($post, 'large', ['class' => 'category-post-card__image']) !!}
                @else
                  <div class="category-post-card__image category-post-card__image--placeholder" aria-hidden="true"></div>
                @endif

                <div class="category-post-card__content">
                  @if ($post_category = \App\theme_primary_post_category($post))
                    <span class="category-post-card__term">{{ $post_category->name }}</span>
                  @endif
                  <h3 class="category-post-card__title">{{ get_the_title($post) }}</h3>
                  <time class="category-post-card__date" datetime="{{ get_the_date('c', $post) }}">
                    {{ get_the_date('F j, Y', $post) }}
                  </time>
                </div>
              </a>
            </article>
          @endforeach
        </div>
      </section>
    @endif

    <section class="category-all-news" aria-label="{{ \App\theme_translate('All category news') }}">
      <h2 class="category-all-news__title">{{ \App\theme_translate('All news') }}</h2>

      <div class="category-all-news__filters" role="group" aria-label="{{ \App\theme_translate('Filter by tag') }}">
        <a
          href="{{ $category_base_url }}"
          class="category-all-news__filter {{ empty($current_tag_slug) ? 'is-active' : '' }}"
          @if (empty($current_tag_slug)) aria-current="true" @endif
        >
          {{ \App\theme_translate('View all') }}
        </a>
        @foreach ($tag_terms as $tag_term)
          <a
            href="{{ add_query_arg('topic', $tag_term->slug, $category_base_url) }}"
            class="category-all-news__filter {{ $current_tag_slug === $tag_term->slug ? 'is-active' : '' }}"
            @if ($current_tag_slug === $tag_term->slug) aria-current="true" @endif
          >
            {{ $tag_term->name }}
          </a>
        @endforeach
      </div>

      @if ($category_news_query->have_posts())
        <div class="category-all-news__grid">
          @while ($category_news_query->have_posts()) @php($category_news_query->the_post())
            <article class="category-post-card">
              <a href="{{ get_permalink() }}" class="category-post-card__link">
                @if (has_post_thumbnail())
                  {!! get_the_post_thumbnail(null, 'large', ['class' => 'category-post-card__image']) !!}
                @else
                  <div class="category-post-card__image category-post-card__image--placeholder" aria-hidden="true"></div>
                @endif

                <div class="category-post-card__content">
                  @if ($post_category = \App\theme_primary_post_category())
                    <span class="category-post-card__term">{{ $post_category->name }}</span>
                  @endif
                  <h3 class="category-post-card__title">{{ get_the_title() }}</h3>
                  <time class="category-post-card__date" datetime="{{ get_the_date('c') }}">
                    {{ get_the_date('F j, Y') }}
                  </time>
                </div>
              </a>
            </article>
          @endwhile
        </div>

        <div class="category-all-news__pagination">
          {!! paginate_links([
            'total' => $category_news_query->max_num_pages,
            'current' => $current_paged,
            'type' => 'list',
            'prev_text' => \App\theme_translate('Previous'),
            'next_text' => \App\theme_translate('Next'),
            'add_args' => empty($current_tag_slug) ? [] : ['topic' => $current_tag_slug],
          ]) !!}
        </div>
      @else
        <x-alert type="warning">
          @if (! empty($current_tag_slug))
            {!! \App\theme_translate('Sorry, no results were found for this tag.') !!}
          @else
            {!! \App\theme_translate('Sorry, no results were found.') !!}
          @endif
        </x-alert>
      @endif

      @php(wp_reset_postdata())
    </section>
  </section>
@endsection

@section('sidebar')
@endsection
