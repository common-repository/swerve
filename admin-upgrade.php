<?php
/**
 * @package Swerve
 */


global $swerve_version;
$swerve_version = "3.0.0";

function swerve_upgrade() 
{
    global $wpdb;
    global $swerve_version;

    $screens = array( 'post', 'page' );
    $args = array(
        'posts_per_page'    => -1,
        'post_type'         => $screens,
        'meta_query'        => array(
            'relation'      => 'OR',
            array(
                'key'       => '_mkdo_swerve_aliases_key'
            ),
            array(
                'key'       => '_mkdo_swerve_previous_permalink_key'
            ),
        )
    );
    $posts = get_posts( $args );

    foreach( $posts as $post )
    {
        $aliases = get_post_meta( $post->ID, '_swerve_aliases', true );
        $old_aliases = get_post_meta( $post->ID, '_mkdo_swerve_aliases_key', true );
        $old_permalinks = get_post_meta( $post->ID, '_mkdo_swerve_previous_permalink_key', true );
        if( !is_array( $aliases ) )
        {
            $aliases = array();
        }
        
        foreach( $old_aliases as $old_alias )
        {
            if( !in_array( $old_alias, $aliases ) )
            {
                $aliases[] = $old_alias;
            }
        }

        foreach( $old_permalinks as $old_permalink )
        {
            if( !in_array( $old_permalink, $aliases ) )
            {
                $aliases[] = $old_permalink;
            }
        }

        if( count( $aliases ) > 0 )
        {
            update_post_meta( $post->ID, '_swerve_aliases', $aliases );
        }
    }

    update_option( "swerve_version", $swerve_version );
}

function swerve_version_check() 
{
    global $swerve_version;

    if ( get_site_option( 'swerve_version' ) != $swerve_version ) 
    {
        swerve_upgrade();
    }
}
add_action( 'plugins_loaded', 'swerve_version_check' );
?>