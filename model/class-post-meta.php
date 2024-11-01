<?php

//Set namespace
namespace mkdo\swerve;

/**
 * post_meta
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class Post_Meta {
	
	//Declare class variables
	private $post_id;
	private $post_meta_key;
	private $single;
	private $post_meta_value;

	/**
     * Getter for $post_id
     */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
     * Getter for $post_meta_key
     */
	public function get_post_meta_key() {
		return $this->post_meta_key;
	}

	/**
     * Getter for $single
     */
	public function is_single() {
		return $this->single;
	}

	/**
     * Getter for $post_meta_value
     */
	public function get_post_meta_value() {
		return $this->post_meta_value;
	}

	/**
     * Get the WordPress $post object
     *
     * @param int $post_id the WordPress post ID
     * @param string $meta_key the meta data key
     * @param boolean $is_single if this should return a single post or an array of posts
     */
	public function __construct( $post_id, $meta_key, $is_single )
	{
		//Set class variables
		$this->post_id = $post_id;
		$this->post_meta_key = $meta_key;
		$this->single = $is_single;
		$this->post_meta_value = $this->get();
	}

	/**
     * Gets the meta data
     *
     * @return string/array single or array of meta data
     */
	public function get()
	{
		return get_post_meta( $this->get_post_id(), $this->get_post_meta_key(), $this->is_single() );
	}

	/**
     * Update the meta key
     *
     * @param string $new_meta_value the WordPress post ID
     */
	public function update( $new_post_meta_value )
	{
		//TODO: Add clensing here
		//If the meta value does not exist add it
		if ( $new_post_meta_value && '' == $this->get_post_meta_value() )
		{
			$this->add( $new_post_meta_value );
		}
		//Else if the new meta value does not match the old meta value, update it
		elseif ( $new_post_meta_value && $new_post_meta_value != $this->get_post_meta_value() )
		{
			update_post_meta( $this->get_post_id(), $this->get_post_meta_key(), $new_post_meta_value );
		}
		//Else if there is no new meta value but an old value exists, delete it
		elseif ( ( empty( $new_post_meta_value)  || '' == $new_post_meta_value ) && $this->get_post_meta_value() )
		{
			$this->delete();
		}

	}

	/**
     * Delete the meta key
     */
	public function delete()
	{
		delete_post_meta( $this->get_post_id(), $this->get_post_meta_key(), $this->get_post_meta_value() );
	}

	/**
     * Add the meta key
     *
     * @param string $new_meta_value the WordPress post ID
     */
	public function add( $new_meta_value )
	{
		add_post_meta( $this->get_post_id(), $this->get_post_meta_key(), $new_meta_value, true );
	}
}
