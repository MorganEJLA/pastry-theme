<?php
// =================================================================================
// 1. REQUIRE FILES & REST API CUSTOMIZATION
// =================================================================================
require get_theme_file_path('/inc/search-route.php');

function pastry_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {
            return get_the_author();
        }
    ));
}
add_action('rest_api_init', 'pastry_custom_rest');


// =================================================================================
// 2. PAGE BANNER FUNCTION (Including Pastry Archive Logic)
// =================================================================================
function pageBanner($args = []) {

    if (!is_array($args)) {
        $args = [];
    }

    // TITLE
    if (!array_key_exists('title', $args) || !$args['title']) {
        $args['title'] = get_the_title();

        // ARCHIVE TITLE FALLBACK
        if (is_archive()) {
            $args['title'] = post_type_archive_title('', false);
        }
    }

    // SUBTITLE
    if (!array_key_exists('subtitle', $args) || !$args['subtitle']) {

        // Optional: special field for desserts, falls back to normal subtitle
        if (is_singular('pastry_case') && get_field('dessert_tagline')) {
            $args['subtitle'] = get_field('dessert_tagline');
        } else {
            $args['subtitle'] = get_field('page_banner_subtitle');
        }
    }

    // PHOTO
    if (!array_key_exists('photo', $args) || !$args['photo']) {

        if (is_post_type_archive('pastry_case')) {

            // Archive hero image
            $args['photo'] = get_theme_file_uri('/images/macaron-new.jpg');

        } elseif (is_singular('pastry_case') && has_post_thumbnail()) {

            // 🔸 Single pastry: use featured image by default
            $args['photo'] = get_the_post_thumbnail_url(get_the_ID(), 'pageBanner');

        } elseif (get_field('page_banner_image')) {

            // Generic page banner image (ACF)
            $args['photo'] = get_field('page_banner_image')['sizes']['pageBanner'];

        } else {

            // Global fallback
            $args['photo'] = get_theme_file_uri('/images/macaron-new.jpg');
        }
    }

    // 🔸 Extra class for pastry singles
    $extra_class = '';
    if (is_singular('pastry_case')) {
        $extra_class = ' page-banner--pastry';
    }
    ?>

    <div class="page-banner<?php echo $extra_class; ?>">
      <div
        class="page-banner__bg-image"
        style="background-image: url(<?php echo esc_url($args['photo']); ?>)"
      ></div>

      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo wp_kses_post($args['title']); ?></h1>

        <?php if ($args['subtitle']) : ?>
          <div class="page-banner__intro">
            <p><?php echo wp_kses_post($args['subtitle']); ?></p>
          </div>
        <?php endif; ?>
      </div>
      <?php
  $thumbnail_id = get_post_thumbnail_id();
  $caption = wp_get_attachment_caption($thumbnail_id);
  if ($caption) : ?>
    <p class="page-banner__photo-credit"><?php echo esc_html($caption); ?></p>
  <?php endif; ?>
    </div>

    <?php
}



// =====================================================================
// 3. ENQUEUE SCRIPTS AND STYLES
// =====================================================================
function pastry_jquery_footer() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.min.js' ), array(), null, true );
    }
}
add_action( 'wp_enqueue_scripts', 'pastry_jquery_footer', 1 );

function pastry_theme_files() {
    wp_enqueue_script(
        'main-pastry-js',
        get_theme_file_uri('/build/index.js'),
        array('jquery'),
        '1.0',
        true
    );

    wp_localize_script('main-pastry-js', 'pastryData', array(
    'root_url'       => get_site_url(),
    'searchSynonyms' => pastry_get_search_synonyms()
));

    // Google Fonts
  wp_enqueue_style(
    'custom-google-fonts',
    'https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap',
    array(),
    null
    );


    // Main Styles
    wp_enqueue_style(
        'pastry_main_styles',
        get_theme_file_uri('/build/style-index.css'),
        array(),
        '1.0'
    );

    // FontAwesome
    wp_enqueue_style(
        'font-awesome',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
    );


}

add_action('wp_enqueue_scripts', 'pastry_theme_files');


// =================================================================================
// 4. THEME SUPPORT AND IMAGE SIZES
// =================================================================================
function pastry_theme_features(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'pastry_theme_features');


// =================================================================================
// 5. QUERY ADJUSTMENTS
// =================================================================================
function pastry_adjust_queries($query){
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('locale')) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
     if (!is_admin() && $query->is_main_query() && is_tax('cultural_influence')) {
        $query->set('posts_per_page', -1);
    }

}
add_action('pre_get_posts', 'pastry_adjust_queries');
// =================================================================================
// 6. SEARCH SYNONYMS (e.g. Azores → Açores)
// =================================================================================
require get_theme_file_path('/inc/search-synonyms.php');

add_filter('posts_search', 'pastry_synonym_search');

function pastry_synonym_search($search) {

  global $wpdb;

  if (is_search() && !empty(get_query_var('s'))) {
    $original_query  = get_query_var('s');
    $sanitized_query = sanitize_text_field($original_query);

    $synonyms        = pastry_get_search_synonyms();
    $replacement_term = $original_query;

    if (array_key_exists(strtolower($sanitized_query), $synonyms)) {
      $replacement_term = $synonyms[strtolower($sanitized_query)];
    }

    if ($original_query !== $replacement_term) {
      $original_like    = '%' . $wpdb->esc_like($original_query) . '%';
      $replacement_like = '%' . $wpdb->esc_like($replacement_term) . '%';

      $search_pattern     = "LIKE '{$original_like}'";
      $search_replacement = "LIKE '{$original_like}' OR {$wpdb->posts}.post_title LIKE '{$replacement_like}' OR {$wpdb->posts}.post_content LIKE '{$replacement_like}'";

      $search = str_replace($search_pattern, $search_replacement, $search);
    }
  }

  return $search;
}

function pastry_related_desserts_prompt() {
    echo '<div class="container container--narrow">
        <p class="generic-content">Enjoyed this pastry? Explore more from this region.</p>
    </div>';
}
add_action('pastry_after_case_content', 'pastry_related_desserts_prompt');

// =================================================================================
// 7. AI DISCLOSURE NOTICE
// =================================================================================
add_filter('the_content', 'pastry_ai_disclosure_notice');

function pastry_ai_disclosure_notice(string $content): string {
  if (is_singular(array('post', 'page', 'pastry_case', 'locale', 'journal', 'professor')) && in_the_loop() && is_main_query()) {
    $notice = '<div class="ai-disclosure-notice">
      <p><em>This content was created with the assistance of AI. Sources are currently being reviewed.</em></p>
    </div>';

    $content .= $notice;
  }

  return $content;
}

?>
