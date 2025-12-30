
<?php
get_header(); ?>
 <div class="page-banner">
      <div
        class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('/images/cuba-banner.jpg') ?>)"
      ></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">Featured Events</h1>
        <div class="page-banner__intro">
          <p>
            What featured events have we celebrated recently?
          </p>
        </div>
      </div>
    </div>
    <div class="container container--narrow page-section">
      <?php while(have_posts()){
    the_post();
    get_template_part('template-parts/content-event');
      }
    echo paginate_links();
  ?>

  <hr class="section-break">
  <p>
    Curious about past featured events?
    <a href="<?php echo site_url('/past_events'); ?>">Explore the archive</a>.
  </p>
</div>

<?php get_footer(); ?>
