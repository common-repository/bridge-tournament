<?php 
/*
Leave the place clean when the plugin is uninstalled

Author: Frederic Hantrais
Author URI:
License: GPLv2

*/

// Exit if the not called by wordpress

if(defined('WP_UNINSTALL_PLUGIN') ){ 
 
	/**
	 * Remove the plugin database table and folder with all files
	 * if it's set in the plugin configuration
	 *
	 * @access public
	 * @global wpdb $wpdb
	 */

function bt_remove_tables() {
	global $wpdb;
	// Get the option to remove plugin table
	$remove_table = get_option('bt_remove_tables', "TRUE" );

	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_player;");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_tournament;");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_registration;");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_partners;");
	
}
bt_remove_tables();

		/** 
		* This functions removes the pages created by the plugin
		* As the pages may have been modified by users, it would be better to remove the page only when the plugin is uninstalled
		**/
				
function bt_delete_pages() {
	$pg_name_base = __('Bridge Tournament', 'bt');
	$page = get_page_by_title( $pg_name_base );

// exclude page from before removing it
	
	$exclude_string = "'exclude=" . $page->ID ."'" ;
	wp_list_pages( $exclude_string) ;
	
	echo 'Delete page : ' . $pg_name_base . ' ID: ' . $page->ID ; 
	wp_delete_post( $page->ID , TRUE );
}
bt_delete_pages();

//Delete options 

delete_option( 'bt_club_name' );
delete_option( 'bt_contact_address' );
delete_option( 'bt_default_location' );
delete_option( 'bt_custom_msg' );
delete_option( 'bt_join_message' );
delete_option( 'bt_admin_privileges' );
delete_option( 'bt_user_privileges' );
delete_option( 'bt_remove_tables' );

}

?>

