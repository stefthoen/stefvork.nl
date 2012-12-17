<?php

/** 
 * Add specific editing function and options to Wordpress.
 * Add clean text options to Wordpress
 * @TODO: Filter text before it is saved (ingore linebreaks etc.)
 * @TODO: Give editors a message if something is changed
 * @TODO: Add h2 content button
 */ 
   
class SWEditing {
    
    
    function __construct() {
        
        add_action('admin_init', array($this, 'on_init'));
          
    }
    
    
    // Init editing function when in backend
    function on_init () {
        
        // Customize TinyMCE
        add_filter('tiny_mce_before_init', array($this, 'custom_tinymce_toolbar'));
        
    }
    
    
    // Redefine TinyMCE toolbars
    function custom_tinymce_toolbar($settings) {
    	
    	// Block formats we want to show in the dropdown
    	$settings['theme_advanced_blockformats'] = 'p,h2,h3';
    	
    	// Disable menu items
    	$settings['theme_advanced_disable'] = 'strikethrough,underline,forecolor,justifyfull,justifyright,justifycenter,wp_help,justifyleft,indent,outdent';
    	
    	return $settings;
    }
    
}