<?php
    get_header();

    // The banner is called ONCE at the top of the search page.
    pageBanner(array(
        'title' => 'Search Results for: ' . esc_attr(get_search_query()),
        'subtitle' => 'You searched for: &ldquo;' . esc_html(get_search_query()) . '&rdquo;'
    ));
?>

<div class="container container--narrow page-section">

    <?php
    // Check if WordPress found any posts
    if(have_posts()){

        // --- START CONDITIONAL LIST WRAPPING ---

        // 1. We must rewind the post pointer to be able to loop through the results twice.
        //    Once to check the type, and once to display (though we'll use a single loop for efficiency).
        rewind_posts();
        $professor_list_open = false; // Flag to track if the <ul> tag is open

        while(have_posts()){
            the_post();

            // Check if we are starting a Professor list
            if (get_post_type() == 'professor' && !$professor_list_open) {
                echo '<ul class="professor-cards">';
                $professor_list_open = true;
            }

            // Check if we are switching *away* from a Professor post type
            // This is complex. For simplicity, we'll let the loop run and close the <ul> tag afterwards.

            // Load the template part (this outputs the <li> for professors, or the <div> for others)
            get_template_part('template-parts/content', get_post_type());

            // If the current post type is NOT professor, we need to close the <ul>
            if (get_post_type() != 'professor' && $professor_list_open) {
                echo '</ul>';
                $professor_list_open = false;
            }

            // To handle a Professor result followed by a non-Professor result,
            // the closing logic below is safer for the end of the results.
        }

        // Final check: Close the professor list if the loop ended with a professor post
        if ($professor_list_open) {
            echo '</ul>';
        }

        // --- END CONDITIONAL LIST WRAPPING ---

        // Display pagination links at the bottom (Now correctly placed AFTER all results and wrappers)
        echo paginate_links();

    } else {
        // Display if no results were found
        ?>
        <p>No results match that search term. Please try a different search or check your spelling.</p>
        <?php
    }
    ?>

    <div class="generic-content">
        <hr class="section-break">
        <form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
            <label class="headline headline--medium" for="s">Perform a New Search:</label>
            <div class="search-form-row">
                <input placeholder = "What are you looking for?" class="s"id ="s" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>">
                <input class="search-submit" type="submit" value="Search">
            </div>
        </form>
    </div>

</div> <?php get_footer(); ?>
