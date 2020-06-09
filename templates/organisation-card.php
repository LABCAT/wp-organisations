<?php
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