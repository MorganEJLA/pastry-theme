<footer class="site-footer">
  <div class="site-footer__inner container container--narrow">
    <div class="group">
      <!-- Logo / Contact -->
      <div class="site-footer__col-one">
        <h1 class="school-logo-text school-logo-text--alt-color">
          <a href="<?php echo site_url(); ?>">Universo da Doçura</a>
        </h1>
        <p><a class="site-footer__link" href="tel:5555555555">555.555.5555</a></p>
      </div>

      <!-- Navigation Columns -->
      <div class="site-footer__col-two-three-group">
        <div class="site-footer__col-two">
          <h3 class="headline headline--small">Explore</h3>
          <nav class="nav-list">
            <ul>
              <li class="<?php
                if (
                  get_post_type() == 'event' ||
                  is_post_type_archive('event') ||
                  is_singular('event') ||
                  is_page('past_events')
                ) echo 'current-menu-item';
              ?>">
                <a href="<?php echo get_post_type_archive_link('event'); ?>">Featured Desserts</a>
              </li>

              <li class="<?php if (is_page('passport')) echo 'current-menu-item'; ?>">
                <a href="<?php echo site_url('/passport'); ?>">Passport</a>
              </li>

              <li class="<?php if (is_page('journal') || is_home()) echo 'current-menu-item'; ?>">
                <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">Journal</a>
              </li>


            </ul>
          </nav>
        </div>
      </div>

      <!-- Social Links -->
      <div class="site-footer__col-four">
        <h3 class="headline headline--small">Connect With Us</h3>
        <nav>
          <ul class="min-list social-icons-list group">
            <li>
              <a href="#" class="social-color-facebook">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
            </li>
            <li>
              <a href="#" class="social-color-linkedin">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
              </a>
            </li>
            <li>
              <a href="#" class="social-color-instagram">
                <i class="fa fa-instagram" aria-hidden="true"></i>
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
