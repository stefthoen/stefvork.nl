<?php

/** 
 * A wrapper function around an object to take care of navigation related functions
 * @TODO: implement long title
 * @TODO: Use cookies to see from which cat the user came, see get_parent function
 * @TODO: get children of this page
 * @TODO: Replace default page-attributes: http://wordpress.stackexchange.com/questions/44966/how-can-i-add-extra-attribute-in-the-page-attribute-section-in-wp-admin-for-pa
 */ 
   
class SWNavigationPage {
    
    // The post represented in this navigation item
    protected $_post;
    
    // If the parent is included in the navigation, this one should not be active of course
    protected $_is_included_parent;
    
    
    // Store the original post
    function __construct($post) {
        
        $this->_post = $post;
        
        $this->_is_included_parent = false;
        
    }
    
    
    // Return the ID of the post object
    public function get_id() {
        
        return $this->_post->ID;
        
    }
    
    
    protected $_parents;
    
    // Fetch and store all parents
    public function get_parents() {
        
        // If the parent chain does not exist, perform search
        if (!$this->_parents) {
            
            // Create the parents array
            $parents = array();
            
            // If the this page has a parent, add it to the chain
            if($parent = $this->get_parent()) {  
                $parents = $parent->get_parents();
            } 
            
            // Add the current object to the chain
            $parents[] = $this;
            
            // Store for re-use
            $this->_parents = $parents;
        } 
        
        return $this->_parents;
        
    }
    
    
    protected $_parent;
    
    // Fetch and store all parent
    public function get_parent() {
        
        if(!$this->_parent && $this->_post->post_parent) {

            // Get the parent of the post
            
            $parent_id = $this->_post->post_parent;
               
        } elseif(!$this->is_page()) {

            // If page parent does not exist, for example posts and categories, search the settings
            // Array key: post type name, Array value: page id of parent
            $post_parents = array();
            $taxonomy_parents = array();
            
            // Apply filter to hook from functions
            $taxonomy_parents = apply_filters('sw_taxonomy_parents', $taxonomy_parents);
            $post_parents = apply_filters('sw_post_parents', $post_parents);
            
            $use_taxonomies = false;
            // We need to take care of categories in the parent
            if(array_key_exists($this->get_post_type(), $taxonomy_parents)
                && !is_archive()
                && $taxonomy_ids = $this->get_term_ids()
            ) {
                
                // We can make use of taxonomies
                $use_taxonomies = true;
            
            }
   
            if($use_taxonomies && array_key_exists($taxonomy_ids[0], $taxonomy_parents[$this->get_post_type()])) {
                // @TODO: Use cookies to see from which cat the user came
                // Search fot the taxonomy parent of the first taxonomy id
                $parent_id = $taxonomy_parents[$this->get_post_type()][$taxonomy_ids[0]];     
             
            } elseif(array_key_exists($this->get_post_type(), $post_parents)) {
                // Search for the post type parent id
                
                $parent_id = $post_parents[$this->get_post_type()];
            }
        }
        
        
        // Check if parent_id is set. The parent is always a page
        if(isset($parent_id) && $page = get_page($parent_id)) { 
            
            //Check if the page is indeed a page and is published
            if($page->post_type == 'page' && $page->post_status == 'publish') {

                // Everything ok, then create new NavigationPage Object
                $this->_parent = new SWNavigationPage($page);
                return $this->_parent;
            } 
                            
        }
            
        
        
        return $this->_parent;
        
    }
    
    
    protected $_children;
    
    // Fetch and store children, return when asked
    public function get_children() {
        
        return $this->_children;
        
    }
    
    
    // Check if item has children
    public function has_children() {
        
        return false;
        
    }
    
    
    protected $_is_active;
    
    // Check if a page is active at this moment
    public function is_active($parent = null) {
        global $sw_page;
        
        if($this->get_id() == $sw_page->get_id()) {
            return true;
        }
        
        // Only look if item is active if the item is not a parent
        if (!$this->_is_included_parent) {
            
            // Look at all the parents of the current page to see if a navigation item matches
            foreach($sw_page->get_parents() as $parent) {
                if ($this->get_id() == $parent->get_id()) {
                    return true;
                }
            }
        }
        
        return false;
        
    }
    
    
    // Return navigation title of the post
    public function get_navigation_title() {
    
        return $this->_post->post_title;
        
    }
    
    
    // Return headline of the page
    public function get_headline() {
        
        // Check if the SW Content Management plugin is activated
        if(function_exists("register_field_group")) {
            return sw_get_headline($this->get_id());
        } else {
            return $this->get_navigation_title();
        }
        
    }
    
    
    public function __toString() {
        
        return $this->get_navigation_title();
        
    }
    
    
    // Set this NavigationPage as the parent
    public function is_included_parent() {
        
        $this->_is_included_parent = true;
        
    }
    
    
    // Check if post-object is a page
    private function is_page() {
        
        if($this->_post->post_type == 'page') {
            return true;
        }
        
        return false;
        
    }
    
    
    // Check the post type of this post
    private function get_post_type() {
        
        return get_post_type($this->_post);
        
    }
    
    
    // Get the term id's this page is related with
    private function get_term_ids() {
        
        // Find the post type taxonomies        
        $taxonomies = get_object_taxonomies($this->get_post_type());
        
        // Find the terms matching the post
        $taxonomy_terms = wp_get_post_terms($this->get_id(), $taxonomies, array('fields' => 'ids'));
        
        // Return the combined categories
        return $taxonomy_terms;
        
    }
       
}