<?php
  // Load the ACF Event Date field
  $rawDate = get_field('dessert_events');

  // Create a DateTime object safely
  if ($rawDate) {
    try {
      $eventDate = new DateTime($rawDate);
    } catch (Exception $e) {
      // Fallback if the date fails to parse
      $eventDate = new DateTime();
    }
  } else {
    // Fallback if no date is set
    $eventDate = new DateTime();
  }
?>

<div class="event-summary">
  <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
    <span class="event-summary__month"><?php echo $eventDate->format('M'); ?></span>
    <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
  </a>

  <div class="event-summary__content">
    <h5 class="event-summary__title headline headline--tiny">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h5>

    <p>
      <?php
        if (has_excerpt()) {
          the_excerpt();
        } else {
          echo wp_trim_words(get_the_content(), 18);
        }
      ?>
      <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
    </p>
  </div>
</div>
