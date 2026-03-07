<?php 
    
    get_header();

    while(have_posts()) {
        the_post(); ?>

        <?php pageBanner(); ?>

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
            $relatedProfessors = new WP_Query(array(
                'posts_per_page' => -1,
                'post_type' => 'professor',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'related_program',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            ));
            if($relatedProfessors->have_posts()): ?>
                <hr class="section-break">
                <h2 class="headline headline--medium">Professors in <?php the_title(); ?></h2>
            <?php
            echo '<ul class="professor-cards">';
            while($relatedProfessors->have_posts()) {
                $relatedProfessors->the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="A picture of <?php the_title(); ?>">
                        <span class="professor-card__name"><?php the_title(); ?></span>
                        
                    </a>
                </li>
                <?php }
                 echo '</ul>';
            endif; ?> 
           
            <?php wp_reset_postdata(); ?>
            
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
                $homepageEvents->the_post(); 
                get_template_part('template-parts/content-event'); 
                 } wp_reset_postdata(); 
            endif; ?>
        </div>
    <?php } 
   





  get_footer(); ?>