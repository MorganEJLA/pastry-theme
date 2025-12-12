<?php
get_header();

pageBanner(array(
  'title'    => 'Pastry Professors',
  'subtitle' => 'Meet the artisans, storytellers, and dessert guardians of Universo da Doçura.'
));
?>

<div class="container container--narrow page-section">
<div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <a class="metabox__blog-home-link" href="<?php echo site_url('/dessert-library'); ?>">
        <i class="fa fa-home" aria-hidden="true"></i> Back to Dessert Library
      </a>
      <span class="metabox__main"><?php the_title(); ?></span>
    </p>
  </div>
  <?php
  
  while ( have_posts() ) : the_post(); ?>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>
  <?php endwhile; ?>

  <?php
  $professors = new WP_Query(array(
    'post_type'      => 'professor',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC'
  ));

  if ( $professors->have_posts() ) : ?>

    <div class="artisan-swiper-section">
      <h2 class="headline headline--medium">Pastry Professors</h2>

      <div class="swiper professorSwiper">
        <div class="swiper-wrapper">

          <?php
          while ( $professors->have_posts() ) :
            $professors->the_post();
            $portrait = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            $subtitle = get_field( 'professor_subtitle' );
          ?>
            <div class="swiper-slide artisan-card">
              <div class="artisan-card__image"
                   style="background-image:url('<?php echo esc_url( $portrait ); ?>');"></div>

              <div class="artisan-card__body">
                <h3><?php the_title(); ?></h3>
                <?php if ( $subtitle ) : ?>
                  <p><?php echo esc_html( $subtitle ); ?></p>
                <?php endif; ?>

                <a class="artisan-card__button" href="<?php the_permalink(); ?>">View</a>
              </div>
            </div>
          <?php endwhile; wp_reset_postdata(); ?>

        </div>

        <!-- scoped nav buttons so they don't fight the other Swiper -->
        <div class="swiper-button-prev professor-nav professor-nav--prev"></div>
        <div class="swiper-button-next professor-nav professor-nav--next"></div>

      </div>
    </div>

  <?php endif; ?>

</div><!-- .container -->

<script>
  document.addEventListener("DOMContentLoaded", function () {
    function initProfessorSwiper() {
      if (typeof Swiper === "undefined") {
        return setTimeout(initProfessorSwiper, 50);
      }

      new Swiper(".professorSwiper", {
        slidesPerView: 1.1,
        spaceBetween: 30,
        breakpoints: {
          768: { slidesPerView: 2.2 },
          1024: { slidesPerView: 3 }
        },
        navigation: {
          nextEl: ".professor-nav--next",
          prevEl: ".professor-nav--prev"
        }
      });
    }

    initProfessorSwiper();
  });
</script>

<?php get_footer(); ?>
