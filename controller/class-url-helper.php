<<<<<<< .mine
<?php

//Set namespace
namespace mkdo\swerve;

/**
 * URL_Helper
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class URL_Helper {  

	/**
     * Remove the querystring and the slashes
     */
	public static function remove_qs_and_slashes( $url ) 
	{
	    $url = preg_replace( '/\?.*/', '', $url );
	    $url = trim( $url,'/' );

	    return $url;
	}

	/**
     * Get the path only part of a full URL
     */
	public static function get_url_path( $url )
	{
		//TODO: Investigate why this breaks some installs
		//$url = parse_url( $url )['path'];

		return $url;
	}

	/**
     * Use sanatize_key on a url but not slashes
     */
	public static function sanitize_key_with_slashes( $url )
	{
		$url_new = '';
		$counter = 0;

		$url_split_by_slash = explode("/",$url);

		foreach( $url_split_by_slash as $url_part)
		{
			if($counter == 0)
			{
				$url_new = sanitize_key($url_part);
			}
			else
			{
				$url_new = $url_new . '/' . sanitize_key($url_part);
			}

			$counter++;
		}

		return $url_new;
	}
}

=======
<?php

//Set namespace
namespace mkdo\swerve;

/**
 * URL_Helper
 * 
 * @package    swerve
 * @author     MKDO Ltd. <hello@mkdo.co.uk>
 */
class URL_Helper {  

	/**
     * Remove the querystring and the slashes
     */
	public static function remove_qs_and_slashes( $url ) 
	{
	    $url = preg_replace( '/\?.*/', '', $url );
	    $url = trim( $url,'/' );

	    return $url;
	}

	/**
     * Get the path only part of a full URL
     */
	public static function get_url_path( $url )
	{
		$url = parse_url( $url )['path'];

		return $url;
	}

	/**
     * Use sanatize_key on a url but not slashes
     */
	public static function sanitize_key_with_slashes( $url )
	{
		$url_new = '';
		$counter = 0;

		$url_split_by_slash = explode("/",$url);

		foreach( $url_split_by_slash as $url_part)
		{
			if($counter == 0)
			{
				$url_new = sanitize_key($url_part);
			}
			else
			{
				$url_new = $url_new . '/' . sanitize_key($url_part);
			}

			$counter++;
		}

		return $url_new;
	}
}

>>>>>>> .r698632
