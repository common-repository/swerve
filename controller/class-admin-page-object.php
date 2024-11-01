<?php

//Set namespace
namespace mkdo\swerve;

/**
 * Admin_Post_Object
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class Admin_Post_Object {  

	/**
     * Get the WordPress $post object
     *
     * @return object $post the WordPress post object
     * @throws boolean false throws false if the $post object cannot be returned 
     */
	public static function get_post_object()
	{
		//TOOD: Better error handling
		//TODO: Querystring escaping

		//If the post id is in the querystring get it and return the post object
		//else if the post id is in the post data get it and return the post object
		//else throw an exception and return false
		if( isset( $_GET['post'] ) )
		{
			$post_id = absint( $_GET['post'] );
			$post = get_post( $post_id );
			
			return $post;
		}
		elseif( isset( $_POST['post_ID'] ) )
		{
			$post_id = absint( $_POST['post_ID'] );
			$post = get_post( $post_id) ;
			
			return $post;
		}
		else
		{
			return false;
		}
	}
}