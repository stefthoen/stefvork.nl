<?php
/*
Plugin Name: SW Basic
Plugin URI: http://www.studiowolf.nl/
Description: Basic set of plugins to set SW Wordpress behaviour.
Version: 1.0.2
Author: Studio Wolf
Author URI: http://www.studiowolf.nl/
License: For unlimited usage, do not copy
*/

/* Copyright 2012 Studio Wolf (email: hallo@studiowolf.nl) */


class SWBasic {
    
    public static $plugin_path;
    public static $plugin_url;
    public static $css_url;
    public static $javascipt_url;
    public static $image_url;
    public static $image_placeholders;

    // Initialize the plugin
    function __construct() {
        
        $this->default_constants();
        $this->set_variables();
        $this->load_modules();
        
    }
    
    // Set plugin wide variables
    function set_variables() {
        
        self::$plugin_path = plugin_dir_path( __FILE__ );
        self::$plugin_url = plugins_url('',__FILE__);
        self::$css_url = self::$plugin_url . "/css";
        self::$javascipt_url = self::$plugin_url . "/javascript";
        self::$image_url = self::$plugin_url . "/images";
        self::$image_placeholders = array();
        
    }
    
    // Include module files
    function load_modules() {
        
        require_once('library/login.php');
        global $pagenow;

        // Only load when on the login page
        if($pagenow == 'wp-login.php') new SWlogin();
        
        require_once('library/rewrite.php');
        new SWRewrite();
        
        require_once('library/backend.php');
        new SWBackend();
        
        require_once('library/media.php');
        new SWMedia();
        
        require_once('library/frontend.php');
        new SWFrontend();
        
        require_once('library/editing.php');
        new SWEditing();
        
        // Load NavigationPage class
        require_once('library/navigation-page.php');
        
        // Load template functions
        require_once('library/functions.php');
        
    }
    
    // Set default settings
    function default_constants() {
        
        // Option to enable comments
        if (!defined('SW_BASIC_ENABLE_COMMENTS')) {
            define('SW_BASIC_ENABLE_COMMENTS', false);
        }
        
        // Option to enable link section in Wordpress
        if (!defined('SW_BASIC_ENABLE_LINKS')) {
            define('SW_BASIC_ENABLE_LINKS', false);
        }
        
        // Option to enable Wordpress news on the dashboard
        if (!defined('SW_BASIC_ENABLE_DASHBOARD_NEWS')) {
            define('SW_BASIC_ENABLE_DASHBOARD_NEWS', false);
        }
        
    }

}

new SWBasic();