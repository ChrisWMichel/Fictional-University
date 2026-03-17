<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- <h1>
    <a href="<?php //echo site_url(); ?>">Fictional University</a>
</h1>
<p><?php //bloginfo('description'); ?></p>
<hr> -->

<header class="site-header">
      <div class="container" style="display: flex; align-items: center; margin: 0 20px;">
        <h2 style=flex:1;>
            <a href="<?= site_url(); ?>" style="text-decoration: none;  color: white;">Fictional University</a>
        </h2>
        <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
        <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
        <div class="site-header__menu group" style="display: flex; align-items: center; margin-left: auto;">
          <nav class="main-navigation">
            <?php 
              // wp_nav_menu(array(
              //     'theme_location' => 'headerMenuLocation'
              // )); 
            ?>
            <ul>
              <li class="<?= is_page('about-us') ? 'current-menu-item' : ''; ?>"><a href="<?= site_url('/about-us'); ?>">About Us</a></li>
              <li class="<?= is_post_type_archive('program') ? 'current-menu-item' : ''; ?>"><a href="<?= get_post_type_archive_link('program'); ?>">Programs</a></li>
              <li class="<?= (is_post_type_archive('event') || is_page('past-events')) ? 'current-menu-item' : ''; ?>"><a href="<?= get_post_type_archive_link('event'); ?>">Events</a></li>
              <li class="<?= is_post_type_archive('campus') ? 'current-menu-item' : ''; ?>"><a href="<?= get_post_type_archive_link('campus'); ?>">Campuses</a></li>
              <li class="<?= is_home() ? 'current-menu-item' : ''; ?>"><a href="<?= site_url('/blog'); ?>">Blog</a></li>
            </ul>

          </nav>

          <div class="site-header__util">
            <?php if(is_user_logged_in()): ?>
              <a href="<?= esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--dark-orange float-left push-right">My Notes</a>
                <a href="<?= wp_logout_url(); ?>" class="btn btn--small btn--orange float-left push-right btn--with-photo">
                  
                  <?php $avatarUrl = get_avatar_url(get_current_user_id(), array('size' => 60)); ?>
                  <?php if($avatarUrl): ?>
                  <span class='site-header__avatar'>
                    <img src="<?= $avatarUrl; ?>" alt="">
                  </span>
                  <?php endif; ?>
                  <span class="btn__text">Logout</span>
                </a>
            <?php else: ?>
              <a href="<?= wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
              <a href="<?= wp_registration_url(); ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
              <span class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
    </header>