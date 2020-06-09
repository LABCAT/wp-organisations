<?php
/**
 * @class     Users\OrganisationUser
 * @Version: 0.0.1
 * @package   MySiteDigital\Users
 * @category  Class
 * @author    MySite Digital
 */
namespace MySiteDigital\Users;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * OrganisationUser Class.
 */
class OrganisationUser {

    public static function has_current_job_listing( $job_lister_id ){
        $current_jobs = get_posts( 
            [
                'post_type' 		=> 'job_listing',
                'post_status' 		=> 'publish',
                'author' 			=> $job_lister_id,
            ]
        );
        return $current_jobs ? count( $current_jobs ) : 0;
    }
}

new OrganisationUser();
