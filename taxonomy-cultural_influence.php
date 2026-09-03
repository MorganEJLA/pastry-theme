<?php
get_header();

// Page banner for the current Cultural Influence term
$current_term = get_queried_object();

// ACF fields on taxonomy terms are read using the term object itself
// (or "taxonomy_termID" as a string) -- not get_the_ID(), which only
// works for posts.
$banner_image = get_field('influence_banner_image', $current_term);

$banner_args = array(
    'title'    => 'Cultural Influence: ' . $current_term->name,
    'subtitle' => 'Desserts shaped by ' . $current_term->name . '.'
);

if ($banner_image) {
    $banner_args['photo'] = $banner_image['sizes']['pageBanner'] ?? $banner_image['url'];
}

pageBanner($banner_args);
?>

<div class="container container--narrow page-section">

    <?php if ( have_posts() ) : ?>

        <section class="pastry-case-section" id="influence-<?php echo esc_attr( $current_term->slug ); ?>">

            <ul class="pastry-cards">
                <?php
                while ( have_posts() ) {
                    the_post();
                    get_template_part( 'template-parts/content', 'pastry-card' );
                }
                ?>
            </ul>

        </section>

    <?php else : ?>

        <p class="generic-content">No desserts are tagged with this influence yet.</p>

    <?php endif; ?>

</div>

<?php
get_footer();
