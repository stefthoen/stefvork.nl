<?php

/** 
 * Add team fields and user functions
 *
 */ 
   
class SWUsers {
    
    function __construct() {
        
        add_filter('user_contactmethods', array($this, 'change_contact_methods'));
        add_filter('init', array($this, 'team_member_fields'));
        
    }
    
    // Change contact methods
    function change_contact_methods($contact_methods) { 
        
        // Add Twitter
        $contact_methods['twitter'] = 'Twitter';
        $contact_methods['facebook'] = 'Facebook';
        $contact_methods['linkedin'] = 'LinkedIn';
        $contact_methods['dribbble'] = 'Dribbble';
    	$contact_methods['phone'] = 'Telefoon';
    	
        // Unset unneeded contact methods
        unset($contact_methods['yim']);
        unset($contact_methods['aim']);
        unset($contact_methods['jabber']);
     	
        return $contact_methods;
    }
    
    
    function team_member_fields() {
        
        if(function_exists("register_field_group")) {
            register_field_group(array (
        		'id' => '508ff3c3f0442',
        		'title' => 'Teamlid',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_508fe0fcbe200',
        				'label' => 'Tonen op website',
        				'name' => 'show',
        				'type' => 'true_false',
        				'instructions' => '',
        				'required' => '0',
        				'message' => 'Teamlid tonen op de website?',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_50896a9d52da2',
        				'label' => 'Functietitel',
        				'name' => 'job_title',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '1',
        			),
        			2 => 
        			array (
        				'key' => 'field_508fe506ddbc1',
        				'label' => 'Functie-omschrijving',
        				'name' => 'job_description',
        				'type' => 'text',
        				'instructions' => 'Functieomschrijving in maximaal 15 woorden.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'html',
        				'order_no' => '2',
        			),
        			3 => 
        			array (
        				'key' => 'field_508fe57a95746',
        				'label' => 'Foto',
        				'name' => 'photo',
        				'type' => 'image',
        				'instructions' => 'Foto geschikt voor vertoning op de website.',
        				'required' => '0',
        				'save_format' => 'object',
        				'preview_size' => 'thumbnail',
        				'order_no' => '3',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => 
        			array (
        				0 => 
        				array (
        					'param' => 'ef_user',
        					'operator' => '==',
        					'value' => 'editor',
        					'order_no' => '0',
        				),
        				1 => 
        				array (
        					'param' => 'ef_user',
        					'operator' => '==',
        					'value' => 'author',
        					'order_no' => '1',
        				),
        				2 => 
        				array (
        					'param' => 'ef_user',
        					'operator' => '==',
        					'value' => 'contributor',
        					'order_no' => '2',
        				),
        				3 => 
        				array (
        					'param' => 'ef_user',
        					'operator' => '==',
        					'value' => 'subscriber',
        					'order_no' => '3',
        				),
        				4 => 
        				array (
        					'param' => 'ef_user',
        					'operator' => '==',
        					'value' => 'managing_editor',
        					'order_no' => '4',
        				),
        			),
        			'allorany' => 'any',
        		),
        		'options' => 
        		array (
        			'position' => 'normal',
        			'layout' => 'default',
        			'hide_on_screen' => 
        			array (
        			),
        		),
        		'menu_order' => 0,
        	));
        }
    }
    
}
