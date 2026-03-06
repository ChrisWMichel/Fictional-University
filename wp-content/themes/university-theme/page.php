<?php 
    
    get_header();

    while(have_posts()) {
        the_post(); ?>
        
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
        <div class="page-banner__intro">
          <p>Dont forget to replace me later!</p>
        </div>
      </div>
    </div>

    <div class="container container--narrow page-section">

        
        <?php 
            if(wp_get_post_parent_id(get_the_ID())){ ?>
                <div class="metabox metabox--position-up metabox--with-home-link">
                    <p>
                    <a class="metabox__blog-home-link" href="<?= get_permalink($post->post_parent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($post->post_parent); ?> </a> <span class="metabox__main"><?php the_title(); ?></span>
                    </p>
                </div>
            <?php } ?>
        
      

      <?php
        $parent_id = wp_get_post_parent_id(get_the_ID()) ? wp_get_post_parent_id(get_the_ID()) : get_the_ID();
        $child_pages = get_pages(array('child_of' => $parent_id, 'parent' => $parent_id));
        if ($child_pages) { ?>
          <div class="page-links">
            <h2 class="page-links__title"><a href="<?= get_permalink($parent_id); ?>"><?= get_the_title($parent_id); ?></a></h2>
            <ul class="min-list">
              <?php wp_list_pages(array(
                'title_li' => NULL,
                'child_of' => $parent_id
              )); ?>
            </ul>
          </div>
      <?php } ?>

      <div class="generic-content">
        <?php the_content(); ?>
      </div>
    </div>
        
    <?php }
   
?>




<?php get_footer(); ?>