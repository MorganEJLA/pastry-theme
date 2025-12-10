<?php
/**
 * Template part for displaying a Pastry Case item as a card.
 * Used on the single-locale.php template.
 */
?>

<li class="pastry-card">
  <a class="pastry-card__link" href="<?php the_permalink(); ?>">

    <div class="pastry-card__image-wrap">
      <?php if ( has_post_thumbnail() ) : ?>
        <img
          class="pastry-card__image"
          src="<?php the_post_thumbnail_url('large'); ?>"
          alt="<?php the_title_attribute(); ?>"
        >
      <?php endif; ?>

      <div class="pastry-card__overlay">
        <h3 class="pastry-card__title"><?php the_title(); ?></h3>

        <?php
        $related_locales = get_field('related_locales');
        if (!empty($related_locales)) {
          $locale_name = get_the_title($related_locales[0]);
        } else {
          $locale_name = '';
        }
        ?>
        <p class="pastry-card__meta"><?php echo esc_html($locale_name); ?></p>
      </div>
    </div>

  </a>
</li>
