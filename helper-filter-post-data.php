<?php
/**
 * @package Swerve
 */

/**
 * 
 * @since  		3.0.0
 * 
 * If the post has moved, or it's slug has changed, create an alias
 * 
 */
function swerve_filter_post_data( $data, $postarr )
{	
	if( !empty( $postarr['ID'] ) )
	{ 
		$post = get_post( $postarr['ID'] );
		$aliases = get_post_meta( $post->ID, '_swerve_aliases', true );
		if( !is_array( $aliases ) )
		{
			$aliases = array();
		}

		if( is_object( $post ) && ( ( !empty( $post->post_name ) && ( $post->post_name != $data['post_name'] ) ) || $post->post_parent != $data['post_parent'] ) )
		{
			$alias = get_permalink( $post->ID );
			$alias = str_replace( home_url() , '', $alias );
			$alias = strtolower( $alias );
			$alias = rtrim( $alias, '/' );

			if( !in_array( $alias, $aliases ) )
			{
				$aliases[] = $alias;
				update_post_meta( $post->ID, '_swerve_aliases', $aliases );
			}
			swerve_change_child_aliases( $post->ID, $alias );
		}
	}
	return $data;
}
add_action( 'wp_insert_post_data', 'swerve_filter_post_data', '99', 2  );


/**
 * 
 * @since  		3.0.0
 * 
 * If the post has moved, or it's slug has changed, reassess its children
 * 
 */
function swerve_change_child_aliases( $parent_id, $parent_alias )
{
	$args = array(
		'post_parent' =>  $parent_id,
	);
	$children = get_children( $args);

	if( !empty( $children ) )
	{
		foreach( $children as $child )
		{
			$alias = $parent_alias . '/' . $child->post_name;
			$alias = preg_replace( '#/+#', '/', $alias );
			$alias = strtolower( $alias );
			$alias = rtrim( $alias, '/' );

			$aliases = get_post_meta( $child->ID, '_swerve_aliases', true );
			if( !is_array( $aliases ) )
			{
				$aliases = array();
			}
			if( !in_array( $alias, $aliases ) )
			{
				$aliases[] = $alias;
				update_post_meta( $child->ID, '_swerve_aliases', $aliases );
			}
			swerve_change_child_aliases( $child->ID, $alias );
		}
	}
}
?>