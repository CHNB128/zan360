<footer class="site-footer" role="contentinfo">
  @php
    $language_labels = [
      'ru' => 'RU',
      'en' => 'EN',
      'kz' => 'KZ',
      'kk' => 'KZ',
    ];

    $language_items = [];
  @endphp
  <div class="site-footer__inner">
    <div class="site-footer__brand-col">
      <a class="site-footer__brand" href="{{ home_url('/') }}" aria-label="{{ get_bloginfo('name') }}">
        <span class="site-footer__brand-name">Zan360</span>
      </a>

      <p class="site-footer__copyright">
        Copyright © {{ date('Y') }} | {{ get_bloginfo('name') }}
      </p>
    </div>

    <div class="site-footer__links-col">
      <h2 class="site-footer__heading">{{ __('Categories', 'sage') }}</h2>
      <ul class="site-footer__list">
        @foreach ($categories as $category)
          <li><a href="{{ get_category_link($category) }}">{{ $category->name }}</a></li>
        @endforeach
      </ul>
    </div>

    <div class="site-footer__links-col">
      <h2 class="site-footer__heading">{{ __('zan360', 'sage') }}</h2>
      <ul class="site-footer__list">
        <li><a href="{{ home_url('/about') }}">{{ __('About', 'sage') }}</a></li>
        <li><a href="{{ home_url('/contact') }}">{{ __('Contact', 'sage') }}</a></li>
        <li><a href="{{ home_url('/advertise') }}">{{ __('Advertise', 'sage') }}</a></li>
        <li><a href="{{ home_url('/write-for-us') }}">{{ __('Write For Us', 'sage') }}</a></li>
        <li><a href="{{ home_url('/privacy-policy') }}">{{ __('Privacy Policy', 'sage') }}</a></li>
        <li><a href="{{ home_url('/terms-and-conditions') }}">{{ __('Terms and Conditions', 'sage') }}</a></li>
      </ul>
    </div>

    <div class="site-footer__links-col">
      <h2 class="site-footer__heading">{{ __('Follow Us', 'sage') }}</h2>
      <ul class="site-footer__list">
        <li><a href="#" aria-label="{{ __('LinkedIn', 'sage') }}">{{ __('LinkedIn', 'sage') }}</a></li>
      </ul>
    </div>
  </div>
</footer>
