<?php get_header(); ?>

<?php pageBanner(array(
    'title' => 'All Programs',
    'subtitle' => 'We offer many programs.'
)); ?>

    <div class="container container--narrow page-section">
        <div style="margin-bottom: 20px;">
            <?php get_search_form(); ?>
        </div>
      <?php 
        while(have_posts()) {
            the_post(); ?>
            <div class="post-item">
                <h2 class="link-list min-list"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <!-- <div class="metabox">
                    <p>Posted by <?php //the_author_posts_link(); ?> on <?php //the_time('n.j.y'); ?> in <?php //echo get_the_category_list(', '); ?></p>
                </div> -->
            </div>
        <?php }
      ?>
    </div>
    <div class="container container--narrow">
        <?php echo paginate_links(); ?>
    </div>
  </div>
  

<?php get_footer(); ?>