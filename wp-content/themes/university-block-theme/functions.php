<?php 
require get_theme_file_path('/includes/search-route.php');
require get_theme_file_path('/includes/like-route.php');

function university_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {
            return get_the_author();
        }
    ));
    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function() {
            return count_user_posts(get_the_author_meta('ID'), 'note');
        }
    ));
}

add_action('rest_api_init', 'university_custom_rest');


function pageBanner($args = array()) {
    if (!isset($args['title'])) {
    $args['title'] = get_the_title();
  }

  if (!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!isset($args['photo'])) {
    if (get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }

  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>
<?php
}

function university_theme_setup() {
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
     wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
          wp_enqueue_script('vue-js', 'https://unpkg.com/vue@3/dist/vue.global.js', array(), '3', true);
          wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery', 'vue-js'), '1.0', true);

          wp_localize_script('main-university-js', 'universityData', array(
                'root_url' => get_site_url(),
                'nonce' => wp_create_nonce('wp_rest')
            ));
    }
    
    function university_features() {
        register_nav_menu('headerMenuLocation', 'Header Menu Location');
        register_nav_menu('footerLocationOne', 'Footer Menu Location One');
        register_nav_menu('footerLocationTwo', 'Footer Menu Location Two');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_image_size('professorLandscape', 400, 260, true);
        add_image_size('professorPortrait', 480, 650, true);
        add_image_size('pageBanner', 1500, 350, true);
        add_editor_style('build/style-index.css');
        add_editor_style('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css','build/style-index.css', 'build/index.css');
        
    }

    function university_adjust_queries($query) {
        if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
            // We don't want to limit the number of programs displayed on the programs page.
            // We want to display all programs in ascending order by title.
            $query->set('post_type', 'program');
            $query->set('order', 'ASC');
            $query->set('orderby', 'title');
            $query->set('posts_per_page', -1); // -1 means no limit
        }
        if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', 'event_date');
            $query->set('order', 'ASC');
            $query->set('meta_query', array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => date('Ymd'),
                    'type' => 'numeric'
                )
            ));
        }
    }
    add_action('wp_enqueue_scripts', 'university_theme_setup');

    add_action('after_setup_theme', 'university_features');

    add_action('pre_get_posts', 'university_adjust_queries');

    // function universityMapKey($api) {
    //     $api['key'] = UNIVERSITY_MAP_KEY;
    //     return $api;
    // }

    //add_filter('acf/fields/google_map/api', 'universityMapKey');

    // Redirect subscriber accounts out of admin and onto homepage
    function redirectSubsToFrontend() {
        $ourCurrentUser = wp_get_current_user();
        if(count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
            wp_redirect(site_url('/'));
            exit;
        }
    }
    add_action('admin_init', 'redirectSubsToFrontend');

        function noSubAdminbar() {
        $ourCurrentUser = wp_get_current_user();
        if(count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
            show_admin_bar(false);
        }
    }
    add_action('wp_loaded', 'noSubAdminbar');

    // Customize login screen
    function ourLoginCSS() {
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
     wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }
    add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginUrl() {
    return site_url('/');
}
add_filter('login_headerurl', 'ourLoginUrl');

function ourLoginTitle() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'ourLoginTitle');

// force note posts to be private
function makeNotePrivate($data, $postarr) {
    if($data['post_type'] == 'note') {
        if(count_user_posts(get_current_user_id(), 'note') >= 5 && !$postarr['ID']) {
            die("You have reached your note limit.");
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if($data['post_type'] == 'note' && $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }
    return $data;
}
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function bannerBlock() {
    wp_register_script('BannerBlockScript', get_theme_file_uri('/build/banner.js'), array('wp-blocks', 'wp-element', 'wp-editor'), '1.0', true);
    wp_localize_script('BannerBlockScript', 'bannerData', array(
        'heroImageUrl' => get_theme_file_uri('/images/library-hero.jpg'),
        'programsUrl'  => get_post_type_archive_link('program')
    ));
    register_block_type('university-block-theme/banner', array(
        'editor_script'   => 'BannerBlockScript',
        'render_callback' => 'renderBannerBlock'
    ));
}

function renderBannerBlock() {
    ob_start(); ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo esc_url(get_theme_file_uri('/images/library-hero.jpg')); ?>)"></div>
        <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large">Welcome!</h1>
            <h2 class="headline headline--medium">This is the block theme banner.</h2>
            <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
            <a href="<?php echo esc_url(get_post_type_archive_link('program')); ?>" class="btn btn--large btn--blue">Find Your Major</a>
        </div>
    </div>
    <?php return ob_get_clean();
}
add_action('init', 'bannerBlock');