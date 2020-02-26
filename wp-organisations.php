<?php
/**
 * Plugin Name: WP Organisations
 * Description: 
 * Version: 1.0.0
 * Author: MySite Digital
 * Author URI: https://mysite.digital
 * Requires at least: 5.3
 * Tested up to: 5.3
 */
namespace MySiteDigital;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


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
        include_once( WPO_PLUGIN_PATH . 'includes/class-wpo-organisations-holder-page.php' );
    }

}

new WPOrganisations();
