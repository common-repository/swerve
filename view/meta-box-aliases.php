
<div class="mkdo-swerve-permalink-list">

<?php
	
	$i = 0;

	if( is_array( $post_meta_value_aliases ) )
	{
		?>

			<p>To remove an alias, uncheck it.</p>

		<?php

			foreach($post_meta_value_aliases as $value)
			{

		?>

				<label for="previous_permalink_meta_value_<?php echo $i; ?>">
					<?php echo $value; ?> <input type="checkbox" id="post_meta_value_alias_<?php echo $i; ?>" name="post_meta_value_alias_<?php echo $i; ?>" value="<?php echo $value; ?>" checked="true"><div></div>
				</label>
				<br/>

		<?php

				$i++;
			}
	}
	else
	{
		
		?>
				<p>There are no aliases. Fill in the box below to add a new one.</p>
		<?php
	}

?>

</div>
<div class="mkdo-swerve-add-new-alias">
	<p>
		<label for="post_meta_value_alias">New Alias</label>: 
		<input type="text" id="post_meta_value_alias" name="post_meta_value_alias" />
	</p>
</div>

