<?php
get_header();

while (have_posts()) {
    the_post();

    // Grab related locales once so we can use them in multiple places
    $relatedLocales = get_field('related_locales'); // ACF relationship field
    ?>

    <!-- Page Banner -->
    <?php pageBanner(); ?>

    <div class="container container--narrow page-section">

        <!-- Metabox -->
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <?php
                if ($relatedLocales) {
                    // Use the first related locale in the metabox link
                    $locale = $relatedLocales[0];
                    ?>
                    <a class="metabox__blog-home-link" href="<?php echo get_the_permalink($locale); ?>">

                         <?php echo get_the_title($locale); ?>
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

            <?php
            // Regional Roots section (like on the Artisans single)
            if ($relatedLocales) {
                echo '<hr class="section-break">';
                echo '<h3 class="headline headline--smaller">Regional Roots</h3>';
                echo '<ul class="link-list min-list">';

                foreach ($relatedLocales as $locale) { ?>
                    <li>
                        <a href="<?php echo get_the_permalink($locale); ?>">
                            <?php echo get_the_title($locale); ?>
                        </a>
                    </li>
                <?php }

                echo '</ul>';
            }
            ?>
        </div>
    </div>

<?php }
get_footer();
