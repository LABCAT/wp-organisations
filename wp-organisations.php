<?php
/**
 * Plugin Name: WP Organisations
 * Description: 
 * Version: 1.0.0
 * Author: Shane Watters
 * Author URI: https://dogoodjobs.co.nbz
 * Requires at least: 5.3
 * Tested up to: 5.3
 */
namespace DoGoodJobs;

if ( ! defined('ABSPATH')) exit;  // if direct access 

/**
 * Main WPOrganisations Class.
 *
 * @class WPOrganisations
 * @version    1.0.0
 */

final class WPOrganisations {

    /**
     * WPOrganisations Constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
    }

    /*
        *
        * Define DiningDashboard Constants.
        */
    private function define_constants() {
        if ( ! defined( 'WPO_PLUGIN_PATH' ) ) {
            define( 'WPO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        }
        if ( ! defined( 'WPO_PLUGIN_URL' ) ) {
            define( 'WPO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        }
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes()
    {
        include_once( WPO_PLUGIN_PATH . 'includes/class-organisation-post-type.php' );
        include_once( WPO_PLUGIN_PATH . 'includes/class-organisation-registration-shortcode.php' );
    }

}

new WPOrganisations();
