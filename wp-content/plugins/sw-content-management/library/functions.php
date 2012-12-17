<?php

/** 
 * Template functions
 * @TODO: All functions that accept an ID like sw_get_title() should also accept a slug
 */ 


// Get the navigation title of the content
function sw_get_title($sw_post_id = null) {
        
    return get_the_title($sw_post_id);
    
}


// Get headline of the content
// @param: post_id to show
function sw_get_headline($sw_post_id = null) {
    
    // Try to get the headline field
    if(!$sw_headline = get_field('headline', $sw_post_id)) {
        
        // If not there, then get the title
        $sw_headline = sw_get_title($sw_post_id);
    }
    
    return $sw_headline;
}


// Get the sub headline
function sw_get_sub_headline($sw_post_id = null) {

    if($sw_sub_headline = get_field('sub_headline', $sw_post_id)) { 
        return $sw_sub_headline;
    }
    
    return false;
}


// Get the navigation title of the content
function sw_get_window_title() {
        
    return sw_get_headline() . " - " . get_bloginfo('name');
    
}


// Get the mini teaser
function sw_get_mini_teaser($sw_post_id = null) {
    if($sw_mini_teaser = get_field('mini_teaser', $sw_post_id)) { 
        return $sw_mini_teaser;
    }
    
    return false;
}


function sw_get_teaser($sw_post_id = null) {
    if($sw_teaser = get_field('teaser', $sw_post_id)) { 
        return $sw_teaser;
    }
    
    return false;
}


function sw_get_link($sw_post_id = null) {
    
    return get_page_link($sw_post_id);
    
}


function sw_get_metadescription() {
    
    return sw_get_mini_teaser();
    
}


function sw_get_images($post_id = null, $amount = false) {


}


// Get first image of the image meta data
function sw_get_first_image($post_id = null) {

    if($images = get_field('images', $post_id)) {
        return array_shift($images);
    }
    return false;
        
}


function sw_get_files($post_id = null, $amount = false) {


}


function sw_get_videos($post_id = null, $amount = false) {


}


function sw_get_first_video($post_id = null) {
    if($videos = get_field('videos', $post_id)) {
        return array_shift($videos);
    }
    return false;
}


/* Option functions */
function sw_get_option_field($field) {
    return get_field($field, 'options');
}


/* User met adata */
function sw_get_user_field($field, $user = false) {
    
    // If $user is false, then get current post user
    if(!$user) {
        global $post;
        $user = $post->post_author;
        
        // Return if no user is found
        if(!$user) return;
    }
    
    // Check if is an ID or an object
    if(is_numeric($user)) {
        return get_field($field, 'user_' . $user);
    } else {
        return get_field($field, 'user_' . $user->ID);
    }
    
}

function sw_get_user_email($user) {
    return $user->user_email;
}