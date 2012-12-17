<?php

/** 
 * Changes the login screen to Studio Wolf style.
 */ 
   
class SWlogin {
    
    function __construct() {
                
        // Remove stylesheets
        add_action('style_loader_tag', '__return_false');
        
        add_filter('login_headerurl', array($this, 'login_logo_url'));
        add_action('login_enqueue_scripts', array( $this, 'login_stylesheet'));
        
    }


    // Change logo URL
    function login_logo_url() {
        
        return "http://www.studiowolf.nl/";
        
    }
    
    
    // Add own stylesheet
    function login_stylesheet() {
    
        echo '<link rel="stylesheet" id="sw-login-css"  href="' . SWBasic::$css_url . '/login.css" type="text/css" media="all" />';
        
    }
    
}