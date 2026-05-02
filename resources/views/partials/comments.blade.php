@if (! post_password_required())
  <section id="comments" class="comments comments-section">
    <header class="comments-section__header">
      <h2 class="comments-section__title">{{ __('Comments', 'sage') }}</h2>
      <p class="comments-section__subtitle">{{ __('Share your thoughts about this post.', 'sage') }}</p>
    </header>

    @if ($responses())
      <h3 class="comments-section__responses-title">
        {!! $title !!}
      </h3>

      <ol class="comment-list">
        {!! $responses !!}
      </ol>

      @if ($paginated())
        <nav aria-label="Comment">
          <ul class="pager">
            @if ($previous())
              <li class="previous">
                {!! $previous !!}
              </li>
            @endif

            @if ($next())
              <li class="next">
                {!! $next !!}
              </li>
            @endif
          </ul>
        </nav>
      @endif
    @endif

    @if ($closed())
      <x-alert type="warning">
        {!! __('Comments are closed.', 'sage') !!}
      </x-alert>
    @endif

    <div class="comments-section__form-wrap">
      @php(comment_form([
        'title_reply_before' => '<h3 class="comments-section__form-title">',
        'title_reply_after' => '</h3>',
        'class_submit' => 'comments-section__submit',
      ]))
    </div>
  </section>
@endif
