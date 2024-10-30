<?php
class bt_admin_pages
{
	/**
	*	This class defines and stores the parameters 
	*	of the plugin WP Bridge Tournament
	* 	Also defines the submenus available in the dashboard
	**/

	public function __construct() {
	
	add_action ('admin_menu', array( $this,'create_bt_menu' ) );
	add_action ('admin_init', array( $this, 'register_bt_options') );	
	}

	public function create_bt_menu() {

   add_menu_page(__('Bridge options', 'bt'), __('BT Options', 'bt'), 'manage_options', 'bt-menu-options', array ($this , 'bt_options' ),plugins_url( 'bridge-tournament/img/bt-icon-16.png'));

	// Add submenu page "New Tournament"    
  	add_submenu_page("bt-menu-options", __('New tournament', 'bt'), __('New Tournament', 'bt'), "edit_pages", "create_tournament", "bt_create_tournament");
   // Adding plugin submenu "New Player"
   add_submenu_page("bt-menu-options", __('New player', 'bt'), __('New Player', 'bt'), "edit_pages", "add_player", "bt_player_add");
	}
	
	public function register_bt_options() {
		register_setting ( 'bt-options-group', 'bt_club_name', array( $this, 'sanitize_string') );
		register_setting ( 'bt-options-group', 'bt_contact_address', array($this, 'sanitize_email' ));
		register_setting ( 'bt-options-group', 'bt_default_location', array( $this, 'sanitize_string' ));
		register_setting ( 'bt-options-group', 'bt_custom_msg', array( $this, 'sanitize_string' ));
		register_setting ( 'bt-options-group', 'bt_join_message', array( $this, 'sanitize_string' ));		
		register_setting ( 'bt-options-group', 'bt_admin_privileges', array($this, 'sanitize_string' ));
		register_setting ( 'bt-options-group', 'bt_user_logged', array($this, 'sanitize_string' ));		
		register_setting ( 'bt-options-group', 'bt_remove_tables', array($this, 'sanitize_string' ));	


		if ( "" == get_option( 'bt_join_message')) {
			$join_default_message = __( 'Before registering for the tournament, your membership must be validated by the club. 
     							If you cannot find your name in the players\' list, you should probably fill  
        						the following form. The information will be sent to the website manager  
        						who will add your name to the list.', 'bt' );
       	update_option('bt_join_message', $join_default_message );		
      }
 		if ( "" == get_option( 'bt_admin_privileges')) {       					
      	update_option( 'bt_admin_privileges', 'editor') ; 							
		}
		if ( "" == get_option( 'bt_remove_tables')) {       					
      	update_option( 'bt_remove_tables', 'TRUE') ; 							
		}
		if ( "" == get_option( 'bt_user_logged')) {       					
      	update_option( 'bt_remove_tables', "FALSE") ; 							
		}
	}

	public function bt_options() {
	?>
	<div class="bt-options">
	<h2><?php _e('Bridge Tournament Options', 'bt') ?></h2>

	<form method="post" action="options.php">
   	 <?php settings_fields( 'bt-options-group' ); ?>
    	<table class="form-table">
     		<tr valign="top">
        	<th scope="row"><?php _e('Club Name:', 'bt') ?></th>
        	<td><input type="text" name="bt_club_name" value="<?php echo get_option('bt_club_name'); ?>" /></td>
        	</tr>
         
        	<tr valign="top">
        	<th scope="row"><?php _e('Contact Address:', 'bt') ?></th>
        	<td><input type="text" name="bt_contact_address" value="<?php echo get_option('bt_contact_address'); ?>" /></td>
        	</tr>
        	
        	<tr valign="top">
        	<th scope="row"><?php _e('Default Tournament Location:', 'bt') ?></th>
        	<td><input type="text" name="bt_default_location" value="<?php echo get_option('bt_default_location'); ?>" /></td>
        	</tr>
   
         <tr valign="top">
        	<th scope="row"><?php _e('Required Wordpress privileges for administration:', 'bt' ) ?></th>
        	<td><select name="bt_admin_privileges">
				<option value="contributor" <?php if( "contributor" == get_option('bt_admin_privileges')) echo "selected = 'selected'"; ?> ><?php _e('contributor', 'bt') ?></option>
 				<option value="author"  <?php if( "author" == get_option('bt_admin_privileges')) echo "selected = 'selected'"; ?> ><?php _e('author', 'bt') ?></option>
				<option value="editor"  <?php if( "editor" == get_option('bt_admin_privileges')) echo "selected = 'selected'"; ?> ><?php _e('editor', 'bt') ?></option>
				<option value="administrator"<?php if( "administrator" == get_option('bt_admin_privileges')) echo "selected = 'selected'"; ?>><?php _e('administrator', 'bt') ?></option>
			</select></td> 
        	</tr>
        	
        	<tr valign="top">
        	<th scope="row"><?php _e('Keep data when removing the plugin:', 'bt') ?></th>
        	<td><input type="radio" name="bt_remove_tables" value="TRUE" <?php if( "TRUE" == get_option('bt_remove_tables')) echo "checked = 'true'"; ?>> <?php _e('Keep tables', 'bt') ?>
				<input type="radio" name="bt_remove_tables" value="FALSE" <?php if( "FALSE" == get_option('bt_remove_tables')) echo "checked = 'true'"; ?>> <?php _e('Remove tables', 'bt') ?>
        	</td>
        	        	<tr valign="top">
        	<th scope="row"><?php _e('User logged in mode:', 'bt')?></th>
        	<td><input type="radio" name="bt_user_logged" value="TRUE" <?php if( "TRUE" == get_option('bt_user_logged')) echo "checked = 'true'"; ?>> <?php _e('Yes', 'bt') ?> 
				<input type="radio" name="bt_user_logged" value="FALSE" <?php if( "FALSE" == get_option('bt_user_logged')) echo "checked = 'true'"; ?>> <?php _e('No', 'bt') ?>
        	</td>
        	
        	</tr>
   
   
        	</table>

	
        	
       	<p><i><?php _e('Please use the following area to write the custom message sent to players (and partners) when the registration for a tournament 
        	is completed. Please refer to the documentation for detailed explanations and examples. You can use the following tags:', 'bt') ?><br>
        	<b>[player]</b><?php _e(' : name of the player', 'bt') ?><br>
        	<b>[partner]</b><?php _e(' : name of the partner', 'bt') ?><br>
        	<b>[date]</b><?php _e(' : tournament date', 'bt') ?> </i></p>

        	<table class="form-table">
       	<tr valign="top">
        	<th scope="row"><?php _e('Custom Message:', 'bt') ?></th>
        	<td><textarea id="custom_msg" name="bt_custom_msg" cols="80" rows="8" /><?php echo get_option('bt_custom_msg');?> </textarea></td>
        	</tr>
        	</table>
        	<p><br><?php _e('Please enter here the message displayed on the "Join Us!" Page.', 'bt') ?><p>
        	
        	<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php _e('Join Us message:', 'bt')?></th>
			<td><textarea name="bt_join_message" cols="80" rows="8" /><?php echo get_option('bt_join_message');?> </textarea></td>
			</tr>
    		</table>

    	<?php submit_button();?>

		</form>
		</div>
	<?php
	}
	
	public function sanitize_string ( $input) {
		    	
		if( !is_string( $input ))
		$input = sanitize_text_field( $input );  
		return $input;
	}
	
   	public function sanitize_email ( $input) {
		    	
		if( !is_email( $input ))
		$input = "";  
		return $input;
	} 

		/** 	Create Bridge_tournament Pages 
		**/	
			
	public static function create_pages() {;
	
		// Create Bridge Tournament Page - Every BT page should have this page for parent
		
		$pg_name_base = __('Bridge Tournament', 'bt');
		if ( !get_page_by_title( $pg_name_base )) {
			$post_array = array( 'post_title' => $pg_name_base,
										'post_name' => 'bridge-tournament',
										'post_status' => 'publish',
										'comment_status' => 'closed', 
										'post_type' => 'page' );
			$parent_ID = wp_insert_post($post_array);					
			} else {
			
			$page = get_page_by_title( $pg_name_base );
			$parent_ID = $page->ID; 				
				}
		}
		
		/** 
		*	This function test if a user is allowed to execute BT administration actions
		*	If the user has not enough wordpress capabilities (default is editor)
		*	the function will display a standard message error
		**/ 
		
	public function bt_admin_message( $user ) {
			
		$privileges = get_option( 'bt_admin_privileges');		
		switch( $privileges ) {
    		case "administrator":				
			if ( !is_administrator( $user ) )	{
					_e( 'This function requires administration privileges.','bt' );				
			}	
		break;
    		case "author":				
			if ( !is_author( $user ) )	{
					_e( 'This function requires administration privileges.','bt' );				
			}	
		break;
    	case "contributor":				
			if ( !is_contributor( $user ) )	{
					_e( 'This function requires administration privileges.','bt' );				
			}	
		break;
		default:
			if ( !is_editor( $user ) )	{
					_e( 'This function requires administration privileges.','bt' );				
			}			
		}
	}
	
}
	
?>
