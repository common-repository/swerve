<?php
/**
 * @package Swerve
 */

/**
 * 
 * @since  		3.1.0
 * 
 * Custom meta box for redirect
 * 
 */
function swerve_redirect_meta_box() {

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
			'swerve_redirect',
			'Redirect',
			'swerve_redirect_render_meta_box',
			$screen
		);
	}

}
add_action( 'add_meta_boxes', 'swerve_redirect_meta_box' );



/**
 * 
 * @since  		3.1.0
 * 
 * Render the redirect meta box
 * 
 */
function swerve_redirect_render_meta_box( $post ) {

	$swerve_link_type 						= get_post_meta( $post->ID, '_swerve_link_type', true );
	$swerve_related_internal_type 			= get_post_meta( $post->ID, '_swerve_related_internal_type', true );
	$swerve_related_internal 				= get_post_meta( $post->ID, '_swerve_related_internal', true );
	$swerve_related_external 				= get_post_meta( $post->ID, '_swerve_related_external', true );
	$post_types 							= get_post_types( array('public' => true) );
	sort( $post_types );

	?>

		<div class="swerve_redirect cf">
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							Link to post
						</strong>
					</div>
					<div class="input__container">
							<input type="radio" id="swerve_link_type_none" name="swerve_link_type" value=""<?php echo empty( $swerve_link_type ) ? ' checked' : '';?> /> <label for="swerve_link_type_none">None</label>
							<br/>
							<input type="radio" id="swerve_link_type_internal" name="swerve_link_type" value="internal"<?php echo ( $swerve_link_type == 'internal' ) ? ' checked' : '';?>/> <label for="swerve_link_type_internal">Internal</label>
							<br/>
							<input type="radio" id="swerve_link_type_external" name="swerve_link_type" value="external"<?php echo ( $swerve_link_type == 'external' ) ? ' checked' : '';?>/> <label for="swerve_link_type_external">External</label>
					</div>
				</div>
			</p>
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="swerve_related_internal">Related internal</label>
						</strong>
					</div>
					<div class="input__container">
						
						<label class="label-inline" for="swerve_related_internal_type">Related type</label>&nbsp;&nbsp; 

						<select id="swerve_related_internal_type" name="swerve_related_internal_type">
								<?php

									foreach( $post_types as $post_type)
									{
										?>
											<option value="<?php echo $post_type;?>"<?php echo ( $swerve_related_internal_type == $post_type ) ? ' selected' : '';?>><?php $obj = get_post_type_object( $post_type ); echo $obj->labels->singular_name;?></option>
										<?php
									}

								?>
							</select>
							<br/><br/>
							<select class="full" id="swerve_related_internal" name="swerve_related_internal">
								<option value="">None</option>
								<?php

									foreach( $post_types as $post_type)
									{
										$list_posts_args 	= array(
											'post_type'			=> $post_type,
											'orderby'			=> 'title',
											'order'				=> 'ASC',
											'posts_per_page' 	=> -1
										);
										$list_posts 		= get_posts( $list_posts_args );

										foreach( $list_posts as $list_post )
										{
											?>
												<option data-post-type="<?php echo $post_type;?>" value="<?php echo $list_post->ID;?>"<?php echo ( $swerve_related_internal == $list_post->ID ) ? ' selected' : '';?>><?php echo $list_post->post_title;?></option>
											<?php
										}
									}

								?>
							</select>
					</div>
				</div>
			</p>
			<p>
				<div class="row cf">
					<div class="label__container">
						<strong>
							<label class="label-inline" for="swerve_related_external">Related external</label>
						</strong>
					</div>
					<div class="input__container">
							<input type="text" id="swerve_related_external" name="swerve_related_external" value="<?php echo $swerve_related_external;?>"/>
					</div>
				</div>
			</p>
		</div>

	<?php

	wp_nonce_field( 'submit_swerve_redirect', 'swerve_redirect_nonce' ); 
}


/**
 * 
 * @since  		3.1.0
 * 
 * Handle the redirect meta box post data
 * 
 */
function swerve_redirect_handle_post_data( $post_id )
{

	$nonce_key							= 'swerve_redirect_nonce';
	$nonce_action						= 'submit_swerve_redirect';

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

	$swerve_link_type 						= isset( $_POST['swerve_link_type'] ) 						?  $_POST['swerve_link_type']  						: null;
	$swerve_related_internal_type 			= isset( $_POST['swerve_related_internal_type'] ) 			?  $_POST['swerve_related_internal_type']  			: null;
	$swerve_related_internal 				= isset( $_POST['swerve_related_internal'] ) 				?  $_POST['swerve_related_internal']  				: null;
	$swerve_related_external 				= isset( $_POST['swerve_related_external'] ) 				?  esc_url_raw( $_POST['swerve_related_external'] )	: null;

	update_post_meta( $post_id, '_swerve_link_type', $swerve_link_type );
	update_post_meta( $post_id, '_swerve_related_internal_type', 			$swerve_related_internal_type );
	update_post_meta( $post_id, '_swerve_related_internal',					$swerve_related_internal );
	update_post_meta( $post_id, '_swerve_related_external', 				$swerve_related_external );
}
add_action( 'save_post', 'swerve_redirect_handle_post_data' );


/**
 * 
 * @since  		3.2.2
 * @updated 	
 * 
 * Do the redirect if a link has been set
 * 
 */
function swerve_redirect_action()
{
	global $post;

	if( is_object($post) )
	{
		$swerve_link_type 						= get_post_meta( $post->ID, '_swerve_link_type', true );
		$swerve_related_internal 				= get_post_meta( $post->ID, '_swerve_related_internal', true );
		$swerve_related_external 				= get_post_meta( $post->ID, '_swerve_related_external', true );
	}
	if( !is_admin() )
	{
		if( !empty( $swerve_link_type ) && $swerve_link_type == "internal" && !empty( $swerve_related_internal ) )
		{
			status_header( 301 );
			$wp_query->is_404=false;
			wp_redirect( get_page_link( $swerve_related_internal ), 301 );
			exit;
		}
		else if( !empty( $swerve_link_type ) && $swerve_link_type == "external" )
		{
			status_header( 301 );
			$wp_query->is_404=false;
			wp_redirect( $swerve_related_external, 301 );
			exit;
		}
	}
}
add_action( 'wp', 'swerve_redirect_action' );
?>