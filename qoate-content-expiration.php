<?php
/*
Plugin Name: Qoate Content Expiration
Plugin URI: http://qoate.com/wordpress-plugins/content-expiration/
Description: Automatically replaces text with a custom message after an expiration date.
Author: Danny van Kooten (Qoate)
Version: 1.0
Author URI: http://Qoate.com
License: GPL2
*/

/*  Copyright 2010  Danny van Kooten  (email : danny@qoate.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('QOATE_PE_PLUGIN_PATH',WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__))); 

include_once QOATE_PE_PLUGIN_PATH.'WPAlchemy/MetaBox.php';
 
// include css to help style our custom meta boxes
// this should be a global stylesheet used by all similar meta boxes
if (is_admin()) wp_enqueue_style('custom_meta_css',QOATE_PE_PLUGIN_PATH.'custom/meta.css');
 
$expiration_info = new WPAlchemy_MetaBox(array
(
	'id' => '_expiration_info', // underscore prefix hides fields from the custom fields area
	'title' => 'Qoate Content Expiration Options',
	'template' => QOATE_PE_PLUGIN_PATH.'custom/expiration_info.php',
	'types' => array('post','pages'),
));

function qoate_check_for_exp($atts,$content) {
global $expiration_info;
if(checkdate($expiration_info->get_the_value('exp_mm'),$expiration_info->get_the_value('exp_dd'),$expiration_info->get_the_value('exp_yy')) == true) {
	$expirestring=$expiration_info->get_the_value('exp_mm').'/'.$expiration_info->get_the_value('exp_dd').'/20'.$expiration_info->get_the_value('exp_yy').' 00:00:00';
	$timebetween= strtotime($expirestring)-time();
		if ( $timebetween > 0 ) {
			return $content;
		} else {
			return $expiration_info->get_the_value('exp_text');
		}
}
}
add_shortcode('exp', 'qoate_check_for_exp');

?>