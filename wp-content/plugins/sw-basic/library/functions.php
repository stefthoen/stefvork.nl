<?php

/** 
 * Template functions
 * @TODO: breadcrumb function
 * @TODO: Automatically detect different video URLS (youtube, vimeo, etc)
 */ 
 

/**
 * Navigation functions
 */
 

// Get navigation on a specific level
// @param $level the depth from the top
// @param $include_parent include parent of the navigational items as first item
// @return $navigationItems[]
function sw_get_navigation($level = null, $include_parent = false) {
    
    global $post;
    global $sw_page;
    
    // Create a SWNavigationPage object from the post and set it as global if not yet created
    if(!isset($sw_page)) $sw_page = new SWNavigationPage($post);
    
    // If a page and a level is given, create navigational on the right level
    if($level) {
        $page_parents = $sw_page->get_parents();
        
        if(isset($page_parents[$level-1])) {
        
            // Navigation found at this level, return parent for further processing
            $parent = $page_parents[$level-1];
        } else {
            
            // No navigation at this level, return fale
            return false;
        }
    }

    // Empty array of navigation items
    $navigation_items = array();

    // If there is a parent and include_parent is true, then include the parent first
    if($include_parent && isset($parent)) {
        
        // Set this PageNavigation as the included parent
        $parent->is_included_parent();
        $navigation_items[] = $parent;
        
    }
    
    if(isset($parent)) {
        
        // If a parent is set, use the parent for determining the items
        $posts = get_pages(array(
            'post_type' => 'page',
            'parent' => $parent->get_id(), 
            'child_of' => $parent->get_id(),
            'sort_column' => 'menu_order', 
            'hierarchical' => 0, 
            'meta_key' => 'show_in_navigation',
            'meta_value' => true,
        ));
    }
    else {
        // Else simply use root menu
        $posts = get_pages(array(
            'post_type' => 'page',
            'parent' => null, 
            'child_of' => null,
            'sort_column' => 'menu_order', 
            'hierarchical' => 0,
            'meta_key' => 'show_in_navigation',
            'meta_value' => true,
        ));
        
    }
   
   
    // Loop all post to create NavigationPage items
    foreach($posts as $sw_post) {
        $navigation_items[] = new SWNavigationPage($sw_post);
    }

    return $navigation_items;
}


// Get the navigation breadcrumbs
function sw_get_breadcrumbs($include_home = true) {
    global $post;
    global $sw_page;
    
    // Create a SWNavigationPage object from the post and set it as global if not yet created
    if(!isset($sw_page)) $sw_page = new SWNavigationPage($post);
    
    // See OLE breadcrumbs if we have to do breadcrumbs for archives
    
    // Get the page parents
    $parents = $sw_page->get_parents();
    
    // If include home option is set, add the home page to the stack
    if($include_home && $home_page_id = get_option('page_on_front')) {
        
        $home_page = new SWNavigationPage(get_post($home_page_id));
        array_unshift($parents, $home_page);
        
    }
    
    return $parents;
}


/**
 * Image functions
 */
 
// Create an image tag from an object
function sw_get_image_tag($object, $size) {
    
    /* Check if attachment still exists */
    if($src = $object['sizes'][$size]) {
        
        /* Get the attachment source and create the image tag */
        $alt = $object['alt'];
        return '<img src="'.$src.'" alt="'. $alt .'" />';
    
    } else {
        $sizes = SWBasic::$image_placeholders;
        if(isset($sizes[$size])) {
            $src = $sizes[$size];
            return '<img src="'.$src.'" />';    
        }
        
    }
    return false;
}

// DEPRECATED, replaced by sw_get_image_tag
function sw_image_tag($object, $size) {
    return sw_get_image_tag($object, $size);
}


// Get an image url given an object and a specific size
function sw_get_image_url($object, $size) {
    if($src = $object['sizes'][$size]) {
        return $src;
    } else {
        if($src = $sizes[$size]) {
            return $src;
        }
    }
    return false;
}

// DEPRECATED, replaced by sw_get_image_url
function sw_image_url($object, $size) {
    return sw_get_image_url($object, $size);
}


/**
 * Video functions
 */

// Create a video tag from video-object
function sw_get_video_tag($object) {

    // Check if object has an url
    if($url = $object['url']) {
        
        // Now we only support youtube, so create the correct embed url
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=embed/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\‌​n]+#", $url, $matches);
        
        // Return false if it is an invalid URL
        if(count($matches) > 0) {
            // Create the embed-url
            $embed_url = "http://www.youtube.com/embed/" . $matches[0] . "?rel=0&amp;wmode=transparent&amp;feature&amp;showinfo=0";
        
            // Return the youtube-tag
            return '<iframe src="' . $embed_url . '" frameborder="0" allowfullscreen></iframe>';
        }
    
    }
    
    return false;
} 


/**
 * File functions
 */
 
 
// Create a file tag from file-object
function sw_get_file_tag($object, $caption = false) {
    /* Check if attachment still exists */
    if($url = $object['url']) {
        /* Get the attachment source and create the image tag */
        $title = $object['title'];
        
        if(!$caption) {
            $caption = $object['caption'];
        }
        
        return '<a href="'. $url .'" title="'. $title .'" target="_blank" />'. $caption .'</a>';
    
    }
    return false;
}


// Get a file url given an object
function sw_get_file_url($object) {
    if($url = $object['url']) {
        return $url;
    }
    return false;
}


// Add a placeholder for a dimension
function sw_add_image_size_placeholder($name, $src) {
    SWBasic::$image_placeholders[$name] = $src;
}