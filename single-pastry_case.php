<?php
get_header();

while (have_posts()) {
    the_post();
    ?>

    <!-- Page Banner -->
   <?php pageBanner(); ?>

    <div class="container container--narrow page-section">

        <!-- Metabox -->
       <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <?php
                // 1. Get the array of related Locales (***CONFIRM FIELD NAME***)
                //    Assuming your ACF Relationship field name is 'related_locale'
                $relatedLocales = get_field('related_locale');

                if ($relatedLocales) {
                    // ACF returns an array of post objects. Use the first one found.
                    $locale = $relatedLocales[0];
                    ?>
                    <a class="metabox__blog-home-link" href="<?php echo get_the_permalink($locale); ?>">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        Locale: <?php echo get_the_title($locale); ?>
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>

                <?php } else { ?>
                    <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('pastry_case'); ?>">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        Back to Pastry Case Archive
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>
                <?php } ?>
            </p>
        </div>

        <!-- Main Content -->
        <div class="generic-content">
            <?php the_content(); ?>
        </div>
<?php }
get_footer();
?>
