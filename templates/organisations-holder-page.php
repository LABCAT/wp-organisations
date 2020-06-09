<?php
use MySiteDigital\Users\OrganisationUser;   
/*
Template Name: Test Template
*/
$is_job_lister = false;
$current_user = wp_get_current_user();
if($current_user->ID){
    if(in_array( 'job_lister', (array) $current_user->roles ) || current_user_can('manage_options')){
        $is_job_lister = true;
    }
}
?>
    <div class="full-container">
        <div id="primary" class="content-area">
            <div id="organisations-holder" class="site-content" role="main">
                <header class="entry-header">
                    <h1 class="entry-title">Organisations</h1>
                </header>
		
                <?php
                    if (have_posts()){
                        while (have_posts()) {
                            the_post();
                            echo '<div id="content">';
                            the_content();
                            echo '</div>';
                        }
                    }
                ?>
                <div class="widget">
                    Filter:
                    <select name="organisation_type" class="organisation-filter">
                        <option value="all">All Types</option>
                        <option value="business">Business</option>
                        <option value="government">Government</option>
                        <option value="not_for_profit">Not For Profit</option>
                        <option value="consultant">Consultant</option>
                        <option value="recruiter">Recruiter</option>
                        <option value="social_enterprise">Social Enterprise</option>
                    </select>
                    <select name="organisation_size" class="organisation-filter">
                        <option value="all">All Sizes</option>
			            <option value="unknown">Unknown</option>
                        <option value="0">0</option>
                        <option value="1-5">1-5</option>
                        <option value="6-15">6-15</option>
                        <option value="16-30">16-30</option>
                        <option value="31-50">31-50</option>
                        <option value="51-100">51-100</option>
                        <option value="101-200">101-200</option>
                        <option value="200+">200+</option>
                    </select>
                    <?php
                        $areas = get_terms( 'area-of-focus', array( 'hide_empty' => true ) );
                        if(! empty($areas) ){
                            ?>
                            <select name="organisation_area" class="organisation-filter">
                                <option value="all">All Areas of Focus</option>
                                <?php
                                    foreach($areas as $area){
                                        echo '<option value="' . $area->name . '">' . $area->name . '</option>';
                                    }
                                ?>
                            </select>
                            <?php
                        }
                    ?>
                </div>
                <div id="organisations-list-notices">
                    <?php appthemes_display_notice( 'notice', 'Sorry, there are no organisations in that category yet!'); ?>
                </div>
                <div id="organisations-list" class="vantage-grid-loop">
                <?php
                    $job_listers = get_users(
                                        [
                                            'role__in' => ['job_lister', 'administrator'],
                                            'meta_query' => [
                                                'relation' => 'OR',
                                                [
                                                      'key'     => 'enable_organisations_page',
                                                      'value'   => 'on',
                                                      'compare' => '='
                                                ]
                                            ],
                                            'meta_key' => 'nickname',
                                            'orderby' => 'meta_value',
                                            'order' => 'ASC'
                                        ]
                                    );
                    $job_listers_without_current_listings = [];
                    foreach ( $job_listers as $job_lister ) {
                        $has_current_job_listing = OrganisationUser::has_current_job_listing( $job_lister->ID );
                        if( $has_current_job_listing ){
                            include WPO_PLUGIN_PATH . 'templates/organisation-card.php';
                        }
                        else {
                            $job_listers_without_current_listings[] = $job_lister;
                        }
                    }
                    foreach ( $job_listers_without_current_listings as $job_lister ) {
                        include WPO_PLUGIN_PATH . 'templates/organisation-card.php';
                    }
                ?>
                </div>
                <section id="get-listed" class="panel yellow">
                    <div>
                        <?php
                            global $post;
                            echo wpautop( get_post_meta( $post->ID, '_promo_text_content', true ) );
                            if( $is_job_lister ){
                                echo '<a href="/profile/" class="button">Update Your Profile</a>';
                            }
                            else {
                                echo '<a href="/contact/" class="button">Contact Us</a>';
                            }
                        ?>
                    </div>
                </section>
            </div>
        </div>
    </div>

</div><!-- end main content -->
