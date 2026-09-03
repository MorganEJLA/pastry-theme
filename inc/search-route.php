<?php

require get_theme_file_path('/inc/search-synonyms.php');

add_action('rest_api_init', 'pastryRegisterSearch');

function pastryRegisterSearch() {
  register_rest_route('pastry/v1', 'search', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'pastrySearchResults'
  ));
}

function pastrySearchResults($data) {

  global $wpdb;

  // =========================================================
  // 1. SANITIZE & NORMALIZE SEARCH TERM
  // =========================================================
  $term       = sanitize_text_field($data['term']);
  $searchTerm = strtolower($term);

  $synonyms = pastry_get_search_synonyms();
  if (array_key_exists($searchTerm, $synonyms)) {
    $searchTerm = $synonyms[$searchTerm];
  }

  $postTypes = array('post', 'page', 'pastry_case', 'professor', 'locale', 'journal');

  // =========================================================
  // 2. STANDARD QUERIES: title/content match + search_aliases meta
  // =========================================================
  $mainQuery = new WP_Query(array(
    'post_type' => $postTypes,
    's'         => $searchTerm,
  ));

  $aliasQuery = new WP_Query(array(
    'post_type'  => $postTypes,
    'meta_query' => array(
      array(
        'key'     => 'search_aliases',
        'value'   => $searchTerm,
        'compare' => 'LIKE'
      )
    )
  ));

  // =========================================================
  // 3. LOCALE-RELATIONSHIP QUERY
  //    If the term matches a locale (e.g. "Japan"), find every
  //    post whose related_locales ACF field points to that
  //    locale's post ID. related_locales is a Relationship
  //    field, so it's stored as a serialized array of post IDs
  //    -- a plain text search never finds it, we have to look
  //    up the locale post first, then match on its ID.
  // =========================================================
  $relatedPosts = array();

  // Title-only match, on purpose -- WP_Query's 's' parameter searches
  // title AND content by default, which meant searching "french"
  // matched Japan's locale page (since its body text mentions
  // "French techniques"), incorrectly pulling in every Japanese
  // dessert. A direct title LIKE query avoids that false positive.
  $likeTerm = '%' . $wpdb->esc_like($searchTerm) . '%';

  $localeIds = $wpdb->get_col($wpdb->prepare(
    "SELECT ID FROM {$wpdb->posts}
     WHERE post_type = 'locale'
       AND post_status = 'publish'
       AND post_title LIKE %s",
    $likeTerm
  ));

  if (!empty($localeIds)) {
    foreach ($localeIds as $localeId) {
      // Serialized relationship values store each ID as a quoted
      // string, e.g. s:2:"57";  -- matching on '"57"' (with quotes)
      // avoids accidentally matching "5" inside "57" or "570".
      // IMPORTANT: WP_Query automatically wraps LIKE values in %...%
      // for you, and escapes any % you include yourself (treating it
      // as a literal character, not a wildcard). Passing '%"103"%'
      // here caused double-wrapping and silently broke every match --
      // just wrap the ID in quotes and let WP add the wildcards.
      $quotedId = '"' . $localeId . '"';

      $relationshipQuery = new WP_Query(array(
        'post_type'      => array('post', 'professor', 'pastry_case'),
        'posts_per_page' => -1,
        'meta_query'     => array(
          'relation' => 'OR',
          array(
            'key'     => 'related_locales',
            'value'   => $quotedId,
            'compare' => 'LIKE'
          ),
          array(
            'key'     => 'related_locales',
            'value'   => $localeId,
            'compare' => '='
          )
        )
      ));

      if (!empty($relationshipQuery->posts)) {
        $relatedPosts = array_merge($relatedPosts, $relationshipQuery->posts);
      }
    }
  }

  // =========================================================
  // 4. CULTURAL INFLUENCE TAXONOMY QUERY
  //    "french" should also surface anything tagged with the
  //    "French Influence" term in Cultural Influences, even
  //    though the person searching has no idea that field
  //    exists. Taxonomy terms are matched directly (unlike
  //    related_locales, this is a real WP taxonomy, so tax_query
  //    handles it natively -- no manual ID/meta matching needed).
  // =========================================================
  $influenceTerms = get_terms(array(
    'taxonomy'   => 'cultural_influence',
    'name__like' => $searchTerm,
    'hide_empty' => false,
  ));

  $influencePosts = array();

  if (!is_wp_error($influenceTerms) && !empty($influenceTerms)) {
    $termIds = wp_list_pluck($influenceTerms, 'term_id');

    $influenceQuery = new WP_Query(array(
      'post_type'      => 'pastry_case',
      'posts_per_page' => -1,
      'tax_query'      => array(
        array(
          'taxonomy' => 'cultural_influence',
          'field'    => 'term_id',
          'terms'    => $termIds,
        )
      )
    ));

    if (!empty($influenceQuery->posts)) {
      $influencePosts = $influenceQuery->posts;
    }
  }

  // =========================================================
  // 5. REVERSE RELATIONSHIP
  //    If a matched post (e.g. "Queijada") has locales set in its
  //    related_locales field, resolve those IDs into locale posts
  //    and include them too -- so searching a dessert also surfaces
  //    the places it's actually tied to, not just keyword matches.
  // =========================================================
  $reverseLocalePosts = array();
  $directMatches = array_merge($mainQuery->posts, $aliasQuery->posts);

  foreach ($directMatches as $post) {
    if (!in_array(get_post_type($post), array('post', 'professor', 'pastry_case'))) {
      continue;
    }

    // get_field() returns the related posts already resolved
    // (as WP_Post objects, if the field's return format is "Post
    // Object") -- if your field's return format is "Post ID"
    // instead, this will return an array of IDs and the foreach
    // below needs get_post($localePost) instead of using it directly.
    $relatedLocales = get_field('related_locales', $post->ID);

    if (!empty($relatedLocales)) {
      foreach ($relatedLocales as $localePost) {
        // Normalize in case return format is IDs rather than objects
        if (!($localePost instanceof WP_Post)) {
          $localePost = get_post($localePost);
        }
        if ($localePost) {
          $reverseLocalePosts[] = $localePost;
        }
      }
    }
  }

  // =========================================================
  // 6. MERGE ALL SOURCES, DEDUPE
  // =========================================================
  $seen_ids     = array();
  $merged_posts = array();

  $allPosts = array_merge($mainQuery->posts, $aliasQuery->posts, $relatedPosts, $reverseLocalePosts, $influencePosts);

  foreach ($allPosts as $post) {
    if (!in_array($post->ID, $seen_ids)) {
      $seen_ids[]     = $post->ID;
      $merged_posts[] = $post;
    }
  }

  // =========================================================
  // 7. BUILD RESULTS ARRAY
  // =========================================================
  $results = array(
    'generalInfo' => array(),
    'pastry_case' => array(),
    'professors'  => array(),
    'locale'      => array(),
    'journal'     => array()
  );

  foreach ($merged_posts as $post) {
    setup_postdata($post);
    $postType = get_post_type($post);

    $postData = array(
      'title'     => get_the_title($post),
      'permalink' => get_permalink($post),
      'postType'  => $postType
    );

    switch ($postType) {
      case 'post':
      case 'page':
        $results['generalInfo'][] = $postData;
        break;
      case 'pastry_case':
        $results['pastry_case'][] = $postData;
        break;
      case 'locale':
        $results['locale'][] = $postData;
        break;
      case 'journal':
        $results['journal'][] = $postData;
        break;
      case 'professor':
        $results['professors'][] = $postData;
        break;
    }
  }

  wp_reset_postdata();

  return $results;
}
