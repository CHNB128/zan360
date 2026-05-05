<header class="site-header" role="banner">
  @php
    $language_labels = [
      'ru' => 'RU',
      'en' => 'EN',
      'kz' => 'KZ',
      'kk' => 'KZ',
    ];

    $language_items = [];

    $active_language = strtolower((string) get_query_var('lang'));
    if (empty($active_language)) {
      $active_language = substr(get_locale(), 0, 2);
    }

    if (function_exists('pll_current_language')) {
      $active_language = pll_current_language('slug') ?: $active_language;
    }

    $current_url = home_url(add_query_arg([], $GLOBALS['wp']->request ?? ''));
    $is_posts_home = is_home() || is_front_page();

    if (function_exists('pll_the_languages')) {
      $pll_languages = pll_the_languages([
        'raw' => 1,
        'hide_if_empty' => 0,
        'hide_if_no_translation' => 0,
      ]);
      if (is_array($pll_languages)) {
        foreach ($pll_languages as $pll_language) {
          $slug = strtolower((string) ($pll_language['slug'] ?? ''));
          if (empty($slug)) {
            continue;
          }

          $has_translation = empty($pll_language['no_translation']);
          $is_current_language = ! empty($pll_language['current_lang']);

          if (! $is_posts_home && ! $has_translation && ! $is_current_language) {
            continue;
          }

          $language_url = add_query_arg('lang', $slug, $current_url);
          if (! empty($pll_language['url']) && ! $is_posts_home && $has_translation) {
            $language_url = $pll_language['url'];
          } elseif (function_exists('pll_home_url')) {
            $language_url = pll_home_url($slug) ?: $language_url;
          } elseif (! empty($pll_language['url'])) {
            $language_url = $pll_language['url'];
          }

          $language_items[] = [
            'slug' => $slug,
            'label' => $language_labels[$slug] ?? strtoupper($slug),
            'url' => $language_url,
          ];
        }
      }
    }

    if (empty($language_items)) {
      $language_items = [
        ['slug' => 'ru', 'label' => 'RU', 'url' => add_query_arg('lang', 'ru', $current_url)],
        ['slug' => 'en', 'label' => 'EN', 'url' => add_query_arg('lang', 'en', $current_url)],
        ['slug' => 'kz', 'label' => 'KZ', 'url' => add_query_arg('lang', 'kz', $current_url)],
      ];
    }
  @endphp

  <div class="site-header__inner">
    <a class="site-header__brand" href="{{ home_url('/') }}" aria-label="{{ get_bloginfo('name') }}">
      <span class="site-header__brand-name">Zan360</span>
    </a>

    <button
      class="site-header__toggle"
      type="button"
      aria-expanded="false"
      aria-controls="site-navigation"
      aria-label="{{ \App\theme_translate('Toggle navigation') }}"
    >
      <span></span>
      <span></span>
      <span></span>
    </button>

    <nav
      id="site-navigation"
      class="site-header__nav"
      aria-label="{{ wp_get_nav_menu_name('primary_navigation') ?: \App\theme_translate('Primary navigation') }}"
    >
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'container' => false,
          'menu_class' => 'site-header__menu',
          'fallback_cb' => false,
          'echo' => false,
        ]) !!}
      @else
        @php
          $header_categories = get_categories([
            'taxonomy' => 'category',
            'hide_empty' => true,
            'number' => 5,
            'orderby' => 'count',
            'order' => 'DESC',
          ]);
        @endphp
        @if (! empty($header_categories))
          <ul class="site-header__menu">
            @foreach ($header_categories as $header_category)
              <li>
                <a href="{{ get_category_link($header_category->term_id) }}">
                  {{ $header_category->name }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      @endif
      <div class="site-header__languages" aria-label="{{ \App\theme_translate('Language switcher') }}">
        @foreach ($language_items as $language_item)
          <a
            href="{{ esc_url($language_item['url']) }}"
            class="site-header__language {{ $active_language === $language_item['slug'] ? 'is-active' : '' }}"
            hreflang="{{ $language_item['slug'] }}"
            lang="{{ $language_item['slug'] }}"
          >
            {{ $language_item['label'] }}
          </a>
        @endforeach
      </div>
      @if (is_user_logged_in())
        <a href="{{ admin_url() }}" class="site-header__jobs">{{ \App\theme_translate('Account') }}</a>
      @else
        <a href="{{ wp_login_url(function_exists('pll_home_url') ? pll_home_url() : home_url('/')) }}" class="site-header__jobs">{{ \App\theme_translate('Log in') }}</a>
      @endif
    </nav>
  </div>
</header>
