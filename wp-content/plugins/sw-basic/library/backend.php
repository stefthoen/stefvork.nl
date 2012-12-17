<?php

/** 
 * Makes some changes to the admin interface.
 * @TODO: Revisions? Keep revisions last 30 days? See the book for more info
 * @TODO: How to cope with "scherminstellingen"? See remove_admin_tabs function
 * @TODO: How can we fix orders of meta boxes?
 * @TODO: Automatically disable Trackbacks and Pingbacks via plugin activation. See: 
 *        http://kb.siteground.com/article/How_to_disable_trackbacks_in_WordPress.html
 * @TODO: Implement correct update time in dashboard widget
 * @TODO: Change Worpress logo in far left top for Studio Wolf logo. See function remove_admin_bar_items.
 * @TODO: Lock pages meta_window at pages
 * @TODO: Lock pages automatically when they are parents for posts or taxonomies (based on the hooks)
 * @TODO: Can verbose page rules stay away?
 */ 
   
class SWBackend {
    
    function __construct() {
        
        add_action('admin_init', array($this, 'on_admin_init'));
        add_action('admin_menu', array($this, 'remove_menu_items'));
        
    }
    
    
    // Display Studio Wolf text in footer
    function footer_text() {
        echo 'Door <a target="_blank" href="http://www.studiowolf.nl">Studio Wolf</a>. Hulp nodig? Bel naar 050 8200 271 of mail naar <a href="mailto:hallo@studiowolf.nl">hallo@studiowolf.nl</a>.';
    }
    
        
    // Init SWBackend when admin_init it triggered
    function on_admin_init() {
        
        if(!SW_BASIC_ENABLE_COMMENTS) {
            // Comments meta boxes
            remove_meta_box('commentsdiv','post','normal');
            remove_meta_box('commentstatusdiv','post','normal');
    	} else {
        	// Remove avatar filter for backend as used in frontend.php
            remove_all_filters('get_avatar');
    	}
    	
    	// Remove custom field meta boxes
    	remove_meta_box('postcustom','post','normal');
    	
    	// Remove post author meta box
    	/* remove_meta_box('authordiv','post','normal'); */
    	remove_meta_box('commentsdiv','page','normal');
    	
    	// Remove page meta boxes
    	remove_meta_box('commentstatusdiv','page','normal');
    	remove_meta_box('postcustom','page','normal');
    	remove_meta_box('authordiv','page','normal');
    	
    	// Remove options from the user profile page
    	remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    	
    	// Activate other hooks
    	add_filter('admin_footer_text', array($this, 'footer_text'));
    	add_action('wp_before_admin_bar_render', array($this, 'remove_admin_bar_items'));
    	add_action('wp_dashboard_setup',  array($this, 'dashboard_widgets'));
    	add_action('admin_head', array($this, 'remove_admin_tabs'));
    	
    }
    
    
    // Remove menu items in admin sections
    function remove_menu_items () {
        
        global $menu;
        
        $restricted = array();
        
        // Menu items to be removed
        if(!SW_BASIC_ENABLE_COMMENTS) $restricted[] = __('Comments');
        if(!SW_BASIC_ENABLE_LINKS) $restricted[] = __('Links');
        $restricted[] = __('Extra');
        
        end ($menu);    
        while (prev($menu)) {
            $value = explode(' ', $menu[key($menu)][0]);
            
            if(in_array($value[0] != NULL?$value[0]:"" , $restricted)) {
                unset($menu[key($menu)]);
            }
        }	
    }
    
    
    // change dashboard widgets
    function dashboard_widgets() {
        global $wp_meta_boxes;
        
        // Remove widgets from home screen
    	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    	
    	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    	
    	if(!SW_BASIC_ENABLE_DASHBOARD_NEWS) {
    	   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    	   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    	}
    	
    	if(!SW_BASIC_ENABLE_COMMENTS) {
        	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        }
        
        // Add Studio Wolf widget
        wp_add_dashboard_widget('dashboard_studiowolf', 'Informatie over dit systeem', array($this, 'widget_studiowolf'));
    }
    
    
    // Studio Wolf dashboard widget
    function widget_studiowolf() {
        
        // Default contact information
        $contact = array('name' => 'Tim Sluis', 'number' => '050 8200 271', 'email' => 'tim@studiowolf.nl');
        
        // Hook filters
        $contact = apply_filters('sw_basic_contact_information', $contact);
    
        echo '
            <h2>Wordpress van <a target="_blank" href="http://www.studiowolf.nl">Studio Wolf</a></h2>
            <p>Heb je hulp nodig? Of wil je een probleem melden? Neem direct contact op met de juiste persoon via de onderstaande gegevens:</p>
            <ul class="list">
                <li>Wie: <strong>' . $contact['name'] . '</strong></li>
                <li>Telefoon: <strong>' . $contact['number'] . '</strong></li>
                <li>E-mail: <strong>' . $contact['email'] . '</a></strong></li>
            </ul>
            <!--<p>Dit systeem is voor het laatst ge&uuml;pdate op <strong>11 oktober 2012</strong>.</p>--><br/>
        ';
    }
    
    
    // Remove tabs from admin bar
    function remove_admin_tabs() {
        
        // Remove help tab
        $screen = get_current_screen();
        $screen->remove_help_tabs();
        
        // Remove screen tab
        //add_filter('screen_options_show_screen', '__return_false');
    }
    
    
    // Remove WP admin bar items
    function remove_admin_bar_items() {
        global $wp_admin_bar;
        
        $wp_admin_bar->remove_menu('wp-logo');
        $wp_admin_bar->remove_menu('new-content');
        $wp_admin_bar->remove_menu('comments');
    }
    
        
}