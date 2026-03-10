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
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            );
        }
         if(get_post_type() == 'professor') {
            $postResults['professors'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            );
        }
         if(get_post_type() == 'program') {
            $postResults['programs'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID()
            );
        }
         if(get_post_type() == 'event') {
            $eventDate = get_field('event_date');
            if($eventDate >= date('Ymd')) {
                $postResults['events'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'eventMonth' => date('M', strtotime($eventDate)),
                    'eventDay' => date('d', strtotime($eventDate)),
                    'excerpt' => wp_trim_words(get_the_excerpt(), 18)
                );
            }
        }
         if(get_post_type() == 'campus') {
            $postResults['campuses'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            );
        }
    }
    wp_reset_postdata();

    if($postResults['programs']) {
        foreach($postResults['programs'] as $program) {
            // Professors related to this program
            $programRelationshipQuery = new WP_Query(array(
                'post_type' => 'professor',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'related_program',
                        'compare' => 'LIKE',
                        'value' => '"' . $program['id'] . '"'
                    )
                )
            ));

            while($programRelationshipQuery->have_posts()) {
                $programRelationshipQuery->the_post();
                $postResults['professors'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                );
            }
            wp_reset_postdata();

            // Upcoming events related to this program
            $eventRelationshipQuery = new WP_Query(array(
                'post_type' => 'event',
                'posts_per_page' => -1,
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
                        'value' => '"' . $program['id'] . '"'
                    )
                )
            ));

            while($eventRelationshipQuery->have_posts()) {
                $eventRelationshipQuery->the_post();
                $eventDate = get_field('event_date');
                $postResults['events'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'eventMonth' => date('M', strtotime($eventDate)),
                    'eventDay' => date('d', strtotime($eventDate)),
                    'excerpt' => wp_trim_words(get_the_excerpt(), 18)
                );
            }
            wp_reset_postdata();
        }
    }

    $postResults['professors'] = array_values(array_unique($postResults['professors'], SORT_REGULAR));
    $postResults['events'] = array_values(array_unique($postResults['events'], SORT_REGULAR));
    
    return $postResults;
}