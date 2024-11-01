<?php
/**
 * @package Swerve
 */

/**
 * 
 * @since  		3.0.0
 * 
 * Before the normal WordPress 404 kicks in, lets check our aliases
 * 
 */
function swerve_override_404() 
{
	global $wp_query;

	if( $wp_query->is_404 )
	{
		$screens 	= array();
		$post_types = get_post_types( array('public' => true) );
		foreach( $post_types as $post_type)
		{
			if( get_option('_swerve_activate_on_' . $post_type ) === 'show' )
			{
				array_push( $screens , $post_type );
			}
		}
		
		$url = $_SERVER['REQUEST_URI'];
		$url = strtolower( $url );
		$url = rtrim( $url, '/' );

		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> $screens,
			'meta_query' 		=> array(
				array(
					'key' => '_swerve_aliases'
				)
			)
		);
		$posts = get_posts( $args );

		foreach( $posts as $post )
		{
			$aliases = get_post_meta( $post->ID, '_swerve_aliases', true );

			if( is_array( $aliases ) && in_array( $url, $aliases ) )
			{
				status_header( 301 );
				$wp_query->is_404=false;
				wp_redirect( get_page_link( $post->ID ), 301 );
				exit;
			}
		}
	}
}
add_filter( 'template_redirect', 'swerve_override_404', 1 );
?>