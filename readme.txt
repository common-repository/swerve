=== Swerve ===
Contributors: mkdo, mwtsn, mkjones
Donate link: 
Tags: link management, alias, aliases, permalink, redirect, slug, link, 301 redirect, 404 management, 301, 404, swerve, redirect, 301 management, hierarchical redirect, nested redirect, page redirect, hierarchical 301, internal redirect, external redirect
Requires at least: 3.3
Tested up to: 3.9
Stable tag: 3.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

301 redirects and aliases. Change a permalink or then parent and the old address will automatically be forwarded. Plus add custom redirects.

== Description ==

Created by [Make Do](http://makedo.in/), choose in the settings which post types you want this to support, and optionally add a meta box to that post type to view, add or remove permalinks and/or aliases.

The plugin will automaticaly keep track of changes to the post types you have 'activated' the plugin on, and will automatically 301 redirect to those pages if an old URL is landed on (WordPress typically dosn't do this for deep hierarchical content, and will instead serve a 404 error).

Also you can add custom redirects, so if you have a page URL of `http://domain.com/about-my-organisation/contacting-us`, with this plugin you can direct a custom URL alias to that page such as: `http://domain.com/contact`. 

= Swerve features =

* Support for URL aliases (redirect to your page/post from any alias you enter)
* Delete permalinks that you do not want to redirect to your page/post
* Redirect any post type to an internal page
* Redirect any post type to an external page

If you are using this plugin in your project [we would love to hear about it](mailto:hello@makedo.in).

== Installation ==

1. Backup your WordPress install
2. Upload the plugin folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Custom meta box to view, add or delete permalinks and aliases
2. The options panel
3. Redirect to any other page

== Changelog ==

= 3.2.2 = 
* Fixed error on admin screen

= 3.2.1 =
* Fixed windows incompatibility issue

= 3.2.0 =	
* Internal and external redirects

= 3.0.0 = 
* Complete rewrite of plugin

= 2.0.2 =
* Fixed issue: Function in class-url-helper.php was causing fatal error
* Updated documentation

= 2.0.1 =
* Fixed issue: Function declared as private causing error

= 2.0.0 =
* Updated documentation
* Completely refactored the code
* Added support for URL alias redirects

= 1.0.0 =
* Initial release

== Upgrade notice ==

Upgrading from v2.x to v3.x will add the meta data of your aliases and permalinks to a single _swerve_aliases meta key. It will not delete the old meta key, so your data will not be lost.