<?php
get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A look back at desserts we\'ve highlighted in the past.'
)) ?>




<div class="container container--narrow page-section">
  <?php
    $today = date('Ymd');

    $pastEvents = new WP_Query(array(
      'paged' => get_query_var('paged', 1),
      'posts_per_page' => 3,
      'post_type' => 'event',
      'meta_key' => 'event_date', // ← UPDATE this if needed
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      'meta_query' => array(
        array(
          'key' => 'dessert_events', // ← UPDATE this if needed
          'compare' => '<',
          'value' => $today,
          'type' => 'NUMERIC'
        )
      )
    ));

    while($pastDesserts->have_posts()){
      $pastDesserts->the_post();
      get_template_part('template-parts/content-event') ?>



  <?php }

    echo paginate_links(array(
      'total' => $pastDesserts->max_num_pages
    ));
  ?>
</div>

<?php get_footer(); ?>
