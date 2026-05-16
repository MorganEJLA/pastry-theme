<footer class="site-footer">
  <div class="site-footer__inner container container--narrow">
    <div class="group">
      <!-- Logo -->
      <div class="site-footer__col-one">
        <h1 class="school-logo-text school-logo-text--alt-color">
          <a href="<?php echo site_url(); ?>">Universo da Doçura</a>
        </h1>
      </div>

      <!-- Navigation -->
      <div class="site-footer__col-two-three-group">
        <div class="site-footer__col-two">
          <h3 class="headline headline--small">Explore</h3>
          <nav class="nav-list">
            <ul>
              <li class="<?php if (is_page('journal') || is_home()) echo 'current-menu-item'; ?>">
                <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">Journal</a>
              </li>
              <li>
                <a href="<?php echo site_url('/dessert-library/artisans/'); ?>">Artisans</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>

      <!-- Social Links -->
      <div class="site-footer__col-four">
        <h3 class="headline headline--small">Connect</h3>
        <nav>
          <ul class="min-list social-icons-list group">
            <li>
              <a href="#" class="social-icon">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
                <span class="sr-only">LinkedIn</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
