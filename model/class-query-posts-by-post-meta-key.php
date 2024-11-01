<?php

//Set namespace
namespace mkdo\swerve;

/**
 * Query_Posts_By_Post_Meta_Key
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class Query_Posts_By_Post_Meta_Key {

	//Define class variables
	private $post_meta_key;

	/**
     * Getter for $post_meta_key
     */
	public function get_post_meta_key() {
		return $this->post_meta_key;
	}

	/**
     * SThe constuctor for the Query_Posts_By_Post_Meta_Key class
     *
     * @param string $meta_key the meta data key
     */
	function __construct( $post_meta_key )
	{
		$this->post_meta_key = $post_meta_key;
	}

	/**
     * Get the posts from the meta data
     *
     * @return array of posts
     */
	function get_posts()
	{
		global $wpdb;

		return 	$wpdb->get_results( 
					$wpdb->prepare(	
						"SELECT posts.*
						FROM $wpdb->posts posts, $wpdb->postmeta postmeta
						WHERE posts.ID = postmeta.post_id
						AND postmeta.meta_key = %s
						ORDER BY postmeta.meta_value DESC", $this->get_post_meta_key()
					)
				, OBJECT );
	}

}
