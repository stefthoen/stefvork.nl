<?php

/** 
 * Change and manage roles and permissions in Wordpress to create a more reliable system
 */ 
   
class SWRoles {
    
    
    function __construct() {
        
        // Register plugin activation hook
        register_activation_hook(SWContentManagement::$plugin_file, array(&$this, 'activation'));
        
        // Register plugin deactivation hook
        register_deactivation_hook(SWContentManagement::$plugin_file, array(&$this, 'deactivation'));
        
        add_filter( 'editable_roles', array(&$this, 'editable_roles'));
        add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'), 10, 4);
        
        add_action('admin_init', array(&$this, 'on_init'));
        
    }
    
    
    // Init when backend is loaded
    function on_init () {
        global $wp_roles;
        
        // Rename roles to more applicable names (can be changed at all times)
        $wp_roles->roles['contributor']['name'] = 'Medewerker';
        $wp_roles->roles['subscriber']['name'] = 'Lid';
        $wp_roles->roles['administrator']['name'] = 'Studio Wolf';
        $wp_roles->roles['managing_editor']['name'] = 'Beheerder';
        
        $wp_roles->role_names['contributor'] = 'Medewerker';
        $wp_roles->role_names['subscriber'] = 'Lid';
        $wp_roles->role_names['administrator'] = 'Studio Wolf';
        $wp_roles->role_names['managing_editor'] = 'Beheerder';
                
    }
    
    function activation() {
        
        global $wp_roles;
        
        // Capabilities are editor capabilities in beginning
        $editor = $wp_roles->get_role('editor');
        
        // Remove page publishing/deleting editor capabilities
        $editor->remove_cap('delete_published_pages');
        $editor->remove_cap('publish_pages');
        $editor->remove_cap('delete_others_pages');
        
        
        // Add the managing editor role with editor capabilities
        add_role('managing_editor', 'Hoofdredacteur', $editor->capabilities);
        
        $managing_editor = get_role('managing_editor');
        
        if(!empty($managing_editor)) {
            $managing_editor->add_cap('create_users');
            $managing_editor->add_cap('list_users');
            $managing_editor->add_cap('edit_users');
            $managing_editor->add_cap('delete_users');
            $managing_editor->add_cap('promote_users');
        }
        
    }
    
    
    // Restore Wordpress in old state
    function deactivation() {
        
        global $wp_roles;
        
        // Restore editor role to normal capabilities
        $editor = $wp_roles->get_role('editor');
        
        $editor->add_cap('delete_published_pages');
        $editor->add_cap('publish_pages');
        $editor->add_cap('delete_others_pages');
        
        // Remove managing editor role
        remove_role('managing_editor');
    }
    
    
    // Other users than the admin can not edit administrators
    function editable_roles( $roles ){
        if(isset($roles['administrator']) && !current_user_can('administrator')) {
            unset($roles['administrator']);
        }
        return $roles;
    }


    // If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
    function map_meta_cap( $caps, $cap, $user_id, $args ){
    
        switch( $cap ){
            
            case 'edit_user':
            case 'remove_user':
            case 'promote_user':
                
                if(isset($args[0]) && $args[0] == $user_id) {
                    break;
                }
                elseif(!isset($args[0])) {
                    $caps[] = 'do_not_allow';
                }
                
                $other = new WP_User(absint($args[0]));
                
                if($other->has_cap('administrator')) {
                    if(!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                }
                
                break;
            
            case 'delete_user':
            case 'delete_users':
                
                if(!isset($args[0])) {
                    break;
                }
                
                $other = new WP_User( absint($args[0]) );
                
                if($other->has_cap('administrator')) {
                    if(!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
                
            default:
            
                break;
        }
        
        return $caps;
        
      }
    
}