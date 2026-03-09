<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults($data){

    $allposts = new WP_Query(array(
        'post_type' => array('post', 'professor', 'page', 'program', 'campus', 'event'),
        'posts_per_page' => -1,
        's' => sanitize_text_field($data['term'] ?? '')
    ));
    $postResults = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while($allposts->have_posts()) {
        $allposts->the_post();
        if(get_post_type() == 'post' || get_post_type() == 'page') {
            $postResults['generalInfo'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorPortrait'),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            );
        }
         if(get_post_type() == 'professor') {
            $postResults['professors'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorPortrait')
            );
        }
         if(get_post_type() == 'program') {
            $postResults['programs'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorPortrait')
            );
        }
         if(get_post_type() == 'event') {
            $eventDate = get_field('event_date');
            if($eventDate >= date('Ymd')) {
                $postResults['events'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorPortrait')
                );
            }
        }
         if(get_post_type() == 'campus') {
            $postResults['campuses'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorPortrait')
            );
        }
    }
    wp_reset_postdata();
    
    return $postResults;
}