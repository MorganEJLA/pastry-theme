<?php
get_header();

while (have_posts()) {
    the_post();
    ?>

    <!-- Page Banner -->
    <div class="page-banner">
        <div
            class="page-banner__bg-image"
            style="background-image: url(<?php
                $pageBannerImage = get_field('page_banner_image');
                echo esc_url($pageBannerImage['sizes']['pageBanner']);
            ?>)"
        ></div>

        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p><?php the_field('page_banner_subtitle'); ?></p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">

        <!-- Metabox -->
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <?php
                $theParent = wp_get_post_parent_id(get_the_ID());

                if ($theParent) { ?>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        Back to <?php echo get_the_title($theParent); ?>
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>

                <?php } else { ?>
                    <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('locale'); ?>">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        Locale Home
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>
                <?php } ?>
            </p>
        </div>

        <!-- Main Content -->
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <!-- CHILD LOCALE MINI-BANNERS -->
        <?php
        $childLocales = get_pages(array(
            'child_of'   => get_the_ID(),
            'post_type'  => 'locale',
            'sort_column'=> 'menu_order'
        ));

        if ($childLocales) { ?>
            <div class="child-locale-section">
                <h2 class="headline headline--medium">
                    Explore More in <?php the_title(); ?>
                </h2>

                <div class="child-locale-grid">
                    <?php foreach ($childLocales as $child) {
                        $banner   = get_field('page_banner_image', $child->ID);
                        $subtitle = get_field('page_banner_subtitle', $child->ID);
                        ?>
                        <div class="child-banner-wrapper">
                            <a class="child-banner" href="<?php echo get_permalink($child->ID); ?>">
                                <div class="child-banner__bg"
                                     style="background-image: url('<?php echo esc_url($banner['sizes']['pageBanner']); ?>');">
                                </div>

                                <div class="child-banner__content">
                                    <h3 class="child-banner__title">
                                        <?php echo esc_html($child->post_title); ?>
                                    </h3>

                                    <?php if ($subtitle) { ?>
                                        <p class="child-banner__subtitle">
                                            <?php echo esc_html($subtitle); ?>
                                        </p>
                                    <?php } ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <!-- FEATURED DESSERT EVENTS -->
        <?php
        $today = date('Ymd');

        $homepageEvents = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type'      => 'event',
            'meta_key'       => 'event_date',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'event_date',
                    'compare' => '>=',
                    'value'   => $today,
                    'type'    => 'NUMERIC'
                ),
                array(
                    'key'     => 'related_locales',
                    'compare' => 'LIKE',
                    'value'   => '"' . get_the_ID() . '"'
                )
            )
        ));

        if ($homepageEvents->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2>Featured Desserts in ' . get_the_title() . '</h2>';

            while ($homepageEvents->have_posts()) {
                $homepageEvents->the_post();
                get_template_part('template-parts/content-event');
            }
        }
        wp_reset_postdata();
        ?>

        <!-- PASTRY CASE (BY CATEGORY) -->
        <?php
        // 1. Get all Pastry Categories that have at least one post
        $categories = get_terms(array(
            'taxonomy'   => 'pastry_category', // your taxonomy slug
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true
        ));

        $has_pastries = false;

        foreach ($categories as $category) {

            // 2. Query Pastry Case items for this category + current locale
            $categoryPastryItems = new WP_Query(array(
                'posts_per_page' => -1,
                'post_type'      => 'pastry_case',
                'orderby'        => 'title',
                'order'          => 'ASC',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'pastry_category',
                        'field'    => 'slug',
                        'terms'    => $category->slug,
                    )
                ),
                'meta_query'     => array(
                    array(
                        'key'     => 'related_locales',
                        'compare' => 'LIKE',
                        'value'   => '"' . get_the_ID() . '"'
                    )
                )
            ));

            if ($categoryPastryItems->have_posts()) {

                // Open section + main heading once
                if (!$has_pastries) {
                    echo '<hr class="section-break">';
                    echo '<section class="pastry-case-section">';
                    echo '<h2 class="headline headline--small">More in the Pastry Case from ' . get_the_title() . '</h2>';
                    $has_pastries = true;
                } else {
                    echo '<hr class="section-break">';
                }

                // Category heading
                echo '<h3>' . esc_html($category->name) . '</h3>';

                // Cards grid
                echo '<ul class="pastry-cards">';

                while ($categoryPastryItems->have_posts()) {
                    $categoryPastryItems->the_post();
                    get_template_part('template-parts/content', 'pastry-card');
                }

                echo '</ul>';
            }

            wp_reset_postdata();
        }

        // Close pastry section if we opened it
        if ($has_pastries) {
            echo '</section>';
        }
        ?>

        <!-- PASTRY PROFESSORS -->
        <?php
        $relatedProfessors = new WP_Query(array(
            'post_type'      => 'professor',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => 'related_locales',
                    'compare' => 'LIKE',
                    'value'   => '"' . get_the_ID() . '"'
                )
            )
        ));

        if ($relatedProfessors->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--small">Pastry Professors from ' . get_the_title() . '</h2>';

            echo '<ul class="professor-cards">';
            while ($relatedProfessors->have_posts()) {
                $relatedProfessors->the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image"
                             src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    </a>
                </li>
            <?php }
            echo '</ul>';
        }

        wp_reset_postdata();
        ?>

    </div><!-- /.container.container--narrow.page-section -->

<?php
} // end while have_posts()

get_footer();
