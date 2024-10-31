<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Uninstall Plugin */

// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out!


/*esle:
	if uninstalled plugin, this options will be deleted
*/
delete_option('obi_popup_anyway_cookie');
delete_option('obi_popup_anyway_url');
delete_option('obi_popup_anyway_time');

?>