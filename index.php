<?php
/**
 * @package Swerve
 * @version 3.2.2
 */

/*
Plugin Name:  Swerve
Plugin URI:   http://makedo.in/products/
Description:  301 redirects and aliases. Change a permalink or then parent and the old address will automatically be forwarded. Plus add custom redirects.
Author:       Make Do
Version:      3.2.2
Author URI:   http://makedo.in
Licence:      GPLv2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html


/////////  VERSION HISTORY

3.0.0 	Complete rebuild of Swerve
3.2.0 	Internal and external redirects
3.2.1 	Fixed windows incompatibility issue
3.2.2 	Fixed error on admin screen

/////////  CURRENT FUNCTIONALITY

1  - Register scripts
2  - Filter post data
3  - Override 404
4  - Options
5  - Aliases meta box
6  - Upgrade scripts
7  - Redirect meta box

///////// TODO

* Short link integration
* View all incoming (internal only) and outgoing links to a page/post
* View all incoming external links (via a backlink service)
* Identify key incoming and outgoing links (customer journey planning)
* On delete identify pages that currently link to this page
* On delete prompt transfer of permalink data to another page (maintaining links)
* Ability to transfer permalink data to another page
* On delete set a custom 404 message

*/

// 1  - Register scripts
require_once 'admin-scripts.php';

// 2  - Filter post data
require_once 'helper-filter-post-data.php';

// 3  - Override 404
require_once 'helper-override-404.php';

// 4  - Options
require_once 'admin-options.php';

// 5  - Aliases meta box
require_once 'admin-meta-box-aliases.php';

// 6  - Upgrade scripts
require_once 'admin-upgrade.php';

// 7  - Redirect meta box
require_once 'admin-meta-box-redirect.php';
?>