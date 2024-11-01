<?php

//Set namespace
namespace mkdo\swerve;

/**
 * update_slug_on_title_change
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class Update_URL
{
  //Define class variables
  private $post_meta_previous_permalinks;
  private $url_previous;
  private $post_object;

  /**
    * Getter for $post_meta_previous_permalinks
    */
  public function get_post_meta_previous_permalinks() {
    return $this->post_meta_previous_permalinks;
  }

  /**
    * Getter for $url_previous
    */
  public function get_url_previous() {
    return $this->url_previous;
  }

  /**
    * Getter for $post_object
    */
  public function get_post_object() {
    return $this->post_object;
  }

  /**
   * The constuctor for the update_slug_on_title_change class
   *
   * @param object $post the WordPress post object
   */
  public function __construct( $main )
  {   
      $post = $main->get_post_object();

      $this->post_object = $main->get_post_object();
      $this->post_meta_previous_permalinks = new Post_Meta( $post->ID, $main->get_post_meta_key_previous_permalinks(), true );
      $this->url_previous = URL_Helper::remove_qs_and_slashes( $this->generate_path( $post, $post->post_name ) );
      
      $this->update_post( $post->ID );
  }

  /**
   * Updates the post meta and slug on post title change
   */
  public function update_post( $post_id )
  {
    $url_previous = $this->get_url_previous();
    $post_meta_value_previous_permalinks = $this->get_post_meta_previous_permalinks()->get();

    $slug = URL_Helper::remove_qs_and_slashes( sanitize_key( str_replace( ' ', '-', $_POST['post_title'] ) ) );
    
    if( empty( $post_meta_value_previous_permalinks ) )
      $post_meta_value_previous_permalinks = array();

    //if it is just a revision don't worry about it
    if (wp_is_post_revision($post_id))
      return false;

    //Check it's not an auto save routine
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
      return;

    //Perform permission checks! For example:
    if ( !current_user_can('edit_post', $post_id) ) 
      return;

    //unhook this function so it doesn't loop infinitely
    remove_action('save_post', array( $this, 'update_post' ) );

    //TODO: Check if parent id has changed (page moved)
    //Check to see if the page slug has change
    if(isset($_POST['post_title']) && $slug != $this->get_post_object()->post_name)
    {
      //If the old url is not empty and the current meta data doesnt contain a referance to it
      if( !empty( $url_previous ) && !in_array( $url_previous, $post_meta_value_previous_permalinks ) )
      {
          //Add the old url to the array
          array_push( $post_meta_value_previous_permalinks, $url_previous );

          //update the meta data
          $this->get_post_meta_previous_permalinks()->update( $post_meta_value_previous_permalinks );
      }

      //update the post and change the post_name/slug to the post_title
      wp_update_post(array('ID' => $post_id, 'post_name' => $slug));
    }

    //re-hook this function
    add_action('save_post', array( $this, 'update_post' ) ); 
  }

  /**
    * Get the full path of the post
    */
  public function generate_path( $post, $path )
  {
      while($post->post_parent != 0)
      {
        $post = get_post($post->post_parent);
        $path = $post->post_name . '/' . $path;
      }

      return $path;
  }

}