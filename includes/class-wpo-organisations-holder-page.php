<?php
/**
 * @class     PostTypes\Pages\OrganisationsHolder
 * @Version: 0.0.1
 * @package   MySiteDigital\PostTypes\Pages
 * @category  Class
 * @author    MySite Digital
 */
namespace MySiteDigital\PostTypes\Pages;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * OrganisationsHolder Class.
 */
class OrganisationsHolder {

    private $templates = [
        'organisations-holder-page.php' => 'Organisations Holder',
    ];

    public function __construct()
    {
        add_filter( 
            'theme_page_templates', 
            [ $this, 'add_template_to_dropdown' ] 
        );

        add_filter(
            'wp_insert_post_data', 
            [ $this, 'register_my_template' ] 
        );

        add_filter(
            'template_include', 
            [ $this, 'load_my_template']
        );

        add_action( 'add_meta_boxes', [ $this, 'my_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_post' ], 1, 2 );
    }

    /**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_template_to_dropdown( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

    public function register_my_template( $atts ) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list. 
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        } 

        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( 
            $templates, 
            $this->templates
        );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public function load_my_template( $template ) {
        
        // Get global post
        global $post;

        // Return template if post is empty
        if ( ! $post ) {
            return $template;
        }

        $current_template = get_post_meta( $post->ID, '_wp_page_template', true );

        // Return default template if we don't have a custom one defined
        if ( ! isset( $this->templates[ $current_template ] ) ) {
            return $template;
        } 

        $file = WPO_PLUGIN_PATH . 'templates/' . $current_template;

        // Just to be safe, we check if the file exist first
        if ( file_exists( $file ) ) {
            return $file;
        } 

        // Return template
        return $template;

    }

    public function my_meta_boxes() {
        global $post;
        $template = get_page_template_slug( $post->ID );
        if( $template == 'organisations-holder-page.php' ){
            add_meta_box( 'page-promo-text', 'Promo Text', [ $this, 'promo_text_metabox' ], 'page', 'normal', 'high' );
        }
    }

    public function promo_text_metabox(){
        global $post;
        $promo_text = get_post_meta( $post->ID, '_promo_text_content', true );
        wp_nonce_field( 'wpo_save_data', 'wpo_meta_nonce' );
        wp_editor( $promo_text, '_promo_text_content');
    }

    public function save_post( $post_id, $post ) {
        // $post_id and $post are required
        if ( empty( $post_id ) || empty( $post )) {
            return;
        }

        // Dont' save meta boxes for revisions or autosaves
        if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
            return;
        }


        // Check the nonce
        if ( empty( $_POST['wpo_meta_nonce'] ) || ! wp_verify_nonce( $_POST['wpo_meta_nonce'], 'wpo_save_data' ) ) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events
        if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
            return;
        }

        // Check user has permission to edit
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if( isset( $_POST[ '_promo_text_content' ] ) ){
            update_post_meta( $post_id, '_promo_text_content', $_POST['_promo_text_content'] );
        }

    }

}

new OrganisationsHolder();
