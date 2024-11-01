
<div class="mkdo-swerve-permalink-list">

<?php
	
	$i = 0;

	if( is_array( $post_meta_value_previous_permalinks ) )
	{
		?>

			<p>To remove a previous permalink, uncheck it.</p>

		<?php

			foreach($post_meta_value_previous_permalinks as $value)
			{

		?>

				<label for="previous_permalink_meta_value_<?php echo $i; ?>">
					<?php echo $value; ?> <input type="checkbox" id="post_meta_value_previous_permalink_<?php echo $i; ?>" name="post_meta_value_previous_permalink_<?php echo $i; ?>" value="<?php echo $value; ?>" checked="true"><div></div>
				</label>
				<br/>

		<?php

				$i++;
			}
	}
	else
	{
		
		?>
				<p>There are no previous permalinks.</p>
		<?php
	}

?>

</div>


