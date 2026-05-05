<article @php(post_class('page-content-page'))>
  <div class="page-content-page__content">
    @php(the_content())
  </div>

  @if ($pagination())
    <nav class="page-nav page-content-page__pagination" aria-label="Page">
      {!! $pagination !!}
    </nav>
  @endif
</article>
