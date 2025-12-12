<?php
get_header();

// Custom banner for the Pastry Case page
pageBanner(array(
    'title'    => 'Pastry Case',
    'subtitle' => 'Explore the entire collection of our delightful desserts.'
));
?>

<div class="container container--narrow page-section">

  <?php
  // Optional: show any content you type in the Pastry Case page editor
  while ( have_posts() ) : the_post(); ?>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>
  <?php endwhile; ?>

  <?php
  // --- START: CUSTOM CATEGORY GROUPING LOOP ---

  // 1. Get all Pastry Categories that have assigned posts
  $categories = get_terms(array(
      'taxonomy'   => 'pastry_category',
      'orderby'    => 'name',
      'order'      => 'ASC',
      'hide_empty' => true,
  ));

  $first_category = true;

  // 2. Loop through each Category
  foreach ( $categories as $category ) {

      // 3. Query Pastry Case items for this Category
      $categoryPastryItems = new WP_Query(array(
          'posts_per_page' => -1,
          'post_type'      => 'pastry_case',
          'orderby'        => 'title',
          'order'          => 'ASC',
          'tax_query'      => array(
              array(
                  'taxonomy' => 'pastry_category',
                  'field'    => 'slug',
                  'terms'    => $category->slug,
              ),
          ),
      ));

      if ( $categoryPastryItems->have_posts() ) {

          if ( ! $first_category ) {
              echo '<hr class="section-break">';
          }
          ?>

          <section class="pastry-case-section" id="pastry-<?php echo esc_attr( $category->slug ); ?>">

            <h2 class="pastry-case-heading">
              <?php echo esc_html( strtolower( $category->name ) ); ?>
            </h2>

            <ul class="pastry-cards">
              <?php
              while ( $categoryPastryItems->have_posts() ) {
                  $categoryPastryItems->the_post();
                  get_template_part( 'template-parts/content', 'pastry-card' );
              }
              ?>
            </ul>

          </section>

          <?php
          $first_category = false;
      }

      wp_reset_postdata();
  }

  // --- END: CUSTOM CATEGORY GROUPING LOOP ---
  ?>

</div>

<?php
get_footer();
