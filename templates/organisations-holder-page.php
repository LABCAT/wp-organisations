<?php
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
                    foreach ( $job_listers as $job_lister ) {
                        $image = get_stylesheet_directory_uri() . '/assets/images/awaiting-photo.jpg';
                        $profile_picture_id = get_user_meta( $job_lister->ID, 'profile_picture', true );
                        if ($profile_picture_id) {
                            $image = wp_get_attachment_url( $profile_picture_id );
                        }

                        $logo_url = get_job_listers_logo_url( $job_lister->ID );

                        if($job_lister->nickname){
                            $organisation_name = $job_lister->nickname;
                        }
                        else {
                            $organisation_name = $job_lister->display_name;
                        }

                        $organisation_type = get_user_meta( $job_lister->ID, 'organisation_type', true );
                        $organisation_size = get_user_meta( $job_lister->ID, 'organisation_size', true );
                        $focus_area = wp_get_post_terms( $job_lister->ID, 'area-of-focus' );
                        if( is_wp_error( $focus_area ) ){
                            $focus_area = '';
                        }
                        else {
                            $focus_area = !empty( $focus_area ) ? $focus_area[0]->name : '';
                        }
                        ?>
                        <div class="card" data-type="<?php echo $organisation_type; ?>" data-size="<?php echo $organisation_size; ?>" data-area="<?php echo $focus_area; ?>">
                            <div class="card-inner">
                                <a href="<?php echo get_author_posts_url( $job_lister->ID ); ?>" class="grid-thumbnail" style="background-image: url(<?php echo $image; ?>);">
                                </a>
                                <?php
                                    if($logo_url){
                                        ?>
                                        <span class='logo-holder'>
                                            <img src="<?php echo esc_attr($logo_url); ?>" class='logo' />
                                        </span>
                                        <?php
                                    }
                                ?>
                                <?php
                                    $organisation_type = get_user_meta( $job_lister->ID, 'organisation_type', true );
                                    if($organisation_type){
                                        $organisation_type = ucwords(str_replace('_', ' ', $organisation_type));
                                        echo '<h4>' . $organisation_type . '</h4>';
                                    }
                                    else {
                                        echo '<div class="org-type-placeholder"></div>';
                                    }
                                ?>
                                <h3>
                                    <a href="<?php echo get_author_posts_url( $job_lister->ID ); ?>" >
                                        <span><?php echo $organisation_name; ?></span>
                                    </a>
                                </h3>
                            </div>
                        </div>
                        <?php
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
