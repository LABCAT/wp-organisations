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
		add_action( 'wp_ajax_availability_check', [ $this, 'availability_check' ] );
		add_action( 'wp_ajax_nopriv_availability_check', [ $this, 'availability_check' ] );
	}

	public function get_organisation_types(){
        $types = [];

        $terms = get_terms( 
            [
                'taxonomy' => 'organisation_type',
                'hide_empty' => false,
            ]
        );

        foreach ( $terms as $term ) {
			$types[ $term->slug ] = [
				'ID'            => $term->term_id,
				'key'           => $term->slug,
				'label'         => $term->name,
			];
        }

        return $types;
   }

   public function get_organisation_sizes(){
        $sizes = [];

        $terms = get_terms( 
            [
                'taxonomy' => 'organisation_size',
                'hide_empty' => false,
            ]
        );

        foreach ( $terms as $term ) {
			$sizes[ $term->slug ] = [
				'ID'            => $term->term_id,
				'key'           => $term->slug,
				'label'         => $term->name,
			];
        }

        return $sizes;
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
			echo '<div id="org-registration-form" data-ajax-url="' . admin_url( 'admin-ajax.php' ) . '"></div>';
		}
		return ob_get_clean();
	}

	/**
	 * Enqueue the scripts for the form.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'wp-organisations', WPO_PLUGIN_URL . '/assets/dist/js/main.js', [], false, false );

        wp_localize_script(
            'wp-organisations',
            'wpOrgsData',
            [
                'orgTypes' => json_encode( $this->get_organisation_types() ),
                'orgSizes' => json_encode( $this->get_organisation_sizes() ),
            ]
        );
	}

	/**
	 * Function call via JS to check if user account is available and organisation name is unique
	 */
	public function availability_check() {
		
		if ( isset($_REQUEST) ) {
			if ( ! isset($_REQUEST['email']) || ! isset($_REQUEST['organisation']) ) {
				echo json_encode(['error' => 'Email and organisation fields are required']);
    			wp_die();
			}

			$email = sanitize_email( $_REQUEST['email'] );
			$organisation = sanitize_text_field( $_REQUEST['organisation'] );
			echo json_encode(
				[
					'emailAvailable' => ! email_exists( $email ),
					'orgAvailable' => ! post_exists( $organisation, '', '', 'organisation'),
				]
			);
			wp_die();
    	}

		echo json_encode(['error' => 'There was an error handling your request, please contact the administrator']);
    	wp_die();
	}
}

OrganisationRegistration::instance();
