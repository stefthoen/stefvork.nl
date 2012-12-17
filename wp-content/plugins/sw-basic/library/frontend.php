<?php

/** 
 * Remove elements from the frontend of a website, like head and the admin bar
 * @TODO: Implement function below if wp_head IS included
 */ 
   
class SWFrontend {
    
    function __construct() {
        
        // Remove RSS feeds for comments
        add_filter('post_comments_feed_link', '__return_false');
        
        // Do not show the admin bar on the website itself
        add_filter('show_admin_bar', '__return_false');
        
        // Only load when comments are enabled
        if(SW_BASIC_ENABLE_COMMENTS) {
            add_filter('get_avatar', array($this, 'regenerate_avatar_tag'));
            add_action('preprocess_comment', array($this, 'comment_spam_filter'));
        }
        
    }
    
    
    // Regerate avatar tag without unnecessary attributes
    function regenerate_avatar_tag($avatar) {
        
        // This filter is removed in backend.php on admin_init, so avatars display correctly within admin screens
        
        $result = array();
        
        // Only match the src-attribute
        preg_match( '/src=\'(.*?)\'/i', $avatar, $result ) ;
        
        // Create and return new image element
        return("<img src='".$result[1]."'/>");
    }
    
    
    // Filter comments from spam
    function comment_spam_filter($comment) {
        
        // Get the spam variable from post data      
        $spam = $_POST['spam'];
        
        // Variable spam should not be set. If set, spam is active.
        if(!isset($spam) || $spam != "") {
            die();
        }
        
        return $comment;
    }
    
}



// These functions are not yet implemented, only if you use wp_head() in frontend, otherwise not needed

/* Remove the wlwmanifest_link (Windows Live Writer) */
//remove_action('wp_head', 'wlwmanifest_link');

/* Remove WP Version information */
//remove_action('wp_head', 'wp_generator');


/* Remove the RSD header link */
//remove_action('wp_head', 'rsd_link');

/* Remove prev and next links from the <head> */
//remove_action('wp_head', 'start_post_rel_link', 10, 0 );
//remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
