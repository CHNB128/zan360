<article @php(post_class('single-post-page h-entry'))>
  <header class="single-post-page__header">
    <div class="single-post-page__inner">
      @php($post_categories = \App\theme_visible_post_categories())
      @if (! empty($post_categories))
        <p class="single-post-page__categories">
          @foreach ($post_categories as $category)
            <a href="{{ get_category_link($category->term_id) }}">{{ $category->name }}</a>
          @endforeach
        </p>
      @endif

      <h1 class="single-post-page__title p-name">
        {!! $title !!}
      </h1>

      @if (has_excerpt())
        <div class="single-post-page__excerpt p-summary">
          @php(the_excerpt())
        </div>
      @endif

      <div class="single-post-page__meta">
        @include('partials.entry-meta')
      </div>
    </div>
  </header>

  @if (has_post_thumbnail())
    <div class="single-post-page__hero">
      {!! get_the_post_thumbnail(null, 'full', ['class' => 'single-post-page__hero-image']) !!}
    </div>
  @endif

  <div class="single-post-page__content-wrap">
    <div class="single-post-page__content e-content">
      @php(the_content())
    </div>

    @if ($pagination())
      <footer class="single-post-page__footer">
        <nav class="page-nav" aria-label="Page">
          {!! $pagination !!}
        </nav>
      </footer>
    @endif
  </div>

</article>
