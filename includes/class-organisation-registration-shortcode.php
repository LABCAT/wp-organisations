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
	 * Form steps.
	 *
	 * @access protected
	 * @var array
	 */
	protected $steps = [];

	/**
	 * Current form step.
	 *
	 * @access protected
	 * @var int
	 */
	protected $step = 0;
	
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
		add_filter( 'job_manager_shortcodes',  [ $this, 'update_job_manager_shortcodes' ] );
		add_action( 'wp_ajax_availability_check', [ $this, 'availability_check' ] );
		add_action( 'wp_ajax_nopriv_availability_check', [ $this, 'availability_check' ] );
		add_action( 'wp', [ $this, 'process_form_submission' ] );
	}

	public function update_job_manager_shortcodes( $wpjm_shortcodes ) {
		array_push($wpjm_shortcodes, 'org_registration_form');
		return $wpjm_shortcodes;
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
			echo '
				<noscript>You need to enable JavaScript to view this form.</noscript>
				<div 
					id="org-registration-form" 
					data-ajax-url="' . admin_url( 'admin-ajax.php' ) . '" 
					data-nonce="' . wp_create_nonce( 'wp-register-organistion' ) .'"
					data-action="' . esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ) .'"
				>
				</div>';
		}
		return ob_get_clean();
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

	public function process_form_submission() {
		try {
			if ( empty( $_POST['register-organisation'] ) ) {
				return;
			}

			$this->check_registration_form_nonce_field();
		
			// create user account
			$input_email        = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : false;
			$input_first_name   = isset( $_POST['first-name'] ) ? sanitize_text_field( wp_unslash( $_POST['first-name'] ) ) : false;
			$input_last_name    = isset( $_POST['last-name'] ) ? sanitize_text_field( wp_unslash( $_POST['last-name'] ) ) : false;
			$input_password     = isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : false;
			$create_account = false;

			if ( empty( $input_email ) ) {
				throw new Exception( __( 'Please enter a email.', 'wp-job-manager' ) );
			}

			if ( empty( $input_first_name ) ) {
				throw new Exception( __( 'Please enter a first name.', 'wp-job-manager' ) );
			}

			if ( empty( $input_last_name ) ) {
				throw new Exception( __( 'Please enter a last name.', 'wp-job-manager' ) );
			}

			if ( empty( $input_password ) ) {
				throw new Exception( __( 'Please enter a password.', 'wp-job-manager' ) );
			}

			$create_account = wp_job_manager_create_account(
				[
					'username' => '',
					'password' => $input_password,
					'email'    => $input_email,
					'role'     => get_option( 'job_manager_registration_role' ),
				]
			);

			if ( is_wp_error( $create_account ) ) {
				throw new Exception( $create_account->get_error_message() );
			}

			$user = get_user_by('email', $input_email );
			update_user_meta( $user->ID, 'first_name', $input_first_name );
			update_user_meta( $user->ID, 'last_name', $input_last_name );

			//create organisation profile
			$input_organisation    = isset( $_POST['organisation'] ) ? sanitize_text_field( wp_unslash( $_POST['organisation'] ) ) : false;
			$input_org_type        = isset( $_POST['org-type'] ) ? intval( wp_unslash( $_POST['org-type'] ) ) : false;
			$input_org_size        = isset( $_POST['org-size'] ) ? intval( wp_unslash( $_POST['org-size'] ) ) : false;

			if ( empty( $input_organisation ) ) {
				throw new Exception( __( 'Please enter an organisation.', 'wp-job-manager' ) );
			}

			if ( ! $input_org_type ) {
				throw new Exception( __( 'Please select an organisation type.', 'wp-job-manager' ) );
			}

			if ( ! $input_org_size ) {
				throw new Exception( __( 'Please select an organisation size.', 'wp-job-manager' ) );
			}

			$organisation_id = wp_insert_post( 
				[
					'post_author' => $user->ID,
					'post_title' => $input_organisation,
					'post_type' => 'organisation'
				],
				true 
			);

			if ( is_wp_error( $organisation_id ) ) {
				throw new Exception( $organisation->get_error_message() );
			}

			wp_set_post_terms( $organisation_id, [ $input_org_type ], 'organisation_type' );
			wp_set_post_terms( $organisation_id, [ $input_org_size ], 'organisation_size' );

			echo '
				<div class="job-manager-message">
					Organisation successfully registered.
				</div>';
			return;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			return;
		}
	}

	/**
	 * Check the nonce field on the submit form.
	 */
	public function check_registration_form_nonce_field() {
		if (
			empty( $_REQUEST['wp-orgs_nonce'] )
			|| ! wp_verify_nonce( wp_unslash( $_REQUEST['wp-orgs_nonce'] ), 'wp-register-organistion' ) 
		) {
			wp_die( 
				'<div class="wp-die-message">Invalid security token detected, please try again and contact the administrator if you continue to experience issues.</div>',
				'Invalid security token detected.',
				403
			);
			die();
		}
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
			$types[] = [
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
			$sizes[] = [
				'ID'            => $term->term_id,
				'key'           => $term->slug,
				'label'         => $term->name,
			];
        }

        return $sizes;
   }

	/**
	 * Enqueue the scripts for the form.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'wp-organisations', WPO_PLUGIN_URL . '/assets/dist/js/main.js', [], false, false );
		wp_enqueue_style( 'wp-job-manager-frontend' );

        wp_localize_script(
            'wp-organisations',
            'wpOrgsData',
            [
                'orgTypes' => $this->get_organisation_types(),
                'orgSizes' => $this->get_organisation_sizes(),
            ]
        );
	}
}

OrganisationRegistration::instance();
