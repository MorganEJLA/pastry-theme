<?php
/*
Template Name: Passport Under Construction
*/
get_header();
?>
<style>
  .passport-banner {
    background: var(--wp--preset--color--p-primary-base, #CFE9E4);
    color: var(--wp--preset--color--p-contrast-dark, #8C6A5E);
    text-align: center;
    padding: 8rem 2rem;
  }

  .passport-banner h1 {
    font-family: 'Abril Fatface', serif;
    font-size: clamp(2rem, 6vw, 5rem);
    margin-bottom: 1.5rem;
    color: var(--wp--preset--color--p-contrast-dark, #8C6A5E);
  }

  .passport-banner p {
    font-size: 1.125rem;
    line-height: 1.6;
    max-width: 700px;
    margin: 0 auto 2rem;
  }

  .btn--pistachio {
    display: inline-block;
    background: var(--wp--preset--color--p-accent-1, #C4D4B6);
    color: var(--wp--preset--color--p-contrast-dark, #8C6A5E);
    padding: 0.75rem 1.75rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .btn--pistachio:hover {
    background: var(--wp--preset--color--p-highlight, #FBE7BE);
    color: #000;
  }

  .passport-banner img {
    display: block;
    margin: 0 auto 2rem;
    max-width: 140px;
  }
</style>
<div class="page-banner">
  <div class="page-banner__content container container--narrow t-center">
    <img src="<?php echo get_theme_file_uri('/images/logo.svg'); ?>" alt="Pastry Pass Logo" style="max-width: 160px; margin: 0 auto 2rem;">
    <h1 class="headline headline--large">Coming Soon</h1>
    <p class="headline headline--small">
      This page is currently under construction as we prepare your <strong>Pastry Pass</strong> — a digital passport to the world’s sweetest traditions.
    </p>
    <p><a href="#" class="btn btn--pistachio">Preview the App</a></p>
  </div>
</div>

<?php get_footer(); ?>
