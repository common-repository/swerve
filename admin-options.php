<?php
/**
 * @package Swerve
 */

/**
 * 
 * @since  		3.0.0
 * 
 * Register the options page
 * 
 */
function swerve_add_options_page() {

	add_options_page( 'Swerve', 'Swerve', 'manage_options', 'swerve-settings', 'swerve_render_options_page' );

	add_action( 'admin_init', 'swerve_register_settings' );
}
add_action('admin_menu', 'swerve_add_options_page');




/**
 * 
 * @since  		3.0.0
 * 
 * Register the options settings
 * 
 */
function swerve_register_settings() {

	$post_types = get_post_types( array('public' => true) );

	foreach( $post_types as $post_type)
	{
		register_setting( 'swerve_group', '_swerve_activate_on_' . $post_type );
		register_setting( 'swerve_group', '_swerve_alias_meta_box_on_' . $post_type );
		register_setting( 'swerve_group', '_swerve_redirect_meta_box_on_' . $post_type );
	}

}



/**
 * 
 * @since  		3.0.0
 * 
 * Render the options page
 * 
 */
function swerve_render_options_page()
{	
	$post_types 		= get_post_types( array('public' => true) );
	sort( $post_types );

	foreach( $post_types as $post_type)
	{
		if($post_type == 'page')
		{
			if( get_option('_swerve_activate_on_' . $post_type ) === false )
			{
				add_option( '_swerve_activate_on_' . $post_type, 'show' );
			}
		}
	}

	?>
		<div class="wrap swerve_options">
			<h2>Swerve</h2>
			<form method="post" action="options.php">
			<?php 
				settings_fields( 'swerve_group' );
				do_settings_sections( 'swerve_group' );
			?>
			<table class="form-table">

				<tr valign="top">
					<th scope="row">Activate 'Swerve' on post type</th>
					<td>
						<?php
							foreach( $post_types as $post_type)
							{
								?>
									<span class="inline">
										<input type="checkbox" value="show" id="swerve_activate_on_<?php echo $post_type;?>" name="_swerve_activate_on_<?php echo $post_type;?>"<?php echo ( get_option('_swerve_activate_on_' . $post_type ) == 'show' ) ? ' checked' : '';?>>
										<label for="_swerve_activate_on_<?php echo $post_type;?>">
										<?php
											
											$obj = get_post_type_object( $post_type );
											echo $obj->labels->singular_name;
										?>
										</label>
									</span>
								<?php
							}
						?>
						<p><em>When 'activated' Swerve will keep track of any permalink changes or hierarchical moves for that post type, and automatically 301 redirect to the new URL if the old permalink is entered.</em></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Show 'Aliases' meta box on post type</th>
					<td>
						<?php
							foreach( $post_types as $post_type)
							{
								?>
									<span class="inline">
										<input type="checkbox" value="show" id="swerve_alias_meta_box_on_<?php echo $post_type;?>" name="_swerve_alias_meta_box_on_<?php echo $post_type;?>"<?php echo ( get_option('_swerve_alias_meta_box_on_' . $post_type ) == 'show' ) ? ' checked' : '';?>>
										<label for="_swerve_alias_meta_box_on_<?php echo $post_type;?>">
										<?php
											
											$obj = get_post_type_object( $post_type );
											echo $obj->labels->singular_name;
										?>
										</label>
									</span>
								<?php
							}
						?>
						<p><em>Swerve must also be 'activated' on the post type for the 'Aliases' meta box to show. This option will let you view what redirects have been set on a post type, and let you add your own 'aliases' so you can redirect a URL of your choosing to the post type.</em></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Show 'Redirect' meta box on post type</th>
					<td>
						<?php
							foreach( $post_types as $post_type)
							{
								?>
									<span class="inline">
										<input type="checkbox" value="show" id="swerve_redirect_meta_box_on_<?php echo $post_type;?>" name="_swerve_redirect_meta_box_on_<?php echo $post_type;?>"<?php echo ( get_option('_swerve_redirect_meta_box_on_' . $post_type ) == 'show' ) ? ' checked' : '';?>>
										<label for="_swerve_redirect_meta_box_on_<?php echo $post_type;?>">
										<?php
											
											$obj = get_post_type_object( $post_type );
											echo $obj->labels->singular_name;
										?>
										</label>
									</span>
								<?php
							}
						?>
						<p><em>Redrirect anything, to anything.</em></p>
					</td>
				</tr>
				
			</table>
			<?php submit_button(); ?>
			</form>
		</div>
	<?php
}

/**
 * Add "Settings" action on installed plugin list
 */
function swerve_add_plugin_actions( $links ) {
	array_unshift( $links, '<a href="options-general.php?page=swerve-settings">Settings</a>');
	return $links;
}
add_action( 'plugin_action_links_swerve/index.php', 'swerve_add_plugin_actions' );

/**
 * Add links on installed plugin list
 */
function swerve_add_plugin_links( $links, $file ) 
{
	if( $file == 'swerve/index.php' ) {
		$rate_url = 'http://wordpress.org/support/view/plugin-reviews/swerve?rate=5#postform';
		$links[] = '<a href="' . $rate_url . '" target="_blank" title="Rate and Review this Plugin on WordPress.org">Rate this plugin</a>';
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'swerve_add_plugin_links' , 10, 2);
?>