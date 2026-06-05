@extends('layouts.app')

@section('content')
  <section class="tag-page">
    <header class="tag-page__header">
      <p class="tag-page__eyebrow">{{ \App\theme_translate('Метка') }}</p>
      <h1 class="tag-page__title">{{ single_tag_title('', false) }}</h1>
      @if (! empty(tag_description()))
        <p class="tag-page__description">{!! tag_description() !!}</p>
      @endif
    </header>

    <section class="tag-news" aria-label="{{ \App\theme_translate('Записи с меткой') }}">
      @if (have_posts())
        <div class="tag-news__grid">
          @while (have_posts()) @php(the_post())
            <article class="category-post-card tag-post-card">
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
                  <h2 class="category-post-card__title">{{ get_the_title() }}</h2>
                  <time class="category-post-card__date" datetime="{{ get_the_date('c') }}">
                    {{ get_the_date('F j, Y') }}
                  </time>
                  <p class="tag-post-card__excerpt">{{ wp_trim_words(get_the_excerpt(), 24, '...') }}</p>
                </div>
              </a>
            </article>
          @endwhile
        </div>

        <div class="tag-news__pagination">
          {!! get_the_posts_pagination([
            'prev_text' => \App\theme_translate('Previous'),
            'next_text' => \App\theme_translate('Next'),
          ]) !!}
        </div>
      @else
        <x-alert type="warning">
          {!! \App\theme_translate('Sorry, no results were found for this tag.') !!}
        </x-alert>
      @endif
    </section>
  </section>
@endsection

@section('sidebar')
@endsection
