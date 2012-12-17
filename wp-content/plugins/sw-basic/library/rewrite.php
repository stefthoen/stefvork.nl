<?php

/** 
 * Changes the rewrite rules
 */ 
   
class SWRewrite {
    
    private $page_rewrite_rules;
    
    function __construct() {
                
        // Remove stylesheets
        add_action('init', array($this, 'on_init'));
        add_filter('page_rewrite_rules', array($this, 'collect_page_rewrite_rules'));
        add_filter('rewrite_rules_array', array($this, 'prepend_page_rewrite_rules'));
        
    }

    
    // On Wordpress init
    function on_init() {
        
        // Create verbose rewrite rules
        $GLOBALS['wp_rewrite']->use_verbose_page_rules = true;
         
    }
    
    
    // Collect the page rewrite rules
    function collect_page_rewrite_rules($page_rewrite_rules) {
        
        $this->page_rewrite_rules = $page_rewrite_rules;
        return array();
        
    }
    
 
    // Prepend the page rewrite rules to the beginning
    function prepend_page_rewrite_rules($rewrite_rules) {
        
        return $this->page_rewrite_rules + $rewrite_rules;
        
    }

    
}