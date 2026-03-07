<?php get_header(); ?>

<?php pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'Latest events from our community.'
)); ?>

  <div>
    <div class="container container--narrow page-section">
      <?php 
        while(have_posts()) {
            the_post(); 
            get_template_part('template-parts/content-event'); 
         }
      ?>
    </div>
    <div class="container container--narrow">
        <?php echo paginate_links(); ?>
    </div>
  </div>
  
   <div class="container container--narrow">
        <p class="t-center no-margin"><a href="<?= site_url('/past-event'); ?>" class="btn btn--blue">View Past Events</a></p>
    </div>

<?php get_footer(); ?>