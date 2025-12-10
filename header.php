<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
      <meta charset = "<?php bloginfo('charset'); ?>">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
      <header class="site-header">
  <div class="container">

    <h1 class="school-logo-text float-left">
      <a href="<?php echo site_url()?>">Universo da Doçura</a>
    </h1>

    <!-- MOBILE search icon (shows on small screens, hidden at >= 960px) -->
    <a href="<?php echo esc_url(site_url('/search')); ?>"
       class="site-header__search-trigger js-search-trigger">
      <i class="fa fa-search" aria-hidden="true"></i>
    </a>

    <!-- MOBILE hamburger icon -->
    <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>

    <!-- Desktop menu -->
    <div class="site-header__menu group">

      <nav class="main-navigation">
        <ul>
          <li class="<?php if (is_page('about-us') || wp_get_post_parent_id(get_the_ID()) == 16) echo 'current-menu-item'; ?>">
            <a href="<?php echo site_url('/about-us'); ?>">About Us</a>
          </li>

          <li class="<?php if (is_page('dessert-library')) echo 'current-menu-item'; ?>">
            <a href="<?php echo site_url('/dessert-library'); ?>">Dessert Library</a>
          </li>

          <li class="<?php if (get_post_type() == 'locale') echo 'current-menu-item'; ?>">
            <a href="<?php echo get_post_type_archive_link('locale'); ?>">Locales</a>
          </li>

          <li class="<?php if (get_post_type() == 'journal' || is_home() || is_singular('post')) echo 'current-menu-item'; ?>">
            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">Journal</a>
          </li>
        </ul>
      </nav>

      <div class="site-header__util">
        <a href="#" class="btn btn--small btn--orange float-left push-right">Login</a>
        <a href="#" class="btn btn--small btn--dark-orange float-left">Sign Up</a>

        <!-- DESKTOP search icon (hidden on mobile, shows at >= 960px) -->
        <a href="<?php echo esc_url(site_url('/search')); ?>"
           class="search-trigger js-search-trigger">
          <i class="fa fa-search" aria-hidden="true"></i>
        </a>
      </div>

    </div><!-- end menu container -->

  </div>
</header>


    </body>
</html>
