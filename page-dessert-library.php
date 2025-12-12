<?php
/*
 * Template Name: Dessert Library Template
 * Template Post Type: page
 */

get_header();

while(have_posts()){
    the_post();
    pageBanner();
?>

<div class="container container--narrow page-section">

<?php
  // --- PARENT LINK/METABOX ---
  $theParent = wp_get_post_parent_id(get_the_ID());
  if($theParent) { ?>

    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"
        ><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a><span class="metabox__main"><?php the_title(); ?></span>
      </p>
    </div>
      <?php }
?>
<?php
// --- CHILD/SIBLING NAVIGATION ---
// Check if the page has children OR if it has a parent (needs sibling links)
$testArray = get_pages(array(
  'child_of' => get_the_ID()
));
if ($theParent or $testArray) { ?>
  <div class="page-links">
    <h2 class="page-links__title"><a href="<?php
    // Link to the parent page title, or the current page if it's top-level
    echo get_permalink($theParent ?: get_the_ID());
    ?>"><?php
    echo get_the_title($theParent ?: get_the_ID());
    ?>
    </a></h2>
    <ul class="min-list">
      <?php
      // Determine the root page to list children/siblings from
      if($theParent) {
        $findChildrenOf = $theParent;
      } else {
        $findChildrenOf = get_the_ID();
      }
      wp_list_pages(array(
        'title_li' => NULL,
        'child_of' => $findChildrenOf,
        'sort_column' => 'menu_order'
      ));
      ?>
    </ul>
  </div>
  <?php } ?>

  <div class="generic-content">

   <?php the_content(); // Displays content entered in the WordPress editor ?>

  <p class="t-center" style="margin-top: 20px; margin-bottom: 40px;">
    <a href="<?php echo site_url('/dessert-library/pastry-case/'); ?>"
        class="btn btn--large btn--blue">
        Explore the Entire Pastry Case
    </a>
</p>



   </div>
</div>

<?php
get_footer();
}
?>
