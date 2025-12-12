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

        // ARCHIVE TITLE FALLBACK: Use a more appropriate title if viewing an archive.
        if (is_archive()) {
            $args['title'] = post_type_archive_title('', false);
        }
    }

    // SUBTITLE
    if (!array_key_exists('subtitle', $args) || !$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    // PHOTO
    if (!array_key_exists('photo', $args) || !$args['photo']) {


        if (is_post_type_archive('pastry_case')) {

            $args['photo'] = get_theme_file_uri('/images/chocolatier-banner.jpg');


        } elseif (get_field('page_banner_image')) {
            $args['photo'] = get_field('page_banner_image')['sizes']['pageBanner'];

        } else {

            $args['photo'] = get_theme_file_uri('/images/chocolatier-banner.jpg');
        }
    }
    ?>

    <div class="page-banner">
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
      </div>

    <?php
}


// =====================================================================
// 3. ENQUEUE SCRIPTS AND STYLES
// =====================================================================
function pastry_theme_files() {
    wp_enqueue_script(
        'main-pastry-js',
        get_theme_file_uri('/build/index.js'),
        array('jquery'),
        '1.0',
        true
    );

    wp_localize_script('main-pastry-js', 'pastryData', array(
        'root_url' => get_site_url()
    ));

    // Google Fonts
    wp_enqueue_style(
        'custom-google-fonts',
        'https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap',
        array(),
        null
    );

    // Base Styles
    wp_enqueue_style(
        'pastry_base_styles',
        get_theme_file_uri('/build/index.css'),
        array(),
        '1.0'
    );

    // Main Styles
    wp_enqueue_style(
        'pastry_main_styles',
        get_theme_file_uri('/build/style-index.css'),
        array('pastry_base_styles'),
        '1.0'
    );

    // FontAwesome
    wp_enqueue_style(
        'font-awesome',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
    );

    /* ---------------------------------------
       LOAD SWIPER ONLY ON THE PROFESSORS PAGE
    ---------------------------------------- */
    if (is_page('professors')) {   // make sure the page slug is actually 'professors'
        // Swiper CSS
        wp_enqueue_style(
            'swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0'
        );

        // Swiper JS
        wp_enqueue_script(
            'swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0',
            true
        );
    }
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


}
add_action('pre_get_posts', 'pastry_adjust_queries');
// =================================================================================
// 6. SEARCH SYNONYMS (e.g. Azores → Açores)
// =================================================================================
add_filter('posts_search', 'pastry_synonym_search');
add_filter('posts_search', 'pastry_synonym_search');
function pastry_synonym_search($search) {

    global $wpdb;

    if (is_search() && !empty(get_query_var('s'))) {
        $original_query = get_query_var('s'); // Get the original term from the URL
        $sanitized_query = sanitize_text_field($original_query);
        // Add more synonyms as needed
        $synonyms = array(

    // Portugal + Islands
            'acores' => 'açores',
            'azores' => 'açores',
            'açores' => 'açores', // reinforce
            'madeira' => 'madeira',
            'funchal' => 'funchal',

            // France + Overseas
            'guadeloupe' => 'guadeloupe',
            'guadaloupe' => 'guadeloupe', // common typo
            'martinique' => 'martinique',
            'réunion' => 'reunion',
            'reunion' => 'réunion',

            // Spain
            'espana' => 'españa',
            'españa' => 'españa',
            'cataluna' => 'cataluña',
            'catalunya' => 'cataluña',

            // Italy + Sicily + Sardinia
            'sicilia' => 'sicily',
            'sicily' => 'sicilia',
            'sardegna' => 'sardinia',
            'sardinia' => 'sardegna',

            // Brazil
            'brasil' => 'brazil',

            // Caribbean
            'haiti' => 'haiti',
            'jamacia' => 'jamaica',
            'jamaica' => 'jamaica',
            'trinidad' => 'trinidad',
            'tobago' => 'trinidad & tobago',

            // Mexico & Latin America
            'mexico' => 'méxico',
            'méxico' => 'méxico',
            'el salvador' => 'el salvador',
            'salvador' => 'el salvador',
            'honduras' => 'honduras',
            'guatemala' => 'guatemala',
            'panama' => 'panamá',
            'panamá' => 'panamá',
            'colombia' => 'colombia',
            'columbia' => 'colombia', // common misspelling
            'ecuador' => 'ecuador',
            'venezuela' => 'venezuela',
            'argentina' => 'argentina',
            'paraguay' => 'paraguay',
            'uruguay' => 'uruguay',
            'chile' => 'chile',

            // Africa
            'cape verde' => 'cabo verde',
            'cabo verde' => 'cabo verde',
            'ivory coast' => 'côte d’ivoire',
            'cote divoire' => 'côte d’ivoire',
            'côte d’ivoire' => 'côte d’ivoire',
            'senegal' => 'sénégal',
            'sénégal' => 'sénégal',
            'kenya' => 'kenya',
            'nigeria' => 'nigeria',
            'ghana' => 'ghana',
            'ethiopia' => 'ethiopia',
            'mali' => 'mali',
            'seychelles' => 'seychelles',
            'mauritania' => 'mauritania',
            'somalia' => 'somalia',
            'angola' => 'angola',
            'south africa' => 'south africa',

            // Middle East
            'turkiye' => 'türkiye',
            'turkey' => 'türkiye',
            'türkiye' => 'türkiye',
            'uae' => 'united arab emirates',
            'dubai' => 'dubai',
            'palestine' => 'palestine',
            'israel' => 'israel',
            'syria' => 'syria',
            'lebanon' => 'lebanon',

            // Asia
            'china' => 'china',
            'beijing' => 'beijing',
            'shanghai' => 'shanghai',
            'guangzhou' => 'guangzhou',
            'hong kong' => 'hong kong',
            'japan' => 'japan',
            'tokyo' => 'tokyo',
            'osaka' => 'osaka',
            'korea' => 'south korea',
            'south korea' => 'south korea',
            'seoul' => 'seoul',
            'taiwan' => 'taiwan',
            'vietnam' => 'vietnam',
            'thailand' => 'thailand',
            'indonesia' => 'indonesia',
            'malaysia' => 'malaysia',
            'philippines' => 'philippines',
            'india' => 'india',
            'nepal' => 'nepal',
            'sri lanka' => 'sri lanka',

            // Europe (general + diaspora)
            'deutschland' => 'germany',
            'germany' => 'germany',
            'schweiz' => 'switzerland',
            'switzerland' => 'switzerland',
            'österreich' => 'austria',
            'austria' => 'austria',
            'hungary' => 'hungary',
            'poland' => 'poland',
            'ukraine' => 'ukraine',
            'belarus' => 'belarus',
            'scotland' => 'scotland',
            'wales' => 'wales',
            'ireland' => 'ireland',
            'england' => 'england',
            'uk' => 'united kingdom',
            'united kingdom' => 'united kingdom',
            'netherlands' => 'netherlands',
            'holland' => 'netherlands',
            'denmark' => 'denmark',
            'sweden' => 'sweden',
            'norway' => 'norway',
            'iceland' => 'iceland',
            'montenegro' => 'montenegro',
            'albania' => 'albania',
            'croatia' => 'croatia',
            'romania' => 'romania',
            'czech republic' => 'czechia',
            'czechia' => 'czechia',

            // North America
            'usa' => 'united states',
            'united states' => 'united states',
            'america' => 'united states',
            'new york' => 'new york',
            'brooklyn' => 'brooklyn',
            'los angeles' => 'los angeles',
            'la' => 'los angeles',
            'chicago' => 'chicago',
            'atlanta' => 'atlanta',
            'harlem' => 'harlem',
            'canada' => 'canada',
            'quebec' => 'québec',
            'québec' => 'québec',
            'first nations' => 'first nations',
            'alaska' => 'alaska',

            // Oceania
            'australia' => 'australia',
            'tasmania' => 'tasmania',
            'new zealand' => 'new zealand',
            'maori' => 'maori',

            // Diaspora (Jewish, Caribbean, African diaspora etc.)
            'ashkenazi' => 'ashkenazi jewish',
            'sephardi' => 'sephardi jewish',
            'mizrahim' => 'mizrahi jewish',
            'mizrahic' => 'mizrahi jewish',
            'gullah' => 'gullah geechee',
            'geechee' => 'gullah geechee',
            'creole' => 'creole',
            'cajun' => 'cajun'
        );

        // Find the correct replacement term
        $replacement_term = $original_query;
        if (array_key_exists(strtolower($sanitized_query), $synonyms)) {
            $replacement_term = $synonyms[strtolower($sanitized_query)];
        }

        // If the original term is different from the preferred term (i.e., we have a synonym)
        if ($original_query !== $replacement_term) {

            // 1. Find the search fragment in the SQL string. It looks like:
            // AND ((($wpdb->posts.post_title LIKE '%{$original_query}%') OR ...))

            // 2. Escape the terms for SQL safety
            $original_like = '%' . $wpdb->esc_like($original_query) . '%';
            $replacement_like = '%' . $wpdb->esc_like($replacement_term) . '%';

            // 3. Create a combined search condition: (Term LIKE 'Original%' OR Term LIKE 'Replacement%')
            // Note: We use the existing SQL structure for safety and portability.

            // The logic below assumes a standard WordPress search query structure.
            // We search for the original search condition and wrap it to include the synonym.

            // The default search condition to find/replace:
            $search_pattern = "LIKE '{$original_like}'";

            // The replacement search condition that includes both terms:
            $search_replacement = "LIKE '{$original_like}' OR {$wpdb->posts}.post_title LIKE '{$replacement_like}' OR {$wpdb->posts}.post_content LIKE '{$replacement_like}'";

            // Apply the replacement across the SQL string
            // We use str_replace to inject the OR condition into the existing AND conditions.
            $search = str_replace($search_pattern, $search_replacement, $search);

        }
    }

    return $search; // Always return the (potentially modified) SQL search fragment
}
function uds_enqueue_swiper_assets() {
    if ( is_page( 'professors' ) || is_page( 'artisans' ) ) {
        wp_enqueue_style(
            'swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
        );
        wp_enqueue_script(
            'swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            null,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'uds_enqueue_swiper_assets' );
?>
