<?php
/*
Plugin Name: SW Content Management
Plugin URI: http://www.studiowolf.nl/
Description: Extensions to add content management functions to adapt Wordpress to the adaptive content principle.
Version: 1.0
Author: Studio Wolf
Author URI: http://www.studiowolf.nl/
License: For unlimited usage, do not copy
*/

/* Copyright 2012 Studio Wolf (email: hallo@studiowolf.nl) 
 *   
 * @TODO: Make default options to make meta-data available, like video and images (per post type?)    
 */


class SWContentManagement {
    
    public static $plugin_path;
    public static $plugin_url;
    public static $plugin_file;
    public static $css_url;
    public static $javascipt_url;
    public static $image_url;

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
        self::$plugin_file = __FILE__;
        
    }
    
    // Include module files
    function load_modules() {
        
        require_once('library/contentEditing.php');
        new SWContentEditing();
        
        require_once('library/fields.php');
        new SWFields();
        
        require_once('library/users.php');
        new SWUsers();
        
        require_once('library/roles.php');
        new SWRoles();
        
        // Load template functions
        require_once('library/functions.php');
        
    }
    
    // Set default settings
    function default_constants() {
                
    }

}

new SWContentManagement();