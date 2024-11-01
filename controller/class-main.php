<?php

	//Set namespace
	namespace mkdo\swerve;

	//Include dependent classes
	include_once( MKDO_SWERVE_PATH . 'controller/class-admin-page-object.php' );
	include_once( MKDO_SWERVE_PATH . 'controller/class-meta-box-aliases.php' );
	include_once( MKDO_SWERVE_PATH . 'controller/class-meta-box-previous-permalinks.php' );
	include_once( MKDO_SWERVE_PATH . 'controller/class-redirect.php' );
	include_once( MKDO_SWERVE_PATH . 'controller/class-update-url.php' );
	include_once( MKDO_SWERVE_PATH . 'controller/class-url-helper.php' );

	include_once( MKDO_SWERVE_PATH . 'model/class-post-meta.php' );
	include_once( MKDO_SWERVE_PATH . 'model/class-query-posts-by-post-meta-key.php' );

	/**
	 * Main
	 * 
	 * @package    swerve
	 * @author     MKDO Ltd. <hello@mkdo.co.uk>
	 */
	class Main {  

		//Define class variables
		private $post_object;
		private $plugin_url;
		private $post_meta_key_previous_permalinks;
		private $post_meta_key_aliases;

		//Define getters and setters

		/**
	     * Getter for $page_meta
	     */
		public function get_post_object() {
			return $this->post_object;
		}

		/**
	     * Getter for $plugin_url
	     */
		public function get_plugin_url() {
			return $this->plugin_url;
		}

		/**
	     * Getter for $previous_permalink_key
	     */
		public function get_post_meta_key_previous_permalinks() {
			return $this->post_meta_key_previous_permalinks;
		}

		/**
	     * Getter for $previous_permalink_key
	     */
		public function get_post_meta_key_aliases() {
			return $this->post_meta_key_aliases;
		}

		/**
	     * The constuctor for the Main class
	     */
		public function __construct() {

			//Set class variables
			$this->post_object = Admin_Post_Object::get_post_object();
			$this->plugin_url = plugin_dir_url( dirname(__FILE__) );
			
			//TODO: Rename this on upgrade to _mkdo_swerve_post_meta_key_previous_permalinks
			$this->post_meta_key_previous_permalinks = '_mkdo_swerve_previous_permalink_key';
			$this->post_meta_key_aliases = '_mkdo_swerve_aliases_key';  

			if(is_admin())
			{

				//TODO: Installer
				//TODO:  - Check compatibility

				//TODO: Activation

				//TODO: Upgrade actions

				//load admin scripts
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

				//Run the Swerve plugin when in the admin panel
				add_action( 'admin_init', array( $this, 'on_admin_init' ) );

				//TODO: Deactive
				//TODO: Uninstall
			}
			else
			{	
				//Do the 404 redirect
				new Redirect( $this );
			}
		}

		public function on_admin_init() {

			//Render and handle the pervious permalinks meta box
			new Meta_Box_Previous_Permalinks( $this );

			//Render and handle the aliases meta box
			new Meta_Box_Aliases( $this );

			//Hande the change of post title
			new Update_URL( $this );
		}

		public function admin_scripts() {

		        wp_register_style( 'mkdo_swerve_css', $this->get_plugin_url() . 'assets/css/swerve.css', true, '1.0.0' );
		        wp_enqueue_style( 'mkdo_swerve_css' );
		        wp_register_script( 'mkdo_swerve_js', $this->get_plugin_url() . 'assets/js/swerve.js', array("jquery"), true, '1.0.0' );
		        wp_enqueue_script( 'mkdo_swerve_js' );
		}

	}

	new Main();