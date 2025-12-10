<?php

add_action('rest_api_init', 'pastryRegisterSearch');

function pastryRegisterSearch() {
  register_rest_route('pastry/v1', 'search', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'pastrySearchResults'
  ));
}

function pastrySearchResults($data) {

  // =========================================================
  // 1. SANITIZE & NORMALIZE SEARCH TERM
  // =========================================================
  $term = sanitize_text_field($data['term']);
  $searchTerm = strtolower($term);

  // Synonym dictionary (add as many as you want)
  $synonyms = array(
    'acores'  => 'azores',
    'açores'  => 'azores',
    'saint domingue' => 'haiti',
    'sao paulo' => 'são paulo',
    'sao tome' => 'são tomé',
    'belem' => 'belém',
    'mexico city' => 'ciudad de méxico',
    'malasada' => 'malasadas',
  );

  // Apply synonym replacement
  foreach ($synonyms as $variant => $canonical) {
    if ($searchTerm === $variant) {
      $searchTerm = $canonical;
    }
  }

  // =========================================================
  // 2. RUN WP QUERY USING NORMALIZED TERM
  // =========================================================
  $mainQuery = new WP_Query(array(
  'post_type' => array('post', 'page', 'event', 'pastry_case', 'locale', 'journal'),
  's' => $searchTerm,  // normalized term
  'meta_query' => array(
    'relation' => 'OR',
    array(
      'key' => 'search_aliases',
      'value' => $searchTerm,
      'compare' => 'LIKE'
    )
  )
));


  $results = array(
    'generalInfo' => array(),
    'event' => array(),
    'pastry_case' => array(),
    'locale' => array(),
    'journal' => array()
  );

  while ($mainQuery->have_posts()) {
    $mainQuery->the_post();

    $postType = get_post_type();

    $postData = array(
      'title' => get_the_title(),
      'permalink' => get_permalink(),
      'postType' => $postType
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

  return $results;
}
