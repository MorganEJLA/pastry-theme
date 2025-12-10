<?php
get_header();

pageBanner(array(
  'title' => 'Pastry Professors',
  'subtitle' => 'Meet the artisans, storytellers, and dessert guardians of Universo da Doçura.'
));
?>

<?php
$professors = new WP_Query(array(
  'post_type' => 'professor',
  'posts_per_page' => -1,
  'orderby' => 'menu_order',
  'order' => 'ASC'
));

if ($professors->have_posts()) { ?>
  <div class="artisan-swiper-section">
    <h2 class="headline headline--medium">Pastry Professors</h2>

    <div class="swiper professorSwiper"> <!-- FIXED NAME HERE -->
      <div class="swiper-wrapper">

        <?php while ($professors->have_posts()) {
            $professors->the_post();
            $portrait = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $subtitle = get_field('professor_subtitle');
        ?>

        <div class="swiper-slide artisan-card">
          <div class="artisan-card__image"
               style="background-image: url('<?php echo esc_url($portrait); ?>');"></div>

          <div class="artisan-card__body">
            <h3><?php the_title(); ?></h3>
            <?php if ($subtitle) { ?>
              <p><?php echo esc_html($subtitle); ?></p>
            <?php } ?>

            <a class="artisan-card__button" href="<?php the_permalink(); ?>">View</a>
          </div>
        </div>

        <?php } wp_reset_postdata(); ?>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

    </div>
  </div>
<?php } ?>

<script>
  const professorSwiper = new Swiper(".professorSwiper", { // FIXED NAME HERE
    slidesPerView: 1.2,
    spaceBetween: 30,
    breakpoints: {
      768: { slidesPerView: 2.2 },
      1024: { slidesPerView: 3 },
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    }
  });
</script>

<?php get_footer(); ?>
