<?php

    add_action('rest_api_init', 'universityRegisterLike');

    function universityRegisterLike() {
        register_rest_route('university/v1', '/manageLike', array(
            'methods' => 'POST',
            'callback' => 'createLike'
        ));

        register_rest_route('university/v1', '/manageLike', array(
            'methods' => 'DELETE',
            'callback' => 'deleteLike'
        ));
    }

    function createLike($data) {
        $data = sanatize_text_field($data['professorId']);        
        if(is_user_logged_in()) {

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $data
                )
            )

        ));

            if($existQuery->found_posts == 0 && get_post_type($data) == 'professor') {
                return wp_insert_post(array(
                    'post_type' => 'like',
                    'post_status' => 'publish',
                    'post_title' => get_current_user_id() . '-' . $data,
                    'meta_input' => array(
                    'liked_professor_id' => $data
                    )
                ));
            } else {
                die("Invalid professor ID or you have already liked this professor.");
            }

            
        } else {
            die("Only logged in users can like.");
        }  
    }

    function deleteLike($data) {
        $likeID = sanatize_text_field($data['like']);

        if($likeID && get_post_type($likeID) == 'like' && get_current_user_id() == get_post_field('post_author', $likeID)) {
            wp_delete_post($data['like'], true);            
        } else {
            die("Invalid like ID or you have not liked this professor.");
        }
    }