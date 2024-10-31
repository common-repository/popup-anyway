<?php
/*
Plugin Name: PopUp Anyway
Plugin URI: http://wp-plugins.in/popup-anyway
Description: Add PopUp advertisement easily, it is not affected with any AdBlock! and compatible with all major browsers like Google Chrome, Firefox, IE, Opera, phone and tablet.
Version: 1.0.0
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2015 Alobaidi (email: wp-plugins@outlook.com)

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


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function obi_popup_anyway_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'popup-anyway.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/popup-anyway" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'obi_popup_anyway_plugin_row_meta', 10, 2 );


function obi_popup_anyway_jquery(){
	wp_enqueue_script( 'obi-popup-anyway-jquery', false, array('jquery'), false, false);
}
add_action('wp_enqueue_scripts', 'obi_popup_anyway_jquery');


function obi_popup_anyway_settings(){
	add_settings_section('obi_popup_anyway_section', 'PopUp Anyway', false, 'general');

	add_settings_field( 'obi_popup_anyway_url', 'PopUp Link', 'obi_popup_anyway_url', 'general', 'obi_popup_anyway_section', array('label_for' => 'obi_popup_anyway_url') );
	add_settings_field( 'obi_popup_anyway_time', 'PopUp Time', 'obi_popup_anyway_time', 'general', 'obi_popup_anyway_section', array('label_for' => 'obi_popup_anyway_time') );

	register_setting( 'general', 'obi_popup_anyway_url' );
	register_setting( 'general', 'obi_popup_anyway_time' );
}
add_action( 'admin_init', 'obi_popup_anyway_settings' );

function obi_popup_anyway_url(){
	?>
		<input type="text" name="obi_popup_anyway_url" id="obi_popup_anyway_url" class="regular-text" value="<?php echo esc_attr( get_option('obi_popup_anyway_url') ); ?>">
		<p class="description">Enter your PopUp advertisement link.</p>
	<?php
}

function obi_popup_anyway_time(){
	?>
		<input type="text" name="obi_popup_anyway_time" id="obi_popup_anyway_time" class="small-text" value="<?php echo esc_attr( get_option('obi_popup_anyway_time') ); ?>">
		<p class="description">PopUp cookie time, default is 24 (24 hours), will be display PopUp advertisement every 24 hours, enter custom time, number only, if you change number, wait 24 hours to update option, because default time is 24 hours.</p>
	<?php
}


function obi_popup_anyway_cookie(){

	if( !get_option('obi_popup_anyway_cookie') ){
		$rand	= 	rand();
		$md5	= 	md5($rand);
		update_option('obi_popup_anyway_cookie', $md5);
	}

	if( get_option('obi_popup_anyway_time') ){
		$number = get_option('obi_popup_anyway_time');
	}else{
		$number = 24;
	}

	$time 		 = 	$number;
	$calc		 = 	3600 * $time;
	$cookie_name = 	get_option('obi_popup_anyway_cookie');

	global $pagenow;

	if( !is_user_logged_in() and 'wp-login.php' !== $pagenow ){
		setcookie($cookie_name, $cookie_name, time() + $calc);
	}

}
add_action('init', 'obi_popup_anyway_cookie');


function obi_popup_anyway_css(){

	$cookie_name = 	get_option('obi_popup_anyway_cookie');

	if( !isset($_COOKIE[$cookie_name]) and !is_user_logged_in() ){
	?>
		<style type="text/css">
			.p<?php echo $cookie_name; ?>{
				cursor:default !important;
				position:fixed !important;
				width:100% !important;
				height:100% !important;
				left:0 !important;
				right:0 !important;
				bottom:0 !important;
				top:0 !important;
				z-index:999999999999999999999999999999999999 !important;
				color:transparent !important;
				text-decoration:none !important;
				margin:0 !important;
				padding:0 !important;
				border:0 !important;
			}

			.p<?php echo $cookie_name; ?>:hover, .p<?php echo $cookie_name; ?>:active,
			.p<?php echo $cookie_name; ?>:focus, .p<?php echo $cookie_name; ?>:visited{
				outline:0 !important;
				color:transparent !important;
				text-decoration:none !important;
			}

			.p<?php echo $cookie_name; ?>:before, .p<?php echo $cookie_name; ?>:after{
				display:none !important;
			}
		</style>
	<?php
	}

}
add_action('wp_head', 'obi_popup_anyway_css');


function obi_popup_anyway_display(){

	$cookie_name = 	get_option('obi_popup_anyway_cookie');

	if( get_option('obi_popup_anyway_url') ){
		$url = get_option('obi_popup_anyway_url');
	}else{
		$url = get_option('siteurl');
	}

	if( !isset($_COOKIE[$cookie_name]) and !is_user_logged_in() ){

		?>
			<script type="text/javascript">
				jQuery(function(){
					jQuery(".p<?php echo $cookie_name; ?>").click(function() {
						jQuery(".p<?php echo $cookie_name; ?>").remove();
					});
				});
			</script>
			<a rel="nofollow" href="<?php echo $url; ?>" target="_blank" class="p<?php echo $cookie_name; ?>"></a>
		<?php

	}

}
add_action('wp_footer', 'obi_popup_anyway_display');

?>