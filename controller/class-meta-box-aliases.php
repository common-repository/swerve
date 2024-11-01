<?php

//Set namespace
namespace mkdo\swerve;

/**
 * Meta_Box_Aliases
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class Meta_Box_Aliases {  

	//Define class variables
	private $post_meta_aliases;

	/**
     * Getter for $post_meta_aliases
     */
	public function get_post_meta_aliases() {
		return $this->post_meta_aliases;
	}

	/**
     * The constuctor for the Meta_Box_Aliases class
     *
     * @param object $post the WordPress post object
     */
	public function __construct( $main )
	{
		//Set class variables
		$this->post_meta_aliases = new Post_Meta( $main->get_post_object()->ID, $main->get_post_meta_key_aliases(), true );
		
		//Create the pervious permalink meta box
		add_action( 'add_meta_boxes', array( $this , 'add_meta_box' ) );

		//Set what we should do with the previous permalink post data
		add_action( 'save_post', array( $this , 'handle_postdata' ) );

		//Hide the previous permalink meta box by default
		//TODO: Make this customisable via options panel
		add_filter( 'default_hidden_meta_boxes', array( $this,'default_hide_meta_box' ) );
	}

	/**
     * Adds a custom meta box to the screens chosen in the Swerve options panel
     */
	public function add_meta_box() {
	    
	    $screens = array( 'post', 'page' ); 
	    //TODO: Add settings panel to chose which types this applies to
	    //TODO: Add a way to display this using template meta data
	    foreach ($screens as $screen) {
	        add_meta_box(
	            'mkdo_swerve_meta_box_aliases',
	            'Aliases', 
	            array( $this , 'render_meta_box' ),
	            $screen
	        );
	    }
	}

	/**
     * Set variables and redner the pervious permalinks custom meta box
     *
     * @param object $post the WordPress post object
     */
	public function render_meta_box( $post ) {

		//Get the value of the pervious permalink meta 
		$post_meta_value_aliases = $this->get_post_meta_aliases()->get();

		//Include the nonce field for verification
		wp_nonce_field( MKDO_SWERVE_PATH, 'mkdo_swerve_noncename' ); 

		//Render the pervious permalink meta box
		include MKDO_SWERVE_PATH . 'view/meta-box-aliases.php';  
	}

	/**
     * Action to take when the pervious permalinks box is saved
     *
     * @param int $post_id the WordPress post ID
     * @throws int $post_id thows the WordPress post ID if the user does not have permission to make changes
     */
	public function handle_postdata( $post_id ) {

		$new_post_meta_value_aliases = array();

	  	//Verify the nonce to defend against XSS
		if ( !isset( $_POST['mkdo_swerve_noncename'] ) || !wp_verify_nonce( $_POST['mkdo_swerve_noncename'], MKDO_SWERVE_PATH ) )
		{
			return $post_id;
		}

		//Check that the current user has permission to edit the post
		if ( !current_user_can( edit_post, $post_id ) )
		{
			return $post_id;
		}
		
		//Loop through the post data
		foreach($_POST as $key => $value) 
		{
		  //If the key starts with the field we are looking for
		  if(strpos($key, 'post_meta_value_alias_') === 0 ) 
		  {
		  	//Clense the url
		  	$value = URL_Helper::remove_qs_and_slashes( $value );

		  	//Push it into the array
		    array_push( $new_post_meta_value_aliases, $value );
		  }
		}

		$new_post_meta_value_alias = URL_Helper::sanitize_key_with_slashes( $_POST['post_meta_value_alias'] );

		if( !empty( $new_post_meta_value_alias ) && !in_array( $new_post_meta_value_alias, $new_post_meta_value_aliases ))
		{
			array_push( $new_post_meta_value_aliases, $new_post_meta_value_alias );
		}

		//Update the permalink meta value
		$this->get_post_meta_aliases()->update( $new_post_meta_value_aliases );
	}

	/**
     * Hide the previous permalinks box by default
     */
	public function default_hide_meta_box( $hidden ) {
	    $hidden[] = 'mkdo_swerve_meta_box_aliases';
	    return $hidden;
	}
}

