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
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                <a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs </a> 
                <span class="metabox__main"><?php the_title(); ?></span>
                
            </div>
            <div class="generic-content" style="margin-bottom: 20px;">
                <?php the_content(); ?>
            </div>
            
            <?php 
            $homepageEvents = new WP_Query(array(
                'posts_per_page' => 2,
                'post_type' => 'event',
                'orderby' => 'meta_value',
                'meta_key' => 'event_date',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'event_date',
                        'compare' => '>=',
                        'value' => date('Ymd'),
                        'type' => 'NUMERIC'
                    ),
                    array(
                        'key' => 'related_program',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            ));
            if($homepageEvents->have_posts()): ?>
                <hr class="section-break">
                <h2 class="headline headline--medium">Upcoming Events for <?php the_title(); ?></h2>
            <?php
            while($homepageEvents->have_posts()) {
                $homepageEvents->the_post(); ?>
                <div class="event-summary" >
                    <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month"><?php 
                            $eventDate = new DateTime(get_field('event_date'));
                            echo $eventDate->format('M');
                        ?></span>
                        <span class="event-summary__day"><?php 
                            $eventDate = new DateTime(get_field('event_date'));
                            echo $eventDate->format('d');
                        ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <?= has_excerpt() ? wp_trim_words(get_the_excerpt(), 15) : wp_trim_words(get_the_content(), 15); ?>
                        <p><a class="gray nu" style="background-color: transparent;" href="<?php the_permalink(); ?>">Learn more &raquo;</a></p>
                    </div>
                    <div class="generic-content">
                        
                    </div>
                   
                </div>
                <?php } wp_reset_postdata(); 
            endif; ?>
        </div>

        
    <?php }
   
?>




<?php get_footer(); ?>