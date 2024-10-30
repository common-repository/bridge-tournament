<?php

function bt_load_css() {
	wp_register_style( 'bridge-tournament', plugins_url( 'bridge-tournament/css/bt-style.css' ) );
	wp_enqueue_style( 'bridge-tournament' );	
}
 
// loading JQuery autocomplete 
function bt_suggest_enqueue() {
    // jQuery autocomplete
    wp_enqueue_style( 'jquery-ui-autocomplete', plugins_url( 'css/jquery-ui-1.8.2.custom.css', __FILE__ ) );
    wp_register_script( 'jquery-ui-autocomplete', plugins_url( 'js/jquery.ui.autocomplete.min.js', __FILE__ ), array( 'jquery-ui-widget', 'jquery-ui-position' ), '1.8.2', true );

    // Plugin script and style
    wp_enqueue_style( 'plugin-css', plugins_url( 'css/style.css', __FILE__ ) );
    wp_enqueue_script( 'plugin-js', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery-ui-autocomplete' ), '1.1', true );

}

// loading jquery datapicker

function bt_date_enqueue() {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}

// loading plugin language
function load_languages() {
	load_plugin_textdomain( 'bt', false, 'bridge-tournament/languages/' );
}

//create filters for template redirection 

add_filter ( 'template_include' , 'bt_page_redirect', 99);

function bt_page_redirect( $template ) {

	if ( is_page( 'bridge-tournament' ))	{

		$new_template =  BT_TEMPLATES_PATH . '/bt-page-template.php' ;
		if ( '' != $new_template ) {
			return $new_template ;			
			} else {
			echo 'filter function not found ' ;	
			}
	}
	return $template;	
}	

// Rewrite rule function 

function bt_rewrite_rule_activation(){
	bt_add_rewrite_rule();
	flush_rewrite_rules();
}

add_action( 'init', 'bt_add_rewrite_rule' );
function bt_add_rewrite_rule() {
		add_rewrite_rule( 'bridge-tournament/?([^/]*)', 'index.php?pagename=bridge-tournament&bt_id=$matches[1]','top');
}

add_filter( 'query_vars', 'bt_add_request_var' );
function bt_add_request_var ( $vars ) {
	$vars[]	= 'bt_id';
	return $vars;
}

// Save error function 

add_action('activated_plugin','bt_save_error');
function bt_save_error(){
file_put_contents(ABSPATH. 'wp-content/uploads/bt_error_activation.html', ob_get_contents());
}

function bt_display_error ( $error_msg ) {
	echo '<div class="bt-error-messages">' . $error_msg . '</div>';						
	}

function bt_display_ack ( $ack_msg ) {
	echo '<div class="bt-ack-messages">' . $ack_msg . '</div>';						
	}

function bt_display_msg ( $ack_msg ) {
	echo '<div class="bt-messages">' . $ack_msg . '</div>';						
	}

function bt_display_comment ( $ack_msg ) {
	echo '<div class="bt-comment-messages">' . $ack_msg . '</div>';						
	}

function bt_error_page ( $error_msg ) {
	
	$error_html = '<div class="bt-error">' . $error_msg . '</div>';
	include BT_TEMPLATES_PATH . '/bt-errors.php' ;
	}
	
	
function bt_display_page_title ( $page_title ) {

	$display_output =  '<h1 class="bt-page-title">' . $page_title . '</h1>';
	echo $display_output;
}
						
								/**
								*
								*	Uninstall functions 
								*
								**/
								


	/**
	 * Remove options created during installation
	 *
	 * @access public
	 * @global wpdb $wpdb
	 */

function bt_delete_options() {
	
	delete_option( 'bt_club_name' );
	delete_option( 'bt_contact_address' );
	delete_option( 'bt_default_location' );
	delete_option( 'bt_custom_message' );
	delete_option( 'bt_remove_tables' );	

}

	/**
	* 	Return boolean value if current user has sufficient privileges to act as an administrator
	**/

function bt_check_admin() {
	$privileges = get_option( 'bt_admin_privileges');		
	switch( $privileges ) {
   	case "administrator":			
  			if ( current_user_can( 'manage_options' ) )	{
				return TRUE;				
			}	
		break;
  		case "author":				
			if ( current_user_can( 'publish_posts' ) )	{
				return TRUE;				
		}	
		break;
    	case "contributor":				
			if ( current_user_can( 'edit_posts' ) )	{
					return TRUE;				
			}	
		break;
		case "editor":				
			if ( current_user_can( 'edit_pages' ) )	{
					return TRUE;				
			}
		break;
		default:
			return FALSE;
		}
	}

	/**
	* 	Return boolean. Positive is the users is logged in or if the configuration parameters bt_logged_user is not active
	**/

function bt_check_logged_users() {
	if ( ( is_user_logged_in() )	or ( 'FALSE' == get_option ( 'bt_user_logged') )) {
		return TRUE;		
		} else {
			 return FALSE;
			}
	}
	
function bt_add_color_to_css() {
	wp_enqueue_style(
		'custom-style',
		get_template_directory_uri() . '/css/bt-style.css'
	);
        $color = "#" . get_background_color(); //E.g. #FF0000
        $custom_css = "
                table.bt-large-table {
                        background-color: $color}
                table.bt-table {
                        background-color: $color}                       
                ";
        wp_add_inline_style( 'custom-style', $custom_css );
}

	
