<?php

/** 
 * Add the fields to Wordpress
 * @todo: add filter to leave certain content fields away
 */ 
   
class SWFields {
    
    
    function __construct() {
        
        // Needs to be loaded as late as possible because to reach all custom post types
        add_action('init', array($this, 'init_fields'), 9999);
    }
    
    
    // Init all the fields
    function init_fields() {
        
        if(function_exists("register_field_group"))
        {
        
            // Set up rules to cover all post types
            $rules = array();
            
            // Count from 0
            $index = 0;
            
            // Find the different post types
            $post_types = get_post_types(array('public' => true));
            
            // Loop through
            foreach($post_types as $post_type) {
                
                // Don't see attachment as a post type
                if($post_type != 'attachement') {
                    
                    // Create rules for post type
                    $rules[$index] = array(
                        'param' => 'post_type',
        				'operator' => '==',
        				'value' => $post_type,
        				'order_no' => $index,
                    );
                }
                $index++;
            }
            
            
            // Register the groups for all post types
        	register_field_group(array (
        		'id' => '508961bcabbb2',
        		'title' => 'Headlines',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_508922a48ec67',
        				'label' => 'Headline',
        				'name' => 'headline',
        				'type' => 'text',
        				'instructions' => 'Dit is de hoofdkop. Deze beschrijft kort waar de content over gaat.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_508922a48ee95',
        				'label' => 'Sub headline',
        				'name' => 'sub_headline',
        				'type' => 'text',
        				'instructions' => 'Dit is de ondertitel bij de content. Ook wel verklarende titel of bijtitel genoemd en geeft nadere toelichting op de headline. Is niet in alle gevallen aanwezig.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '1',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => $rules,
        			'allorany' => 'any',
        		),
        		'options' => 
        		array (
        			'position' => 'normal',
        			'layout' => 'default',
        			'hide_on_screen' => 
        			array (
        				0 => 'excerpt',
        				1 => 'custom_fields',
        				2 => 'slug',
        				3 => 'featured_image',
        			),
        		),
        		'menu_order' => 10,
        	));
        	
        	register_field_group(array (
        		'id' => '508961bcac4bc',
        		'title' => 'Samenvattingen',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_508926b69b8a0',
        				'label' => 'Korte samenvatting',
        				'name' => 'mini_teaser',
        				'type' => 'text',
        				'instructions' => 'Samenvatting van de content in maximaal 15 woorden. Deze moet verplicht ingevuld worden omdat dit onder andere wordt gebruikt voor zoekmachine-optimalisatie.',
        				'required' => '1',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_508926b69bae6',
        				'label' => 'Samenvatting',
        				'name' => 'teaser',
        				'type' => 'textarea',
        				'instructions' => 'Beschrijf de content in maximaal 1 alinea (moraal van het verhaal). Typisch 4 regels tekst. Deze content kan op verschillende plekken gebruikt worden, onder andere als eerste, iets vetter gedrukte, alinea op de website. Dit veld is niet verplicht, maar wel aan te raden.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'br',
        				'order_no' => '1',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => $rules,
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
        		'menu_order' => 20,
        	));
        	
        	register_field_group(array (
        		'id' => '508961bcad11a',
        		'title' => 'Media',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_508931de9c472',
        				'label' => 'Afbeeldingen',
        				'name' => 'images',
        				'type' => 'gallery',
        				'instructions' => 'Voeg afbeeldingen toe die bij de content horen. Afbeeldingen die hoger staan worden eerder getoond. Het ontwerp bepaald waar de afbeeldingen getoond worden, daarom is het belangrijk dat alle informatie bij de afbeeldingen ingevuld wordt.',
        				'required' => '0',
        				'preview_size' => 'thumbnail',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_508948593ee00',
        				'label' => 'Bestanden',
        				'name' => 'files',
        				'type' => 'repeater',
        				'instructions' => 'Voeg hier bestanden en/of downloads toe die bij de content horen. Belangrijk is dat de juiste data als titel, onderschrift en omschrijving zijn ingevuld bij de behorende file om een net overzicht te genereren.',
        				'required' => '0',
        				'sub_fields' => 
        				array (
        					0 => 
        					array (
        						'key' => 'field_508948593ee1d',
        						'label' => 'Bestand',
        						'name' => 'file',
        						'type' => 'file',
        						'instructions' => '',
        						'column_width' => '',
        						'save_format' => 'object',
        						'order_no' => '0',
        					),
        				),
        				'row_min' => '0',
        				'row_limit' => '',
        				'layout' => 'row',
        				'button_label' => 'Nieuw bestand toevoegen',
        				'order_no' => '1',
        			),
        			2 => 
        			array (
        				'key' => 'field_50894eaab9144',
        				'label' => 'Video\'s',
        				'name' => 'videos',
        				'type' => 'repeater',
        				'instructions' => 'Voer URL\'s van video\'s in die bij de content horen. Plaats de volledige URL. Of de video\'s geplaatst worden is afhankelijk van het ontwerp.',
        				'required' => '0',
        				'sub_fields' => 
        				array (
        					0 => 
        					array (
        						'key' => 'field_50894eaab9156',
        						'label' => 'Video-url',
        						'name' => 'url',
        						'type' => 'text',
        						'instructions' => '',
        						'column_width' => '',
        						'default_value' => 'http://',
        						'formatting' => 'none',
        						'order_no' => '0',
        					),
        				),
        				'row_min' => '0',
        				'row_limit' => '',
        				'layout' => 'row',
        				'button_label' => 'Nieuwe video toevoegen',
        				'order_no' => '2',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => $rules,
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
        		'menu_order' => 30,
        	));
        	
        	register_field_group(array (
        		'id' => '508964b5b6bc8',
        		'title' => 'Overig',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'choices' => 
        				array (
        					1 => 'Hoog',
        					2 => 'Normaal',
        					3 => 'Laag',
        				),
        				'key' => 'field_5089557dc86e1',
        				'label' => 'Prioriteit',
        				'name' => 'priority',
        				'type' => 'select',
        				'instructions' => 'Geef de belangrijkheid van deze content aan binnen de website.',
        				'required' => '0',
        				'default_value' => 2,
        				'allow_null' => '0',
        				'multiple' => '0',
        				'order_no' => '0',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => $rules,
        			'allorany' => 'any',
        		),
        		'options' => 
        		array (
        			'position' => 'side',
        			'layout' => 'default',
        			'hide_on_screen' => 
        			array (
        			),
        		),
        		'menu_order' => 20,
        	));
        	
        	
        	// Option page fields
        	register_field_group(array (
        		'id' => '5089650b68bda',
        		'title' => 'Contactgegevens',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_50895d61370f8',
        				'label' => 'Organisatie',
        				'name' => 'organisation',
        				'type' => 'text',
        				'instructions' => 'Voer de naam van het bedrijf, instantie, organisatie etc. hier in.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_50895d6137575',
        				'label' => 'Adres',
        				'name' => 'address',
        				'type' => 'text',
        				'instructions' => 'Straatnaam en huisnummer.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '1',
        			),
        			2 =>
        			array (
        				'key' => 'field_50895d6137431',
        				'label' => 'Postcode',
        				'name' => 'postal_code',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '2',
        			),
        			3 =>
        			array (
        				'key' => 'field_50895d6137801',
        				'label' => 'Postbus',
        				'name' => 'postbox',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '3',
        			),
        			4 => 
        			array (
        				'key' => 'field_50895d61379ac',
        				'label' => 'Plaats',
        				'name' => 'city',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '4',
        			),
        			5 => 
        			array (
        				'key' => 'field_50895ea600cfd',
        				'label' => 'Telefoonnummer',
        				'name' => 'phone',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '5',
        			),
        			6 => 
        			array (
        				'label' => 'Fax',
        				'name' => 'fax',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'key' => 'field_50895ec167195',
        				'order_no' => '6',
        			),
        			7 => 
        			array (
        				'key' => 'field_50895d6137b55',
        				'label' => 'E-mailadres',
        				'name' => 'email',
        				'type' => 'text',
        				'instructions' => 'Het algemene e-mailadres.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '7',
        			),
        			8 => 
        			array (
        				'label' => 'Foto',
        				'name' => 'photo',
        				'type' => 'image',
        				'instructions' => 'Een algemene foto van de organisatie. Bijvoorbeeld een kantoor, of een groep mensen.',
        				'required' => '0',
        				'save_format' => 'object',
        				'preview_size' => 'thumbnail',
        				'key' => 'field_50901279d38a2',
        				'order_no' => '8',
        			),
        			9 => 
        			array (
        				'label' => 'Google Maps',
        				'name' => 'google_maps',
        				'type' => 'text',
        				'instructions' => 'Een link naar Google Maps waar men eventueel een routebeschrijving kan invullen.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'key' => 'field_70234ec167195',
        				'order_no' => '9',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => 
        			array (
        				0 => 
        				array (
        					'param' => 'options_page',
        					'operator' => '==',
        					'value' => 'acf-options',
        					'order_no' => '0',
        				),
        			),
        			'allorany' => 'all',
        		),
        		'options' => 
        		array (
        			'position' => 'normal',
        			'layout' => 'default',
        			'hide_on_screen' => 
        			array (
        			),
        		),
        		'menu_order' => 10,
        	));
        	
        	register_field_group(array (
        		'id' => '5089650b6a582',
        		'title' => 'Social media',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_5089601dbb0b9',
        				'label' => 'Twitter',
        				'name' => 'twitter_url',
        				'type' => 'text',
        				'instructions' => 'Voer de URL van het twitter-account in.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_5089601dbb315',
        				'label' => 'Facebook',
        				'name' => 'facebook_url',
        				'type' => 'text',
        				'instructions' => 'Voer de URL van de facebookpagina in.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '1',
        			),
        			2 => 
        			array (
        				'key' => 'field_5089601dbb4c7',
        				'label' => 'LinkedIn',
        				'name' => 'linkedin_url',
        				'type' => 'text',
        				'instructions' => 'Voer de URL van de LinkedIn-pagina in.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '2',
        			),
        			3 => 
        			array (
        				'key' => 'field_5069201dbb4c7',
        				'label' => 'Flickr',
        				'name' => 'flickr_url',
        				'type' => 'text',
        				'instructions' => 'Voer de URL van de Flickr-pagina in.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '3',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => 
        			array (
        				0 => 
        				array (
        					'param' => 'options_page',
        					'operator' => '==',
        					'value' => 'acf-options',
        					'order_no' => '0',
        				),
        			),
        			'allorany' => 'all',
        		),
        		'options' => 
        		array (
        			'position' => 'normal',
        			'layout' => 'default',
        			'hide_on_screen' => 
        			array (
        			),
        		),
        		'menu_order' => 20,
        	));
        	
        	register_field_group(array (
        		'id' => '5089650b6b9c8',
        		'title' => 'Administratief',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_50895e1df05dc',
        				'label' => 'KvK-nummer',
        				'name' => 'kvk',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_50895e1df07ef',
        				'label' => 'BTW-nummer',
        				'name' => 'btw',
        				'type' => 'text',
        				'instructions' => '',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '1',
        			),
        			2 => 
        			array (
        				'key' => 'field_50895e1df099b',
        				'label' => 'Bankgegevens',
        				'name' => 'bank',
        				'type' => 'text',
        				'instructions' => 'Inclusief banknaam en nummer.',
        				'required' => '0',
        				'default_value' => '',
        				'formatting' => 'none',
        				'order_no' => '2',
        			),
        			3 => 
        			array (
        				'key' => 'field_50895ef29a6c2',
        				'label' => 'Algemene voorwaarden',
        				'name' => 'terms',
        				'type' => 'file',
        				'instructions' => 'Voeg hier het digitale formaat van de algemene voorwaarden toe indien beschikbaar.',
        				'required' => '0',
        				'save_format' => 'object',
        				'order_no' => '3',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => 
        			array (
        				0 => 
        				array (
        					'param' => 'options_page',
        					'operator' => '==',
        					'value' => 'acf-options',
        					'order_no' => '0',
        				),
        			),
        			'allorany' => 'all',
        		),
        		'options' => 
        		array (
        			'position' => 'normal',
        			'layout' => 'default',
        			'hide_on_screen' => 
        			array (
        			),
        		),
        		'menu_order' => 30,
        	));
        	
        	
        	// Only for pages
        	register_field_group(array (
        		'id' => '5089653d2b3b3',
        		'title' => 'acf-options',
        		'fields' => 
        		array (
        			0 => 
        			array (
        				'key' => 'field_508952ff0b50c',
        				'label' => 'Tonen in navigatie',
        				'name' => 'show_in_navigation',
        				'type' => 'radio',
        				'instructions' => 'Deze pagina tonen in de navigatiestructuur van de website?',
        				'required' => '1',
        				'choices' => 
        				array (
        					1 => 'Ja',
        					0 => 'Nee',
        				),
        				'default_value' => 1,
        				'layout' => 'vertical',
        				'order_no' => '0',
        			),
        			1 => 
        			array (
        				'key' => 'field_50895bebc8dd2',
        				'label' => 'Doorlink',
        				'name' => 'page_link',
        				'type' => 'post_object',
        				'instructions' => 'Voer hier een mogelijk doorlink in naar een andere pagina.',
        				'required' => '0',
        				'post_type' => 
        				array (
        					0 => 'page',
        				),
        				'taxonomy' => 
        				array (
        					0 => 'all',
        				),
        				'allow_null' => '1',
        				'multiple' => '0',
        				'order_no' => '1',
        			),
        		),
        		'location' => 
        		array (
        			'rules' => 
        			array (
        				0 => 
        				array (
        					'param' => 'post_type',
        					'operator' => '==',
        					'value' => 'page',
        					'order_no' => '0',
        				),
        			),
        			'allorany' => 'all',
        		),
        		'options' => 
        		array (
        			'position' => 'side',
        			'layout' => 'default',
        			'hide_on_screen' => 
        			array (
        			),
        		),
        		'menu_order' => 10,
        	));
        }

                
    }
    
        
}      