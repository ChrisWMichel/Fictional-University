<?php 
    
    get_header();

    while(have_posts()) {
        the_post(); 
        
        pageBanner();
        ?>

       
        <div class="container container--narrow page-section">
            <!-- <div class="generic-content">
                <?php //if(has_post_thumbnail()) { ?>
                    <div class="professor__image">
                        <?php //the_post_thumbnail('professorLandscape'); ?>
                    </div>
                <?php //} ?>
                <?php //the_content(); ?>
            </div> -->
            <div class="generic-content">
                <div class="row group">
                    <div class="one-third">
                        <?php if(has_post_thumbnail()) { ?>
                            <div class="professor__image">
                                <?php the_post_thumbnail('professorPortrait'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="two-thirds">
                        <?php 
                            $likeCount = new WP_Query(array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_professor_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            ));

                            $existStatus = 'no';

                            $existQuery = new WP_Query(array(
                                'author' => get_current_user_id(),
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_professor_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            ));

                            if($existQuery->found_posts) {
                                $existStatus = 'yes';
                            }
                        ?>
                        <span class='like-box' data-exists="<?php echo $existStatus; ?>" data-professor="<?php echo get_the_ID(); ?>">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <span class="like-count">
                                <?php echo $likeCount->found_posts; ?>
                            </span>
                        </span>
                        <span>
                            <?php the_content(); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <?php $relatedPrograms = get_field('related_program') ?>
            <?php if($relatedPrograms): ?>
                <hr class="section-break">
                <h2 class="headline headline--medium">Subject(s) Taught</h2>
                <ul class="link-list min-list">
                    <?php foreach($relatedPrograms as $program): ?>
                        <li><a href="<?php echo get_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
    <?php }
   
?>




<?php get_footer(); ?>