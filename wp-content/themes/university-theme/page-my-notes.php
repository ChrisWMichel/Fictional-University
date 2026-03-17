<?php 
    if(!is_user_logged_in()) {
        wp_redirect(esc_url(site_url('/')));
        exit;
    }
    
    get_header();

    while(have_posts()) {
        the_post();
        pageBanner();
        ?>
<style>
    .hidden {
        display: none;
    }
    .button-container {
    display: flex;
    justify-content: space-between;
    width: 100%; 
    align-items: center;
    padding: 10px 0; 
    }

</style>
    <div class="container container--narrow page-section">
        <div>
            <div class="create-note-link ">
                <a href="#" class="btn btn--blue">+ Create New Note</a>
            </div>
            <div class="create-note" style="display: none;">
                <h2 class="headline headline--medium">Create New Note</h2>
                <input placeholder="Note Title" id="new-note-title" class="note-title-field new-note-title">
                <textarea placeholder="Your note here..." id="new-note-body" class="note-body-field new-note-body"></textarea>
                <div class="button-container">
                    <button class="submit-note" id="new-note">Submit</button>
                    <span class="note-limit-message">Note limit reached: Delete an existing note to create a new one.</span>
                    <button class="cancel-note">Cancel</button>
                </div>
            </div>
        </div>
        <ul class="min-list link-list" id="my-notes">
        <?php 
            $userNotes = new WP_Query(array(
                'post_type' => 'note',
                'posts_per_page' => -1,
                'author' => get_current_user_id()
            ));

        ?>
        
        <?php
            if($userNotes->have_posts()) {
                while($userNotes->have_posts()) {
                    $userNotes->the_post(); ?>
                    <li data-id="<?php the_ID(); ?>">
                        <input class="note-title note-title-field" value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?>" readonly>
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                        <span class="delete-note"><i class="fa fa-trash" aria-hidden="true"></i></span>
                      
                        <textarea class="note-body note-body-field" readonly><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>
                       
                        <span class="update-note btn btn--blue btn--small"><i style="padding-right: 5px;" class="fa fa-arrow-right" aria-hidden="true"></i>Update</span>
                    </li>
                <?php }
                wp_reset_postdata();
            } else {
                echo '<p>You have no notes yet.</p>';
            } ?>
        </ul>
    </div>
    
    <?php }

    get_footer();
    ?>

    