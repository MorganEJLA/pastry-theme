<?php
get_header();

// Call the page banner function for the top of the archive page
// Assuming 'pageBanner' is a custom function defined elsewhere in your theme
pageBanner(array(
    // The post_type_archive_title() function will output "Pastry Case"
    'title' => post_type_archive_title('', false),
    'subtitle' => 'Explore the entire collection of our delightful desserts.'
));
?>

<div class="container container--narrow page-section">

    <?php
    // --- START: CUSTOM CATEGORY GROUPING LOOP ---

    // 1. Get all Pastry Categories that have assigned posts
    $categories = get_terms(array(
        'taxonomy' => 'pastry_category',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => true
    ));

    $first_category = true; // Flag to control the initial <hr> and heading placement

    // 2. Loop through each Category found
    foreach ($categories as $category) {

        // 3. Query Pastry Case items for the CURRENT Category (no locale filter needed)
        $categoryPastryItems = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'pastry_case',
            'orderby' => 'title',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'pastry_category',
                    'field' => 'slug',
                    'terms' => $category->slug,
                )
            )
        ));

        // 4. Check if posts exist for this specific category
        if ($categoryPastryItems->have_posts()) {

            // Add a divider before every category block EXCEPT the first one
            if (!$first_category) {
                echo '<hr class="section-break">';
            }

            // Display the Category Name as a heading (e.g., "Cakes & Tarts")
            echo '<h3>' . esc_html($category->name) . '</h3>';

            // START: Correct container class to activate the grid CSS
            echo '<ul class="pastry-cards">';

            // Loop through and display the posts using the template part
            while ($categoryPastryItems->have_posts()) {
                $categoryPastryItems->the_post();
                get_template_part('template-parts/content', 'pastry-card');
            }

            echo '</ul>'; // CLOSE: Correct container class

            // After the first successful loop, set the flag to false
            $first_category = false;
        }

        // Always clean up after a custom WP_Query
        wp_reset_postdata();
    }

    // --- END: CUSTOM CATEGORY GROUPING LOOP ---
    ?>

    <?php
    // Pagination is not typically used when displaying all posts (-1) in a long list.
    // If you remove 'posts_per_page' => -1 above, you can uncomment this:
    // echo paginate_links();
    ?>

</div>

<?php
get_footer();
?>
