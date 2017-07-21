<?php
/*
Plugin Name: Cute Profiles
Plugin URI: http://keencodes.com/cute-profiles
Description: Cute Profiles adds all your Social Profile icons vertically on left/right side of your pages,in widgets or anywhere on webpage. Left/Right side profiles will remain visible all the time though the page is scrolled and without consuming the sidebar space. Also add social sharing buttons at these places.
Version: 1.2.1	
Author: KeenCodes
Author URI: http://keencodes.com
Wordpress version supported: 3.5 and above
*/

/*  Copyright 2009  KeenCodes  (email : tedeshpa@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//defined global variables and constants here
define('CUTE_PROFILES_TABLE','cute_profiles'); //Cute Profiles TABLE NAME
define('CUTE_PROFILES_VER','1.2.1',false);//Current Version of Cute Profiles
if ( ! defined( 'CUTE_PROFILES_PLUGIN_BASENAME' ) )
	define( 'CUTE_PROFILES_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'CUTE_PROFILES_CSS_DIR' ) ){
	define( 'CUTE_PROFILES_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/default/' );
}
global $cute_profiles,$default_cute_profiles_settings;
$default_cute_profiles_settings = array('top'=>'325', 
								'align'=>'left', 
								'size'=>'32', 
								'theme'=>'default',
								'profiles'=> array(array('p_name'=>'Facebook','icon_url'=>' ','p_url'=>'http://facebook.com','p_flag'=>'1','p_num'=>'0'),
											 array('p_name'=>'Twitter','icon_url'=>' ','p_url'=>'http://twitter.com','p_flag'=>'1','p_num'=>'1'),
											 array('p_name'=>'Google+','icon_url'=>' ','p_url'=>'http://plus.google.com','p_flag'=>'1','p_num'=>'2'),
											 array('p_name'=>'Pinterest','icon_url'=>' ','p_url'=>'http://pinterest.com','p_flag'=>'1','p_num'=>'3'),
											 array('p_name'=>'Digg','icon_url'=>' ','p_url'=>'http://digg.com','p_flag'=>'1','p_num'=>'4'),
											 array('p_name'=>'Reddit','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'5'),
											 array('p_name'=>'Stumbleupon','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'6'),
											 array('p_name'=>'Linkedin','icon_url'=>' ','p_url'=>'http://linkedin.com','p_flag'=>'1','p_num'=>'7'),
											 array('p_name'=>'RSS','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'8'),
											 array('p_name'=>'Blogger','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'9'),
											 array('p_name'=>'WordPress','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'10'),
											 array('p_name'=>'Deviantart','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'11'),
											 array('p_name'=>'Dribble','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'12'),
											 array('p_name'=>'Email','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'13'),
											 array('p_name'=>'Dropbox','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'14'),
											 array('p_name'=>'Flickr','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'15'),
											 array('p_name'=>'Foursquare','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'16'),
											 array('p_name'=>'Instagram','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'17'),
											 array('p_name'=>'Friendfeed','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'18'),
											 array('p_name'=>'Last FM','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'19'),
											 array('p_name'=>'MySpace','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'20'),
											 array('p_name'=>'Skype','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'21'),
											 array('p_name'=>'Tumblr','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'22'),
											 array('p_name'=>'Vimeo','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'23'),
											 array('p_name'=>'YouTube','icon_url'=>' ','p_url'=>'http://youtube.com','p_flag'=>'1','p_num'=>'24'),
											 array('p_name'=>'Yahoo','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'25'),
											 array('p_name'=>'Pulse','icon_url'=>' ','p_url'=>' ','p_flag'=>'0','p_num'=>'26')
											 ),
								'sharings'=> array(array('p_name'=>'Facebook','icon_url'=>' ','p_url'=>' ','p_flag'=>'1','p_num'=>'0'),
											 array('p_name'=>'Twitter','icon_url'=>' ','p_url'=>' ','p_flag'=>'1','p_num'=>'1'),
											 array('p_name'=>'Google+','icon_url'=>' ','p_url'=>' ','p_flag'=>'1','p_num'=>'2'),
											 array('p_name'=>'Digg','icon_url'=>' ','p_url'=>' ','p_flag'=>'1','p_num'=>'3'),
											 array('p_name'=>'Reddit','icon_url'=>' ','p_url'=>' ','p_flag'=>'1','p_num'=>'4'),
											 ),
								'insert'=>'auto', 
								'follow'=>'dofollow',
								'bg_color'=>'#ffffff',
								'bg'=>'0',
								'show_posts'=>'1',
								'show_pages'=>'1',
								'show_frontpage'=>'1',
								'show_category'=>'1',
								'show_search'=>'1',
								'admin_preview'=>'1',
								's_insert'=>'auto', 
								's_follow'=>'dofollow',
								's_bg_color'=>'#ffffff',
								's_bg'=>'0',
								's_show_posts'=>'1',
								's_show_pages'=>'1',
								's_show_frontpage'=>'1',
								's_show_category'=>'1',
								's_show_search'=>'1',
								's_admin_preview'=>'1',
								's_align'=>'left', 
								's_size'=>'32', 
								's_theme'=>'default',
								's_top'=>'325',
							    'noscript'=>'This page is having a slideshow that uses Javascript. Your browser either doesn\'t support Javascript or you have it turned off. To see this page as it is meant to appear please use a Javascript enabled browser.'
								 );	
function activate_cute_profiles() {
	global $cute_profiles,$default_cute_profiles_settings;
	$cute_profiles_curr=$cute_profiles;
	if($cute_profiles_curr) {
	   foreach($default_cute_profiles_settings as $key=>$value) {
		  if(!isset($cute_profiles_curr[$key])) {
			 $cute_profiles_curr[$key] = $value;
		  }
	   }
	   delete_option('cute_profiles_options');	  
	   update_option('cute_profiles_options',$cute_profiles_curr);
	}
	else update_option('cute_profiles_options',$default_cute_profiles_settings);
}
register_activation_hook( __FILE__, 'activate_cute_profiles' );							 
$cute_profiles = get_option('cute_profiles_options');
// Create Text Domain For Translations
load_plugin_textdomain('cute-profiles', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

function cute_profiles_plugin_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

require_once (dirname (__FILE__) . '/settings/settings.php');
require_once (dirname (__FILE__) . '/processors/cute_1.php');
?>