<?php
/**
 * @class     DoGoodJobs\PostTypes\Organisation
 * @Version: 1.0.0
 * @package   DoGoodJobs\PostTypes
 * @category  Class
 * @author    MySite Digital
 */
namespace DoGoodJobs\PostTypes;

if ( ! defined('ABSPATH')) exit;  // if direct access 

class Organisation{
	
	public function __construct(){
		add_action( 'init', [ $this, 'job_bm_posttype_job' ], 0 );
    }
	
	public function job_bm_posttype_job(){
		if ( post_type_exists( "organisation" ) ) {
            return;
        }

        $singular  = __( 'Organisation', 'wp-organisations' );
		$plural    = __( 'Organisations', 'wp-organisations' );
	 
		register_post_type( "organisation",
			apply_filters( "wpo_post_type_organisation", 
                [
                    'labels' => [
                        'name' 					=> $plural,
                        'singular_name' 		=> $singular,
                        'menu_name'             => $singular,
                        'all_items'             => sprintf( __( 'All %s', 'wp-organisations' ), $plural ),
                        'add_new' 				=> sprintf( __( 'Add %s', 'wp-organisations' ), $singular ),
                        'add_new_item' 			=> sprintf( __( 'Add %s', 'wp-organisations' ), $singular ),
                        'edit' 					=> __( 'Edit', 'wp-organisations' ),
                        'edit_item' 			=> sprintf( __( 'Edit %s', 'wp-organisations' ), $singular ),
                        'new_item' 				=> sprintf( __( 'New %s', 'wp-organisations' ), $singular ),
                        'view' 					=> sprintf( __( 'View %s', 'wp-organisations' ), $singular ),
                        'view_item' 			=> sprintf( __( 'View %s', 'wp-organisations' ), $singular ),
                        'search_items' 			=> sprintf( __( 'Search %s', 'wp-organisations' ), $plural ),
                        'not_found' 			=> sprintf( __( 'No %s found', 'wp-organisations' ), $plural ),
                        'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'wp-organisations' ), $plural ),
                        'parent' 				=> sprintf( __( 'Parent %s', 'wp-organisations' ), $singular )
                    ],
                    'description' => sprintf( __( 'This is where you can create and manage %s.', 'wp-organisations' ), $plural ),
                    'public' 				=> false,
                    'show_ui' 				=> true,
                    'capability_type' 		=> 'post',
                    'map_meta_cap'          => true,
                    'publicly_queryable' 	=> true,
                    'exclude_from_search' 	=> false,
                    'hierarchical' 			=> false,
                    'rewrite' 				=> true,
                    'query_var' 			=> true,
                    'supports' 				=> array('title','editor','author'),
                    'show_in_nav_menus' 	=> true,
                    'menu_icon' => 'dashicons-megaphone',
                ]
            )
		); 
	 
	 
    }
}
	

new Organisation();