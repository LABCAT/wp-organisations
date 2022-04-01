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

        $singular = __( 'Organisation Type', 'wp-organisations' );
        $plural   = __( 'Organisation Types', 'wp-organisations' );

        register_taxonomy(
            'organisation_type',
            'organisation',
            apply_filters(
                'register_taxonomy_organisation_organisation_type_args',
                [
                    'hierarchical'          => false,
                    'update_count_callback' => '_update_post_term_count',
                    'label'                 => $plural,
                    'labels'                => [
                        'name'              => $plural,
                        'singular_name'     => $singular,
                        'menu_name'         => ucwords( $plural ),
                        // translators: Placeholder %s is the plural label of the job listing category taxonomy type.
                        'search_items'      => sprintf( __( 'Search %s', 'wp-organisations' ), $plural ),
                        // translators: Placeholder %s is the plural label of the job listing category taxonomy type.
                        'all_items'         => sprintf( __( 'All %s', 'wp-organisations' ), $plural ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'parent_item'       => sprintf( __( 'Parent %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'edit_item'         => sprintf( __( 'Edit %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'update_item'       => sprintf( __( 'Update %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'add_new_item'      => sprintf( __( 'Add New %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'new_item_name'     => sprintf( __( 'New %s Name', 'wp-organisations' ), $singular ),
                    ],
                    'show_ui'               => true,
                    'show_tagcloud'         => false,
                    //'show_in_quick_edit'    => false,
                    //'meta_box_cb'           => false,
                    'public'                => false,
                    'show_in_rest'          => false,

                ]
            )
        );

        $singular = __( 'Organisation Size', 'wp-organisations' );
        $plural   = __( 'Organisation Sizes', 'wp-organisations' );

        register_taxonomy(
            'organisation_size',
            'organisation',
            apply_filters(
                'register_taxonomy_organisation_organisation_size_args',
                [
                    'hierarchical'          => false,
                    'update_count_callback' => '_update_post_term_count',
                    'label'                 => $plural,
                    'labels'                => [
                        'name'              => $plural,
                        'singular_name'     => $singular,
                        'menu_name'         => ucwords( $plural ),
                        // translators: Placeholder %s is the plural label of the job listing category taxonomy type.
                        'search_items'      => sprintf( __( 'Search %s', 'wp-organisations' ), $plural ),
                        // translators: Placeholder %s is the plural label of the job listing category taxonomy type.
                        'all_items'         => sprintf( __( 'All %s', 'wp-organisations' ), $plural ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'parent_item'       => sprintf( __( 'Parent %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'edit_item'         => sprintf( __( 'Edit %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'update_item'       => sprintf( __( 'Update %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'add_new_item'      => sprintf( __( 'Add New %s', 'wp-organisations' ), $singular ),
                        // translators: Placeholder %s is the singular label of the job listing category taxonomy type.
                        'new_item_name'     => sprintf( __( 'New %s Name', 'wp-organisations' ), $singular ),
                    ],
                    'show_ui'               => true,
                    'show_tagcloud'         => false,
                    //'show_in_quick_edit'    => false,
                    //'meta_box_cb'           => false,
                    'public'                => false,
                    'show_in_rest'          => false,

                ]
            )
        );
    }
}
	

new Organisation();