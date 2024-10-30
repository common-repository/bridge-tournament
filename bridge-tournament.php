<?php
/*
Plugin Name: Bridge Tournament
Plugin URI: http://www.pmbeforepm.org
Description:  This plugin is deisgned to help bridge tournament organizers to manage games and tournaments.
Version: 1.0
Author: Frederic Hantrais
Author URI:
License: GPLv2
*/

/*
	Copyright 2013  Frederic Hantrais  (email: fhs@pmbeforepm.org)

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

// Plugin Version
define( 'WP_BT_VERSION', '1.0' );
// Defining the path constants
define( 'BT_TEMPLATES_PATH', plugin_dir_path( __FILE__ ) . '/templates' );
define( 'BT_INCLUDES_PATH', plugin_dir_path( __FILE__ ) . '/includes' );
define( 'BT_IMG_PATH', plugin_dir_path( __FILE__ ) . '/img' );
define( 'BT_LANGUAGES_PATH', plugin_dir_path( __FILE__ ) . '/languages' );
define( 'BT_CSS_PATH', plugin_dir_path( __FILE__ ) . '/css' );

include_once 'includes/db_access.php'; // Database access objects and functions
include_once 'includes/player.php'; 	// Load player classes
include_once 'includes/tournament.php';
include_once 'includes/settings_page.php'; // Load option page class
include_once 'includes/support_functions.php'; // jquery and css functions

// register plugin activation actions ( create tables and folders )
register_activation_hook( __FILE__ , array('DB_Access','create_tables'));

// register rules and flush on plugin activation
register_activation_hook(__FILE__, 'bt_rewrite_rule_activation' );

/**
 * Initialize Bridge Tournament - Plugin Menu
 */
add_action('admin_menu', '_admin_menu_options');
function _admin_menu_options() {
	add_submenu_page( 'tools.php', 'Players page', 'Players list', 'manage_options', 'my-custom-submenu-page', 'custom_player_display' ); 
}

/**
* Activate support functions
**/
add_action( 'init' , 'bt_load_css' );
add_action( 'wp_suggest' , 'bt_suggest_enqueue' ); 
add_action( 'init' , 'bt_date_enqueue' );
add_action( 'plugins_loaded' , 'load_languages' );
add_action( 'wp_enqueue_scripts', 'bt_add_color_to_css' );


/* Activate the menu by creating menu object */

$bt_settings_page = new bt_admin_pages();

// Create Plugin pages when the plugin is activated
register_activation_hook (__FILE__, array('bt_admin_pages','create_pages'));


function add_custom_query_var( $vars ){
  $vars[] = "trn_date";
  $vars[] = "contact_id";
  $vars[] = "player_id";
  return $vars;
}
add_action( 'query_vars', 'add_custom_query_var' );


				/**
				** Plugin calls
				**	The following functions are called through shortcodes
				**
				**/

/*
 * Display all the registered players
 */ 
function bt_players_display() {
	$player = new player;
	echo $player->display_players_list();
	
}
add_shortcode('display_players', 'bt_players_display');

/**
    *   Function to register new users 
**/
function bt_player_add() {
	
	if ( bt_check_admin() ) {
   	$player = new player;
   	echo $player->create_player();
   	} else {
			_e( 'This function is only available for bridge tournament administrators.','bt' );   		
   		}         
}
add_shortcode('create_player', 'bt_player_add');

/**
    *   Function to edit user profile 
**/
function bt_player_edit() {
	
	if ( bt_check_admin() ) {
   	$dbr = new DB_Access;
		$player_id = get_query_var( 'player_id' );			
		$player = new player ( $dbr->get_player_by_id( $player_id ));
		
   	echo $player->edit_player();
   	} else {
			_e( 'This function is only available for bridge tournament administrators.','bt' );   		
   		}         
}
add_shortcode('edit_player', 'bt_player_edit');

/**
    *   Function to open new tournament
    * Add a new entry in the tournament table
**/
function bt_create_tournament() { 
	$tournament_date = get_query_var( 'trn_date' );
	if ( bt_check_admin() ) {
		if (!empty( $tournament_date ) ) {
			$dbr = new DB_Access();			
			$tournament = $dbr->get_trn_by_date( $tournament_date ) ;
			$tournament->update_tournament();			
			}
			 else {	
			$tournament = new tournament();	
			$tournament->create_tournament();
			}
		} else {
			_e( 'This function is only available for bridge tournament administrators.','bt' );			
			}
}
add_shortcode('create_tournament', 'bt_create_tournament');

function bt_list_tournament() { 
		ob_start();
		$tournament = new tournament();
		$tournament->display_tournaments();
		return ob_get_clean();
}
add_shortcode('upcoming_tournament', 'bt_list_tournament');

function bt_show_past_tournaments() { 
	
	$tournament = new tournament ();
   ob_start();
   $tournament->display_past_tournaments();
	return ob_get_clean();
}
add_shortcode('list_past_tournaments', 'bt_show_past_tournaments');

function bt_show_full_past_tournaments() { 
	
	$tournament = new tournament ();
   ob_start();
   $tournament->display_past_full_tournaments();
	return ob_get_clean();
}
add_shortcode('list_past_full_tournaments', 'bt_show_full_past_tournaments');
/** 
    * Functions required for user registration 
**/
function bt_player_registration() {
        $trn = new tournament();
		  $trn->register_players();
		  
}
add_shortcode('registration', 'bt_player_registration');


/** 
    * Functions required for put users on player list 
**/
function bt_add_to_list() {
        $trn = new tournament();
		  $trn->set_as_available();
}
add_shortcode('add_to_list', 'bt_add_to_list');

/** 
* Returns and display the list of registered player for a specific tournament
**/

function bt_list_registered( $trn_date ) {
	$trn = new tournament();
	$trn->manage_registrations ( $trn_date ) ;
}
add_shortcode('registered_players', 'bt_list_registered');

/** 
* Returns the content of the player list
**/

function bt_show_list( $trn_date ) {
	$trn = new tournament();
	$trn->show_available( $trn_date ) ;
}
add_shortcode('players_on_list', 'bt_show_list');

/** 
* Returns and display or modify the list of registered player for a given date
**/

function bt_list_results($trn_date) {
	$datefromhref = get_query_var( 'trn_date' );
	if ( !empty( $datefromhref )) { 
		$dbr = new DB_Access();
		$trn = $dbr->get_trn_by_date($datefromhref);
		$trn->display_results();	
		} else {
			$relocation= get_bloginfo('url') . '/bt-past-tournaments/';
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$relocation.'">';	
			}
}
add_shortcode('display_results', 'bt_list_results');

function bt_update_results($trn_date) {
	$datefromhref = get_query_var( 'trn_date' );
	if ( !empty( $datefromhref )) { 
		$dbr = new DB_Access();
		$trn = $dbr->get_trn_by_date($datefromhref);
		if ( $trn->get_tr_type()  == 'Mitchell' ) { 
				$trn->update_results_oriented() ;			
			}
			else { 
				$trn->update_results() ;
			}
		} else {
			$relocation= get_bloginfo('url') . '/bt-past-tournaments/';
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$relocation.'">';					
			}
}
add_shortcode('update_results', 'bt_update_results');

function bt_player_request() {
	$player = new player;
	$player->request();	
	}
add_shortcode('player_request','bt_player_request');

function bt_contact_player() {
	$player = new player;
	$player->contact_request();
}
add_shortcode('contact_player', 'bt_contact_player');
?>
