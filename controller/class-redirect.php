<?php

//Set namespace
namespace mkdo\swerve;

/**
 * Redirect
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class Redirect {  

	//Define class variables
	private $post_meta_key_previous_permalinks;
	private $post_meta_key_aliases;
	private $url_current;

	/**
     * Getter for $post_meta_key_previous_permalinks
     */
	public function get_post_meta_key_previous_permalinks() {
		return $this->post_meta_key_previous_permalinks;
	}

	/**
     * Getter for $post_meta_key_aliases
     */
	public function get_post_meta_key_aliases() {
		return $this->post_meta_key_aliases;
	}

  	/**
   	 * Getter for $url_previous
   	 */
  	public function get_url_current() {
    	return $this->url_current;
  	}


	/**
     * The constuctor for the Redirect class
     */
	public function __construct( $main )
	{
		$this->url_current = URL_Helper::remove_qs_and_slashes( sanitize_key( $_SERVER['REQUEST_URI'] ) );
		$this->post_meta_key_previous_permalinks = $main->get_post_meta_key_previous_permalinks();
		$this->post_meta_key_aliases = $main->get_post_meta_key_aliases();

		//TODO:admin option to enable this if required
		//stop WordPress trying to guess URL (canonical url)
		remove_filter( 'template_redirect', 'redirect_canonical' );
		
		//TODO:admin option to enable this if required
		//force a 404 if previous permalink not found
		remove_action( 'template_redirect', 'wp_old_slug_redirect' );

		//add permalink redirects
		add_filter( 'template_redirect', array( $this, 'override_404' ));
	}

	/**
     * If there is a 404 do the following actions
     */
	public function override_404( $main ) 
	{
		//TODO: Optimise this

	    global $wp_query;

	    if( $wp_query->is_404 )
	    {
			$posts_by_meta_key = new Query_Posts_By_Post_Meta_Key( $this->get_post_meta_key_previous_permalinks() );

			$posts = $posts_by_meta_key->get_posts();

			foreach( $posts as $post )
			{
				$post_meta_array = get_post_meta( $post->ID, $this->get_post_meta_key_previous_permalinks(), true );

				if( is_array( $post_meta_array ) )
				{
					if( in_array( $this->get_url_current(), $post_meta_array ) )
					{
						status_header( 301 );
        				$wp_query->is_404=false;
						wp_redirect( get_page_link( $post->ID ), 301 );
						exit;
					}
				}
			}

			$posts_by_meta_key = new Query_Posts_By_Post_Meta_Key( $this->get_post_meta_key_aliases() );

			$posts = $posts_by_meta_key->get_posts();

			foreach( $posts as $post )
			{
				$post_meta_array = get_post_meta( $post->ID, $this->get_post_meta_key_aliases(), true );

				if( is_array( $post_meta_array ) )
				{
					if( in_array( $this->get_url_current(), $post_meta_array ) )
					{
						status_header( 301 );
        				$wp_query->is_404=false;
						wp_redirect( get_page_link( $post->ID ), 301 );
						exit;
					}
				}
			}
		}
	}
}

