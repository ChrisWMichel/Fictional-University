<?php

/*
Plugin Name: Word Count
Description: A plugin to count words in posts.
Version: 1.1
Author: Chris Michel
Author URI: https://chris-michel.dev/
Text Domain: cm-word-count
Domain Path: /languages
*/

class WordCountPlugin {
    public function __construct() {
         add_filter('the_content', array($this, 'ifWrap'));
         add_action('admin_menu', array($this, 'adminPage'));
         add_action('admin_init', array($this, 'settings'));
         add_action('init', array($this, 'loadTextDomain'));
    }

    public function loadTextDomain() {
        load_plugin_textdomain('cm-word-count', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function settings() {
        add_settings_section('wcp_first_section', null, null, 'word_count_settings');

        add_settings_field('titleLocation', 'Display Location', array($this, 'locationHTML'), 'word_count_settings', 'wcp_first_section');
        register_setting('word_count_settings', 'titleLocation', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitizeLocation'),
            'default' => '0',
        ));

        add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word_count_settings', 'wcp_first_section');
        register_setting('word_count_settings', 'wcp_headline', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitizeHeadline'),
            'default' => 'Post Statistics',
        ));

        add_settings_field('wcp_word_count', 'Word Count', array($this, 'wordCountHTML'), 'word_count_settings', 'wcp_first_section');
        register_setting('word_count_settings', 'wcp_word_count', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1',
        ));

        add_settings_field('wcp_character_count', 'Character Count', array($this, 'characterCountHTML'), 'word_count_settings', 'wcp_first_section');
        register_setting('word_count_settings', 'wcp_character_count', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1',
        ));

        add_settings_field('wcp_read_time', 'Read Time', array($this, 'readTimeHTML'), 'word_count_settings', 'wcp_first_section');
        register_setting('word_count_settings', 'wcp_read_time', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '0',
        ));
    }
    public function sanitizeHeadline($input) {
        $sanitized = sanitize_text_field($input);
        return $sanitized !== '' ? $sanitized : 'Post Statistics';
    }

    public function sanitizeLocation($input) {
        $valid = array('0', '1');
        if (in_array($input, $valid)) {
            return $input;
        }
        return '0';
    }

    function ifWrap($content) {
        if (is_single() && is_main_query()) {
            $wordCount = str_word_count(strip_tags($content));
            $characterCount = strlen(strip_tags($content));
            $readTime = ceil($wordCount / 200); // Assuming an average reading speed of 200 words per minute
            $headline = esc_html(get_option('wcp_headline', 'Post Statistics'));

            $headlineTitle = '<h3 style="margin-bottom: 0px;">' . $headline . ':</h3>';
            $output = '<div style="padding-top: 0px; padding-bottom: 20px;" ><p>';
            if (get_option('wcp_word_count', '1') === '1') {
                $output .= esc_html__('This post has', 'cm-word-count') . '  <strong>' . $wordCount . '</strong> ' . esc_html__('words', 'cm-word-count') . '. <br>';
            }
            if (get_option('wcp_character_count', '1') === '1') {
                $output .= esc_html__('There are', 'cm-word-count') . '  <strong>' . $characterCount . '</strong> ' . esc_html__('characters', 'cm-word-count') . '. <br>';
            }
            if (get_option('wcp_read_time', '0') === '1') {
                $output .= esc_html__('It will take about', 'cm-word-count') . ' <strong>' . $readTime . '</strong> ' . esc_html__('minutes to read', 'cm-word-count') . '. <br>';
            }
            $output .= '</p></div>';
            $headlineTitle .= $output;

            if (get_option('titleLocation', '0') === '1') {
                return $headlineTitle . $content;
            } else {
                return $content . $headlineTitle;
            }
           
        }
        return $content;
    }

    public function readTimeHTML() {
        $readTimeText = get_option('wcp_read_time', '0');
        // unchecked by default, so we check if the value is '1' to set the checkbox as checked
        ?>
        <input type="checkbox" name="wcp_read_time" id="wcp_read_time" value="1" <?php checked($readTimeText, '1'); ?> />
        <?php
    }
    public function characterCountHTML() {
        $characterCountText = get_option('wcp_character_count', '1');
        ?>
        <input type="checkbox" name="wcp_character_count" id="wcp_character_count" value="1" <?php checked($characterCountText, '1'); ?> />
        <?php
    }
    public function wordCountHTML() {
        $wordCountText = get_option('wcp_word_count', '1');
        ?>
        <input type="checkbox" name="wcp_word_count" id="wcp_word_count" value="1" <?php checked($wordCountText, '1'); ?> />
        <?php
    }
    public function headlineHTML() {
        $headline = get_option('wcp_headline', 'Post Statistics');
        ?>
        <input type="text" name="wcp_headline" id="wcp_headline" value="<?= esc_attr($headline); ?>" />
        <?php
    }
    public function locationHTML() {
        $option = get_option('titleLocation', '0');
        ?>
        <select name="titleLocation" id="titleLocation">
            <option value="0" <?php selected($option, '0'); ?>>End of Post</option>
            <option value="1" <?php selected($option, '1'); ?>>Beginning of Post</option>
        </select>
        <?php
    }

    // public function countWords($content) {
    //     $wordCount = str_word_count(strip_tags($content));
    //     $option = get_option('titleLocation', '0');
    //     if ($option === '1') {
    //         return '<p><strong>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . ':</strong> ' . $wordCount . '</p>' . $content;
    //     }
    //     return $content . '<p><strong>Word Count:</strong> ' . $wordCount . '</p>';
    // }

    function adminPage() {
     add_options_page('Word Count Settings', __('Word Count', 'cm-word-count'), 'manage_options', 'word-count-settings', array($this, 'html'));
    }

    // Define the plugin settings page content.
    function html() { ?>
       <div class="wrap">
         <h1><?php echo __('Word Count Settings', 'cm-word-count'); ?></h1>
        <p><?php echo __('This plugin counts the number of words in your posts and displays it at the end of each post.', 'cm-word-count'); ?></p>
        <p><?php echo __('To use this plugin, simply activate it and it will automatically count the words in your posts and display the count at the end of each post.', 'cm-word-count'); ?></p>
        <form action="options.php" method="post">
            <?php
            settings_fields('word_count_settings');
            do_settings_sections('word_count_settings');
            submit_button();
            ?>
        </form>
       </div>
    <?php }
}

// Initialize the plugin.
$wordCountPlugin = new WordCountPlugin();