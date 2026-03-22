<?php

/*
    Plugin Name: Word Filter
    Description: A plugin to filter out bad words from content.
    Version: 1.0
    Author: Chris Michel
    Author URI: https://chris-michel.dev/
*/

    if(! defined('ABSPATH')) exit; // Exit if accessed directly

    class WordFilterPlugin {
        //private $bad_words = array('badword1', 'badword2', 'badword3'); // Add your bad words here

        public function __construct() {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'subSettings'));
            if(get_option('word_filter_bad_words')) {
                add_filter('the_content', array($this, 'filter_content'));
            }
        }

        public function add_admin_menu() {
          $mainPageHook =  add_menu_page(
                'Words To Filter',
                'Word Filter',
                'manage_options',
                'word-filter-settings',
                array($this, 'render_settings_page'),
                'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+',
                100
            );

            add_submenu_page('word-filter-settings', 'Words To Filter', 'Words List', 'manage_options', 'word-filter-settings', array($this, 'render_settings_page'));

            add_submenu_page(
                'word-filter-settings',
                'Settings',
                'Settings',
                'manage_options',
                'word-filter-settings-sub',
                array($this, 'settingsSubPage')
            );

            add_action("load-$mainPageHook", array($this, 'load_settings'));
        }

        public function load_settings() {
            wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) . 'admin.css');
        }

        public function filter_content($content) {
            $bad_words = get_option('word_filter_bad_words', array());
            if (!empty($bad_words)) {
                foreach ($bad_words as $bad_word) {
                    $content = str_ireplace($bad_word, esc_html(get_option('replacement_text', '****')), $content);
                }
            }
            return $content;
        }

        public function handleForm() {
            if (isset($_POST['word_filter_settings']) && check_admin_referer('word_filter_settings_action', 'word_filter_settings_nonce')) {
                $words = sanitize_text_field($_POST['word_filter_settings']);
                $word_array = array_map('trim', explode(',', $words));
                update_option('word_filter_bad_words', $word_array);
                echo '<div class="updated"><h3>Your filtered words were saved.</h3></div>';
            } else {
                echo '<div class="error"><h3>Sorry, you do not have permission to perform this action.</h3></div>';
            }
        }

        public function render_settings_page() {
            ?>
            <div class="wrap">
                <h1>Word Filter Settings</h1>
                <?php if (isset($_POST['justsubmitted']) == "true")  $this->handleForm();  ?>

                
                <form method="post" >
                    <input type="hidden" name="justsubmitted" value="true">
                    <?php wp_nonce_field('word_filter_settings_action', 'word_filter_settings_nonce'); ?>
                    <label for="word_filter_settings"><p><p>Enter a <strong>comma-separated</strong> list of words to filter out from content.</p></p></label>
                    <div class="word-filter__flex-container">
                        <textarea name="word_filter_settings" id="word_filter_settings" placeholder="Enter words separated by commas"><?php
                                $bad_words = get_option('word_filter_bad_words', array());
                                if (!empty($bad_words)) {
                                    echo implode(', ', $bad_words);
                                }
                            ?></textarea>
                    </div>
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
                    
                </form>
            </div>
            <?php
        }

        public function subSettings() {
            add_settings_section('replacement_text_section', null, null, 'word-filter-settings-sub');
            register_setting('replacement_fields', 'replacement_text');
             add_settings_field('word_filter_bad_words', 'Bad Words List', array($this,'badWordsFieldHTML'), 'word-filter-settings-sub', 'replacement_text_section');
        }

        public function badWordsFieldHTML() {
            $replacement_text = get_option('replacement_text', '****');
            ?>
            <input type="text" name="replacement_text" value="<?php echo esc_attr($replacement_text); ?>" class="regular-text">
            <p class="description">Leave blank to remove the filtered words.</p>
            <?php
        }

        public function settingsSubPage() {
            ?>
            <div class="wrap">
                <h1>Word Filter Options</h1>
                <form action="options.php" method="post">
                    <?php
                    settings_errors(); // Show settings errors if there are any
                    settings_fields('replacement_fields');
                    do_settings_sections('word-filter-settings-sub');
                        submit_button();
                    ?>
                </form>
            </div>
            <?php
        }
    }

    $wordFilterPlugin = new WordFilterPlugin();