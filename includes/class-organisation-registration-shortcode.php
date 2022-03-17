<?php
/**
 * @class     DoGoodJobs\ShortCodes\OrganisationRegistration
 * @Version: 1.0.0
 * @package   DoGoodJobs\PostTypes
 * @category  Class
 * @author    MySite Digital
 */
namespace DoGoodJobs\ShortCodes;


/**
 * Handles the shortcodes for WP Job Manager.
 *
 * @since 1.0.0
 */
class OrganisationRegistration {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.0.0
	 */
	private static $instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.0.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'org_registration_form', [ $this, 'org_registration_form' ] );
	}

	/**
	 * Outputs the div required by react to display the Organisations Registration Form
	 *
	 * @param array $atts
	 * @return string|null
	 */
	public function org_registration_form( $atts = [] ) {
		$this->enqueue_scripts();
		ob_start();
		if( is_user_logged_in() ) {
			echo '<div class="job-manager-error">You are already registered.</div>';
		}
		else {
			echo '<div id="org-registration-form"></div>';
		}
		return ob_get_clean();
	}

	/**
	 * Enqueue the scripts for the form.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'wp-organisations', WPO_PLUGIN_URL . '/assets/dist/js/main.js', [], false, false );
	}
}

OrganisationRegistration::instance();
