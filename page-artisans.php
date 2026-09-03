<?php
get_header();
pageBanner(array(
  'title'    => 'Artisans of Universo da Doçura',
  'subtitle' => 'Meet the artisans, storytellers, and dessert guardians of Universo da Doçura.'
));
?>
<main>
  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo site_url('/dessert-library'); ?>">
          <i class="fa fa-home" aria-hidden="true"></i> Back to Dessert Library
        </a>
        <span class="metabox__main"><?php the_title(); ?></span>
      </p>
    </div>
    <?php while ( have_posts() ) : the_post(); ?>
      <div class="generic-content">
        <?php the_content(); ?>
      </div>
    <?php endwhile; ?>

    <?php echo do_shortcode('[artisan_slider]'); ?>
  </div>
</main>

<?php get_footer(); ?>
