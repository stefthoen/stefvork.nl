<?php

/** 
 * Load specific media functions
 * @TODO: Automatically accept new image sizes. If an image is added, meta data is added
 * @TODO: Make prefilter minimum upload size a option, default 400x400, allow files to be uploaded otherwise!
 * If meta data not available when image is asked, wordpress thinks the image does not exist.
 * Search: automatic update image sizes/meta data
 */ 
class SWMedia {
    
    
    private $image_sizes;
    
    
    function __construct() {
        
        // Do not execute these hooks when multisite
        if (is_multisite()) return;
        
        // Filters and actions to disable resizing after upload, and activate on page view
        add_action('template_redirect', array($this, 'image_404_handler'));
        add_filter('intermediate_image_sizes_advanced', array($this, 'image_sizes_advanced'));
        add_filter('wp_generate_attachment_metadata', array($this, 'generate_metadata'));
        
        // Only needs to be loaded on admin
        //add_filter('wp_handle_upload_prefilter', array($this, 'upload_prefilter'));
                
    }
    
    
    // Do dynamic resizing if an image load triggers a 404 (because it's non-existant)
    function image_404_handler() {
    	
    	// Return if request is not a 404
    	if ( !is_404() ) return;
    	
    	// Check if request is an image
    	if (preg_match('/(.*)-([0-9]+)x([0-9]+)(c)?\.(jpg|png|gif)/i', $_SERVER['REQUEST_URI'], $matches)) {
    		
    		// Define variables
    		$filename = $matches[1] . '.' . $matches[5];
    		$width = $matches[2];
    		$height = $matches[3];
    		$crop = !empty($matches[4]);
    		
    		$uploads_dir = wp_upload_dir();
    		$temp = parse_url($uploads_dir['baseurl']);
    		$upload_path = $temp['path'];
    		$findfile = str_replace($upload_path, '', $filename);
    		
    		$basefile = $uploads_dir['basedir'] . $findfile;
    		$suffix = $width . 'x' . $height;
    		
    		if ($crop) $suffix .='c';
    	
    		// Only continue if file really exists
    		if (file_exists($basefile)) {
    			
    			// File found, call WP function to resize the file
    			$resized = image_resize($basefile, $width, $height, $crop, $suffix);
    			
    			// Find the mime type of the file to serve directly
    			foreach (get_allowed_mime_types() as $exts => $mime) {
    				if (preg_match( '!^(' . $exts . ')$!i', $matches[5])) {
    					$type = $mime;
    					break;
    				}
    			}
    
    			// Serve the image this one time (next time the webserver will do it for us)
    			header('Content-Type: '.$type);
    			header('Content-Length: ' . filesize($resized));
    			readfile($resized);
    			exit;
    		}
    	}
    }

    
    // Function to change the image sizes in Wordpress
    function image_sizes_advanced($sizes) {
    	
    	// Save image sizes to the class variable. We need this later to create the image meta's
    	$this->image_sizes = $sizes;
    
    	// Tell WP that there are no different image sizes. Now they won't be made
    	return array();
    }
    

    // Filter to intersect meta data. Now the sizes are gone, we need to get them from the private variable
    function generate_metadata($meta) {
    	
    	if(isset($this->image_sizes)) {		
    	
        	foreach ($this->image_sizes as $sizename => $size) {
        		
        		// Figure out what size WP would make this:
        		$newsize = image_resize_dimensions($meta['width'], $meta['height'], $size['width'], $size['height'], $size['crop']);
        
        		if ($newsize) {
        			$info = pathinfo($meta['file']);
        			$ext = $info['extension'];
        			$name = wp_basename($meta['file'], ".$ext");
        
        			$suffix = "{$newsize[4]}x{$newsize[5]}";
        			if ($size['crop']) $suffix .='c';
        
        			// build the fake meta entry for this specific entry
        			$resized = array(
        				'file' => "{$name}-{$suffix}.{$ext}",
        				'width' => $newsize[4],
        				'height' => $newsize[5],
        			);
        
        			$meta['sizes'][$sizename] = $resized;
        		}
        	}
        }
    	
    	return $meta;
    }
    
    
    
    // Prefilter uploaded images
    function upload_prefilter($file) {
    
        $img = getimagesize($file['tmp_name']);
        
        // Minimum dimensions, should be options eventually
        $minimum = array('width' => '400', 'height' => '400');
        
        $width = $img[0];
        $height = $img[1];
    
        // Return an upload error if dimensions are not big enough
        if($width < $minimum['width']) {
            return array('error' => "Image dimensions are too small. Minimum width is {$minimum['width']}px. Uploaded image width is $width px");
            
        } elseif ($height <  $minimum['height']) {
            return array('error' => "Image dimensions are too small. Minimum height is {$minimum['height']}px. Uploaded image height is $height px");
            
        } else {
            return $file; 
        }
        
    }
    
    
    // Returns image sizes defined in Wordpress and functions
    // if $name_as_key is true, the image size name will be the key instead of the value in the array
    static function get_image_sizes($name_as_key = false) {
        if($name_as_key) {
            
            $sizes = array();
            // Create array with sizes as the key
            foreach (get_intermediate_image_sizes() as $size) {
                $sizes[$size] = "";
            }
            return $sizes;
            
        } else {
            return get_intermediate_image_sizes();
        } 
    }
    
}