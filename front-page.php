<?php get_header(); ?>

<div class="page-banner">

  <div class="page-banner__content container t-center c-white">
    <h1 class="page-banner__title">Universo da Doçura</h1>
    <div class="page-banner__intro">
      <p>Where desserts become stories — traditions, flavors, and the cultures that created them.</p>
    </div>
  </div>
</div>

<div class="locale-banner">
  <div class="locale-banner__image" style="background-image: url(<?php echo get_theme_file_uri('/images/azores-background-scaled.jpg') ?>)">
  </div>
  <div class="locale-banner__text">
    <span class="slide-label">Locales</span>
    <h2 class="locale-banner__headline">Sweetness Has an Address</h2>
    <a href="<?php echo get_post_type_archive_link('locale'); ?>" class="slide-link">Explore Locales </a>
  </div>
</div>

<div class="dessert-banner" style="background-image: url(<?php echo get_theme_file_uri('/images/caitlyn-de-wild-jyeJVxCTUgI-unsplash.jpg') ?>)">
  <div class="dessert-banner__content">
    <span class="slide-label">Desserts</span>
    <h2 class="slide-headline">History in Every Slice</h2>
    <a href="<?php echo get_post_type_archive_link('pastry_case'); ?>" class="slide-link">View Desserts →</a>
  </div>
</div>

<div class="artisan-banner">
  <div class="artisan-banner__text">
    <span class="slide-label">Artisans</span>
    <h2 class="artisan-banner__headline">Every Recipe Has a Keeper</h2>
    <a href="<?php echo site_url('/dessert-library/artisans/'); ?>" class="slide-link">Meet the Artisans</a>
  </div>
  <div class="artisan-banner__image" style="background-image: url(<?php echo get_theme_file_uri('/images/artisan-background.png') ?>)">
  </div>
</div>

<?php get_footer(); ?>


<?php get_footer(); ?>
