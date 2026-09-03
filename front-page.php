<?php get_header(); ?>

<main>
  <div class="page-banner">

    <div class="page-banner__content container t-center c-white">
      <h1 class="page-banner__title">Universo da Doçura</h1>
      <div class="page-banner__intro">
        <p>Where desserts become stories — traditions, flavors, and the cultures that created them.</p>
      </div>
    </div>
  </div>

  <div class="locale-banner">
    <div class="locale-banner__image">
  <img
    src="<?php echo esc_url( get_theme_file_uri('/images/jun-weng-_83QYWgrQUc-unsplash.jpg') ); ?>"
    alt="Colorful assorted sweets and pastries displayed on a market stall"
    fetchpriority="high"
    class="locale-banner__img-el"
  >
  <span class="photo-credit">Photo: <a href="https://unsplash.com/photos/colorful-assorted-sweets-and-pastries-displayed-on-a-market-stall-_83QYWgrQUc" target="_blank" rel="noopener noreferrer">Jun Weng</a> / Unsplash</span>
</div>
    <div class="locale-banner__text">
      <span class="slide-label">Locales</span>
      <h2 class="locale-banner__headline">Sweetness Has an Address</h2>
      <a href="<?php echo get_post_type_archive_link('locale'); ?>" class="slide-link">Explore Locales </a>
    </div>

  </div>

  <div class="dessert-banner" style="background-image: url(<?php echo get_theme_file_uri('/images/caitlyn-de-wild-jyeJVxCTUgI-unsplash.jpg') ?>)">
     <span class="photo-credit">Photo: <a href="https://unsplash.com/photos/sliced-cake-on-white-ceramic-plate-jyeJVxCTUgI" target="_blank" rel="noopener noreferrer">Caitlyn de Wild</a> / Unsplash</span>

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
</main>

<?php get_footer(); ?>
