<?php

/*
Plugin Name: Quiz CM Plugin
Description: A plugin to create and manage quizzes for the University Theme.
Version: 1.0
Author: Chris Michel
Author URI: https://chris-michel.dev/
*/

if(! defined('ABSPATH')) exit; // Exit if accessed directly

class QuizCMPlugin {
    public function __construct() {
        add_action('init', array($this, 'adminAssets'));
    }

    public function adminAssets() {
        $frontend_asset = require plugin_dir_path(__FILE__) . 'build/frontend.asset.php';
        $admin_asset = require plugin_dir_path(__FILE__) . 'build/index.asset.php';

        wp_register_style('quiz-cm-css', plugin_dir_url(__FILE__) . 'build/index.css', array(), $admin_asset['version']);
        wp_register_style('quiz-cm-frontend-css', plugin_dir_url(__FILE__) . 'build/frontend.css', array(), $frontend_asset['version']);
        wp_register_script('quiz-cm-admin', plugin_dir_url(__FILE__) . 'build/index.js', $admin_asset['dependencies'], $admin_asset['version'], true);
        wp_register_script('quiz-cm-frontend', plugin_dir_url(__FILE__) . 'build/frontend.js', $frontend_asset['dependencies'], $frontend_asset['version'], true);

        register_block_type('quiz-cm-plugin/quiz-block', array(
            'editor_script' => 'quiz-cm-admin',
            'editor_style'  => 'quiz-cm-css',
            'render_callback' => array($this, 'renderQuizBlock'),
        ));
    }

    public function renderQuizBlock($attributes) {
        wp_enqueue_script('quiz-cm-frontend');
        wp_enqueue_style('quiz-cm-frontend-css');
        ob_start();
        ?>
        <div class="paying-attention-quiz-update">
             <pre style="display:none;"><?= wp_json_encode($attributes); ?></pre>
        </div>
        <?php
        return ob_get_clean();
    }
}

$quizCMPlugin = new QuizCMPlugin();