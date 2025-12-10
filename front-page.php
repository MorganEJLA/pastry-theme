


<?php get_header(); ?>

  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/macaron-fr.jpeg') ?>)"></div>
    <div class="page-banner__content container t-center c-white">
      <h1 class="headline headline--large">Universo da Doçura</h1>
      <h2 class="headline headline--medium">Where desserts become stories.</h2>
      <h3 class="headline headline--small">Discover traditions, flavors, and the cultures that created them.</h3>
      <a href="<?php echo get_post_type_archive_link('locale'); ?>" class="btn btn--large btn--blue">Start Exploring</a>
    </div>
  </div>

  <div class="full-width-split group">
    <div class="full-width-split__one">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">Featured Events</h2>
        <?php
  $today = date('Ymd');
  $homepageEvents = new WP_Query(array(
    'posts_per_page' => 2,
    'post_type' => 'event',
    'meta_key' => 'dessert_events', // FIXED
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => array(
      array(
        'key' => 'dessert_events', // FIXED
        'compare' => '>=',
        'value' => $today,
        'type' => 'NUMERIC'
      )
    )
  ));

  while($homepageEvents->have_posts()){
    $homepageEvents->the_post();
    get_template_part('template-parts/content-event');
  }
  wp_reset_postdata();
?>

        <p class="t-center no-margin">
          <a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a>
        </p>
      </div>
    </div>

    <div class="full-width-split__two">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">From the Journal</h2>

        <?php
          $homepagePosts = new WP_Query(array(
            'posts_per_page' => 2
          ));
          while($homepagePosts->have_posts()){
            $homepagePosts->the_post();  ?>
            <div class="event-summary">
              <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month"><?php the_time('M'); ?></span>
                <span class="event-summary__day"><?php the_time('d'); ?></span>
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h5>
                <p><?php echo wp_trim_words(get_the_content(), 18); ?>
                  <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a>
                </p>
              </div>
            </div>
          <?php } wp_reset_postdata();
        ?>

        <p class="t-center no-margin">
          <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn btn--yellow">View All Journal Entries</a>
        </p>
      </div>
    </div>
  </div>

 <div class="hero-slider">
  <div data-glide-el="track" class="glide__track">
    <div class="glide__slides">

      <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('/images/belgian-pralines-belg.jpg') ?>)">
        <div class="hero-slider__interior container">
          <div class="hero-slider__overlay">
            <h2 class="headline headline--medium t-center">A World of Flavor</h2>
            <p class="t-center">Explore desserts that connect continents through sugar, spice, and story.</p>
            <p class="t-center no-margin">
              <a href="<?php echo get_post_type_archive_link('locale'); ?>" class="btn btn--blue">Explore locales</a>
            </p>
          </div>
        </div>
      </div>

      <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('/images/kaak-warka-tunisia.jpeg') ?>)">
        <div class="hero-slider__interior container">
          <div class="hero-slider__overlay">
            <h2 class="headline headline--medium t-center">Craft and Culture</h2>
            <p class="t-center">From Tunisian pastries to Caribbean sweets, tradition finds new form in every kitchen.</p>
            <p class="t-center no-margin">
              <a href="<?php echo get_post_type_archive_link('pastry_case'); ?>" class="btn btn--blue">View Desserts</a>
            </p>
          </div>
        </div>
      </div>

      <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('/images/malasadas-portugal.jpg') ?>)">
        <div class="hero-slider__interior container">
          <div class="hero-slider__overlay">
            <h2 class="headline headline--medium t-center">Learning Through Dessert</h2>
            <p class="t-center">Universo da Doçura began as a way to learn development by tracing the history of sweets.</p>
            <p class="t-center no-margin">
              <a href="<?php echo get_permalink(get_page_by_title('About Us')); ?>" class="btn btn--blue">Our Story</a>
            </p>
          </div>
        </div>
      </div>

    </div>
    <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
  </div>
</div>

<?php get_footer(); ?>
