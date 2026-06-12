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

  // =========================================================
  // 1. SANITIZE & NORMALIZE SEARCH TERM
  // =========================================================
  $term       = sanitize_text_field($data['term']);
  $searchTerm = strtolower($term);

  // Apply synonym replacement
  $synonyms = pastry_get_search_synonyms();
  if (array_key_exists($searchTerm, $synonyms)) {
    $searchTerm = $synonyms[$searchTerm];
  }

  // =========================================================
  // 2. RUN TWO QUERIES AND MERGE RESULTS
  // =========================================================

  // Query 1: standard title/content search
  $mainQuery = new WP_Query(array(
    'post_type' => array('post', 'page', 'event', 'pastry_case', 'locale', 'journal'),
    's'         => $searchTerm,
  ));

  // Query 2: search_aliases meta field
  $aliasQuery = new WP_Query(array(
    'post_type'  => array('post', 'page', 'event', 'pastry_case', 'locale', 'journal'),
    'meta_query' => array(
      array(
        'key'     => 'search_aliases',
        'value'   => $searchTerm,
        'compare' => 'LIKE'
      )
    )
  ));

  // Merge, avoid duplicates
  $seen_ids     = array();
  $merged_posts = array();

  foreach (array_merge($mainQuery->posts, $aliasQuery->posts) as $post) {
    if (!in_array($post->ID, $seen_ids)) {
      $seen_ids[]     = $post->ID;
      $merged_posts[] = $post;
    }
  }

  // =========================================================
  // 3. BUILD RESULTS ARRAY
  // =========================================================
  $results = array(
    'generalInfo' => array(),
    'event'       => array(),
    'pastry_case' => array(),
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
      case 'event':
        $results['event'][] = $postData;
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
    }
  }

  wp_reset_postdata();

  return $results;
}
