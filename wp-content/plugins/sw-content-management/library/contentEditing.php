<?php

/** 
 * Add specific editing function and options to Wordpress.
 * Add clean text options to Wordpress
 * @TODO: Filter text before it is saved (ingore linebreaks etc.)
 * @TODO: Give editors a message if something is changed
 * @TODO: Remove HTML bar / Only for non-admins, see on_init function
 * @TODO: Build option that review to Studio Wolf can be disabled, Zorg ervoor dat de contact persoon de reviewmail krijgt
 * @TODO: Quality link ter review "Waarom?"
 * @TODO: Review mail: send mail met afzender van de loggedin user
 */ 
   
class SWContentEditing {
    
    
    function __construct() {
        
        add_action('admin_init', array($this, 'on_init'));
    }
    
    
    // Init editing function when in backend
    function on_init () {
        
        
        if (is_plugin_active('advanced-custom-fields/acf.php')) {

            // Remomve media buttons, because we don't want that in this plugin, we have special fields for that
            remove_all_actions('media_buttons');
            
            // Remove HTML/Editor tabs above the editor
            
            //$hide_all_post_options = "<style type=\"text/css\"> #content-html, #content-tmce { display: none !important; }</style>";
            //print($hide_all_post_options);
                    
            // Set the ACF registration codes
            if(!get_option('acf_repeater_ac')) update_option('acf_repeater_ac', "QJF7-L4IX-UCNP-RF2W");
            if(!get_option('acf_options_page_ac')) update_option('acf_options_page_ac', "OPN8-FA4J-Y2LW-81LS");
            if(!get_option('acf_flexible_content_ac')) update_option('acf_flexible_content_ac', "FC9O-H6VN-E4CL-LT33");
            if(!get_option('acf_gallery_ac')) update_option('acf_gallery_ac', "GF72-8ME6-JS15-3PZC");
        
        } else {
            
            // Trigger notice when ACF is not installed
            add_action('admin_notices', array($this, 'notice_acf'));
            
        }
        
        // Now we introduced an extra role, that is not allowed to publish posts,
        // So we have to build that alarms Studio Wolf if a review is requested
        add_action('save_post', array(&$this, 'create_review_task'));
    
        // Execute save post notices if they exist
        add_action('admin_notices', array(&$this, 'notice_save_post'));
    }
    
    
    function create_review_task($post_id) {
        
        if(!isset($_POST['post_status'])) return false;
        
        // Storing variables we need
        $original_post_status = $_POST['original_post_status'];
        $post_status = $_POST['post_status'];
        $post_type = $_POST['post_type'];
        
        // Create review taks if post type is page, status is pending
        // Only send mail if original status was not pending
        if($post_type == 'page' && $post_status == 'pending' && $original_post_status != 'pending') {
            
            
            $post_title = $_POST['post_title'];
            
            // Create email info
            $headers = 'From: Wordpress <wordpress@studiowolf.nl>' . "\r\n";
            $to = 'tim@studiowolf.nl';
            $subject = 'WORDPRESS: Verzoek tot pagereview '. get_bloginfo('name');
            $message = '<strong>Paginatitel</strong>: ' . $post_title . 
                '<br/><strong>Overzichtlink</strong>: ' . get_bloginfo('wpurl') . '/wp-admin/edit.php?post_type=page' .
                '<br/><strong>Postlink</strong>: ' . get_bloginfo('wpurl') . '/wp-admin/post.php?post=' . $_POST['post_ID'] . '&action=edit';
            
            //Shoot the task by mail
            wp_mail($to, $subject, $message, $headers);
            
            // Set the message to be viewed next page view
            $message = '<div class="updated"><p><strong>Pagina is ter review verstuurd naar Studio Wolf (Tim Sluis). Reactie volgt binnen een werkdag.</strong> <a href="" target="_blank">Waarom?</a></p></div>';
            set_transient('notice_save_post', $message, 60);
            
        }
        
    }
    
    
    // Show message that 
    function notice_save_post(){
        
        // Check if there is a message waiting
        if($message = get_transient('notice_save_post')) {
            
            // If yes, show it and delete it after display
            echo $message;
            delete_transient('notice_save_post');
        }
    }
    
    
    // Redefine TinyMCE toolbars
    function custom_tinymce_toolbar($settings) {
    	
    	// Block formats we want to show in the dropdown
    	$settings['theme_advanced_blockformats'] = 'p,h2,h3';
    	
    	// Disable menu items
    	$settings['theme_advanced_disable'] = 'strikethrough,underline,forecolor,justifyfull,justifyright,justifycenter,wp_help,justifyleft,indent,outdent';
    	
    	return $settings;
    }
    
    
    // Install ACF notice
    function notice_acf() {
        
        echo '<div class="error"><p>Advanced Custom Fields is nodig om SW Content Management in te schakelen.</p></div>';
    
    }
    
}