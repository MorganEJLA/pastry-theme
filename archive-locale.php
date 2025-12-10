<?php
get_header();

echo do_shortcode('[locale_slider]');
?>

<section id="browse-locales" class="all-locales-section">
  <div class="container container--narrow page-section">

    <h2 class="headline headline--medium">Browse all locales</h2>
    <p class="all-locales-tagline">
      Jump straight to any locale using search or the A–Z filter.
    </p>
        <a href="#locale-hero-top"
       class="btn btn--outline locales-back-to-hero">
      Back to featured locales
    </a>

    <!-- Search -->
    <div class="locale-search">
      <input
        type="text"
        id="locale-inline-search"
        placeholder="Search locales..."
        autocomplete="off"
      >
      <div id="locale-inline-suggestions" class="locale-search__suggestions"></div>
    </div>

    <!-- A–Z filter -->
    <div class="az-filter">
      <button type="button" data-letter="all" class="az-filter__btn is-active">All</button>
      <?php foreach (range('A','Z') as $letter): ?>
        <button type="button" class="az-filter__btn" data-letter="<?php echo $letter; ?>">
          <?php echo $letter; ?>
        </button>
      <?php endforeach; ?>
    </div>

    <?php
    $locale_query = new WP_Query([
      'post_type'      => 'locale',
      'posts_per_page' => -1,
      'orderby'        => 'title',
      'order'          => 'ASC'
    ]);
    ?>

    <?php if ($locale_query->have_posts()) : ?>
      <div class="locale-grid">
        <?php while ($locale_query->have_posts()) : $locale_query->the_post(); ?>
          <?php $title = get_the_title(); ?>
          <a
            href="<?php the_permalink(); ?>"
            class="locale-card"
            data-title="<?php echo strtolower(esc_attr($title)); ?>"
          >
            <div class="locale-card__image"
              style="background-image:url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'medium')); ?>')">
            </div>
            <h3 class="locale-card__title"><?php echo esc_html($title); ?></h3>
          </a>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p>No locales found.</p>
    <?php endif; ?>

  </div>
</section>
<!-- floating arrow button -->
<button id="scroll-to-hero"
        class="scroll-to-hero-btn"
        aria-label="Back to featured locales">
  ↑
</button>

<?php get_footer(); ?>
