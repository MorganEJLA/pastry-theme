
<?php
get_header();

while(have_posts()){
    the_post(); ?>
    <?php pageBanner(); ?>
    <div class="container container--narrow page-section">

        <div class="generic-content">
        <div class="row group">
            <div class="one-third">
                <?php the_post_thumbnail('professorPortrait'); ?>
            </div>
            <div class="two-thirds">
                <?php the_content(); ?>
            </div>
        </div>


        <?php


          $relatedLocales = get_field('related_locales');
          if($relatedLocales){
             echo '<hr class="section-break">';
          echo '<h3 class="headline headline--smaller">Regional Roots</h3>';
          echo '<ul class="link-list min-list">';
          foreach($relatedLocales as $locale){?>
          <li><a href="<?php echo get_the_permalink($locale); ?>"><?php
            echo get_the_title($locale); ?></a></li>
          <?php }
          echo '</ul>';
          }
          ?>
    </div>
    </div>



    <?php }
    get_footer();
    ?>
