<?php
/**
 * @package Swerve
 */

/**
 * 
 * @since  		3.0.0
 * 
 * Custom meta box for aliases
 * 
 */
function swerve_aliases_meta_box() {

	// Only add the box to the selected post types
	$screens 		= array();
	$post_types 	= get_post_types( array('public' => true) );

	foreach( $post_types as $post_type)
	{
		if( get_option('_swerve_alias_meta_box_on_' . $post_type ) === 'show' && get_option('_swerve_activate_on_' . $post_type ) === 'show' )
		{
			array_push( $screens, $post_type );
		}
	}

	foreach ( $screens as $screen ) 
	{
		add_meta_box(
			'swerve_aliases',
			'Aliases',
			'swerve_aliases_render_meta_box',
			$screen
		);
	}

}
add_action( 'add_meta_boxes', 'swerve_aliases_meta_box' );



/**
 * 
 * @since  		3.0.0
 * 
 * Render the aliases meta box
 * 
 */
function swerve_aliases_render_meta_box( $post ) {

	$aliases = get_post_meta( $post->ID, '_swerve_aliases', true );
	if( !is_array( $aliases ) )
	{
		$aliases = array();
	}
	sort( $aliases );

	?>

		<div class="swerve_aliases cf">
			<table cellpadding="0" cellspacing="0" class="swerve_aliases_table">
				<thead>
					<tr>
						<th width="90%" scope="col"><label for="swerve_alias_name">Alias</label></th>
						<th scope="col">Option</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td><input type="text" id="swerve_alias_name" name="swerve_alias_name"/> </td>
						<td><input type="submit" class="button button-small" id="swerve_alias_add" name="swerve_alias_add" value="Add"/></td>
					</tr>
				</tfoot>
				<tbody>
					<?php

						foreach( $aliases as $index=>$alias )
						{
							?>
								<tr>
									<td><?php echo $alias; ?></td>
									<td><input type="submit" class="button button-small" id="swerve_alias_remove_<?php echo $index;?>" name="swerve_alias_remove_<?php echo $index;?>" value="Remove"/></td>
								</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>

	<?php

	wp_nonce_field( 'submit_swerve_aliases', 'swerve_aliases_nonce' ); 
}


/**
 * 
 * @since  		3.0.0
 * 
 * Handle the aliases meta box post data
 * 
 */
function swerve_aliases_handle_post_data( $post_id )
{

	$nonce_key							= 'swerve_aliases_nonce';
	$nonce_action						= 'submit_swerve_aliases';

	// If it is just a revision don't worry about it
	if ( wp_is_post_revision( $post_id ) )
		return $post_id;

	// Check it's not an auto save routine
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// Verify the nonce to defend against XSS
	if ( !isset( $_POST[$nonce_key] ) || !wp_verify_nonce( $_POST[$nonce_key], $nonce_action ) )
		return $post_id;

	// Check that the current user has permission to edit the post
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	$aliases = get_post_meta( $post_id , '_swerve_aliases', true );
	if( !is_array( $aliases ) )
	{
		$aliases = array();
	}
	sort( $aliases );

	$swerve_alias_name = isset( $_POST['swerve_alias_name'] ) ? $_POST['swerve_alias_name'] : null;

	if( !empty( $swerve_alias_name ) )
	{	
		$swerve_alias_name = '/' . strtolower( $swerve_alias_name );
		$swerve_alias_name = rtrim( $swerve_alias_name, '/' );
		$swerve_alias_name = preg_replace( '/\s+/', ' ', $swerve_alias_name );
		$swerve_alias_name = str_replace( ' ', '-', $swerve_alias_name );
		$swerve_alias_name = preg_replace( '/-+/', '-', $swerve_alias_name );
		$swerve_alias_name = preg_replace( '/_+/', '_', $swerve_alias_name );
		$swerve_alias_name = preg_replace( '/[^0-9a-z_-\s\/]/', '', $swerve_alias_name );
		$swerve_alias_name = preg_replace( '#/+#', '/', $swerve_alias_name );
		
		if( !in_array( $swerve_alias_name, $aliases ) )
		{
			$aliases[] = $swerve_alias_name;
		}
	}

	foreach( $_POST as $key=>$value ) 
	{
		if( strpos( $key, 'swerve_alias_remove_' ) === 0 ) 
		{
			$key_value = str_replace( 'swerve_alias_remove_', '', $key );
			unset( $aliases[ $key_value ] );
		}
	}

	if( count( $aliases ) > 0 )
	{
		update_post_meta( $post_id, '_swerve_aliases', $aliases );
	}
	else
	{
		delete_post_meta( $post_id, '_swerve_aliases' );
	}
}
add_action( 'save_post', 'swerve_aliases_handle_post_data' );
?>