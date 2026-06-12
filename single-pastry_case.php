<?php
get_header();

while (have_posts()) {
    the_post();

    $relatedLocales = get_field('related_locales');
    ?>

    <?php pageBanner(); ?>

    <div class="container container--narrow page-section">

        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <?php
                if ($relatedLocales) {
                    $locale = $relatedLocales[0];
                    ?>
                    <a class="metabox__blog-home-link" href="<?php echo get_the_permalink($locale); ?>">
                        <?php echo get_the_title($locale); ?>
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>
                <?php } else { ?>
                    <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('pastry_case'); ?>">
                        <i class="fa fa-home" aria-hidden="true"></i> Back to Pastry Case
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>
                <?php } ?>
            </p>
        </div>

        <div class="generic-content">
            <?php
            $content = apply_filters('the_content', get_the_content());
            $paragraphs = explode('</p>', $content);
            $first = isset($paragraphs[0]) ? $paragraphs[0] . '</p>' : '';
            array_shift($paragraphs);
            $rest = implode('</p>', $paragraphs);

            $quill = get_field('quill_image');
            $fallback = get_template_directory_uri() . '/images/quill-default.png';
            $src = $quill ? esc_url($quill['url']) : esc_url($fallback);
            $alt = $quill ? esc_attr($quill['alt']) : 'Quilled illustration';
            ?>

            <div class="row group">
                <div class="one-third">
                    <img src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" />
                </div>
                <div class="two-thirds">
                    <?php echo $first; ?>
                </div>
            </div>

            <?php echo $rest; ?>

            <?php
            $influences = get_the_terms(get_the_ID(), 'cultural_influence');
            if ($influences && !is_wp_error($influences)) : ?>
                <ul class="influence-tags">
                    <?php foreach ($influences as $term) : ?>
                        <li class="influence-tags__tag"><?php echo esc_html($term->name); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php
            if ($relatedLocales) {
                echo '<hr class="section-break">';
                echo '<h3 class="regional-roots-heading">Regional Roots</h3>';
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

    <?php do_action('pastry_after_case_content'); ?>
<?php }

get_footer();
