<footer class="site-footer" role="contentinfo">
  @php
    $language_labels = [
      'ru' => 'RU',
      'en' => 'EN',
      'kz' => 'KZ',
      'kk' => 'KZ',
    ];

    $language_items = [];
    $footer_categories = get_categories([
      'taxonomy' => 'category',
      'hide_empty' => true,
      'orderby' => 'name',
      'order' => 'ASC',
    ]);

    if (is_wp_error($footer_categories)) {
      $footer_categories = [];
    }

    $linkedin_url = get_theme_mod('zan360_linkedin_url');
    $instagram_url = get_theme_mod('zan360_instagram_url');
    $site_logo_url = \Illuminate\Support\Facades\Vite::asset('resources/images/PHOTO-2026-04-23-19-51-16.jpg');
  @endphp
  <div class="site-footer__inner">
    <div class="site-footer__brand-col">
      <a class="site-footer__brand" href="{{ home_url('/') }}" aria-label="{{ get_bloginfo('name') }}">
        <span class="site-footer__logo" aria-hidden="true">
          <img src="{{ $site_logo_url }}" alt="" loading="lazy" decoding="async">
        </span>
      </a>

      <p class="site-footer__copyright">
        {{ \App\theme_translate('Copyright') }} © {{ date('Y') }} | {{ get_bloginfo('name') }}
      </p>
    </div>

    <div class="site-footer__links-col">
      <h2 class="site-footer__heading">{{ \App\theme_translate('Categories') }}</h2>
      <ul class="site-footer__list">
        @foreach ($footer_categories as $category)
          <li><a href="{{ get_category_link($category) }}">{{ $category->name }}</a></li>
        @endforeach
      </ul>
    </div>

    <div class="site-footer__links-col">
      <h2 class="site-footer__heading">zan360</h2>
      <ul class="site-footer__list">
        <li><a href="{{ \App\theme_localized_page_url('/about') }}">{{ \App\theme_translate('About') }}</a></li>
        <li><a href="{{ \App\theme_localized_page_url('/contact') }}">{{ \App\theme_translate('Contact') }}</a></li>
        <li><a href="{{ \App\theme_localized_page_url('/write-for-us') }}">{{ \App\theme_translate('Write For Us') }}</a></li>
        <li><a href="{{ \App\theme_localized_page_url('/privacy-policy') }}">{{ \App\theme_translate('Privacy Policy') }}</a></li>
        <li><a href="{{ \App\theme_localized_page_url('/terms-and-conditions') }}">{{ \App\theme_translate('Terms and Conditions') }}</a></li>
      </ul>
    </div>

    @if ($linkedin_url || $instagram_url)
      <div class="site-footer__links-col">
        <h2 class="site-footer__heading">{{ \App\theme_translate('Follow Us') }}</h2>
        <ul class="site-footer__list">
          @if ($linkedin_url)
            <li><a href="{{ esc_url($linkedin_url) }}" aria-label="{{ \App\theme_translate('LinkedIn') }}">{{ \App\theme_translate('LinkedIn') }}</a></li>
          @endif
          @if ($instagram_url)
            <li><a href="{{ esc_url($instagram_url) }}" aria-label="{{ \App\theme_translate('Instagram') }}">{{ \App\theme_translate('Instagram') }}</a></li>
          @endif
        </ul>
      </div>
    @endif
  </div>
</footer>
