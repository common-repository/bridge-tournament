<?php

/**
 * Bridge tournament classes
 *
 * @license GPLv3
 * @version 0.1
 * @since July 5th 2013
 * @author Frederic Hantrais frederic.hantrais@free.fr
 */
class player {

	/**
	 * @var interger
	 */
    private $pl_id;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $firstname;
	/**
	 * @var string
	 */
	private $email;
	

	/**
	 *
	 * @global wpdb $wpdb
	 * @param array $data 
	 */
	public function __construct( array $data = array() ) {
		foreach ( $data as $key => $val )
      if ( property_exists($this, $key) )
      	$this->$key = $val;
	}

	/**
	 * Method to get player ID
	 *
	 * @return integer
	 */
	public function get_id()
	{
		return $this->pl_id;
	}

	/**
	 * Method to set player ID
	 *
	 * @param integer $id
	 */
	public function set_id($id)
	{
		$this->pl_id = $id;
	}

	/**
	 * Method to get player name
	 *
	 * @return string
	 */
	public function get_name()
	{
		return $this->name;
	}

	/**
	 * Method to set player name
	 *
	 * @param string $name
	 */
	public function set_name($name)
	{
		$this->name = $name;
	}
	/**
	* Method to get player first name
	*
	* @return string
	*/
	public function get_firstname() {
		return $this->firstname;
	}
	/**
	* Method to set player name	
	*
	* @param string $firstname
	*/
	public function set_firstname($firstname) {
		$this->firstname = $firstname;
	}
	/**
	* Method to get player fullname	
	*
	* @param string $fullname
	*/
	public function get_fullname() {
		return $this->firstname . ' ' . $this->name;
	}
	/**
	* Method to get player email
	*
	* @return string
	*/
	public function get_email() {
		return $this->email;
	}
	/**
	* Method to set player Email
	*
	* @param string $email
	*/
	public function set_email($email) {
		$this->email = $email;
	}
	
	
	/** 
	* Method to create a new player
	*  @param none 
	*	@return : boolean
	**/	

	public function create_player() {
		ob_start();
		include BT_TEMPLATES_PATH . '/create-player.php';            
		if(!empty($_POST['pl_name'])&& !empty($_POST['firstname'])&& !empty($_POST['email'])) {
			// $new_player = new player;
			$this->set_id("\N");
			$this->set_name($_POST['pl_name']);
			$this->set_firstname($_POST['firstname']);
			$this->set_email($_POST['email']);
		//check the data type	
			$dbr = new DB_Access();
			if ($this->check_player_type()) {
		// Check that the users doesn't already exist
				$unicity = $dbr->check_unicity($this); 
				if ( $unicity == "0" ) {
		// insert result into the database 
					$resultat = $dbr->insert_players( $this ); //DB Request
					}
				else {
					bt_display_error( __('User already exists, please refer to the user list.','bt'));
					}
			}
			else {
				bt_display_error( __('The data you entered are not valid.','bt'));
			} 
			bt_display_ack( __('The user has been successfully created.', 'bt' ));
		} 
		return ob_get_clean();
	}	

	/** 
	* Modify the information related to a player
	*  @param none 
	*	@return : boolean
	**/	

	public function edit_player() {		
		$dbr = new DB_Access();
		ob_start();
		// Update data - when the form has been reloaded
		if(!empty($_POST['pl_name'])&& !empty($_POST['pl_firstname'])&& !empty($_POST['pl_email'])) {
			$this->set_name($_POST['pl_name']);
			$this->set_firstname($_POST['pl_firstname']);
			$this->set_email($_POST['pl_email']);
		}
		
		include BT_TEMPLATES_PATH . '/edit-player.php';     
		if(!empty($_POST['pl_name'])&& !empty($_POST['pl_firstname'])&& !empty($_POST['pl_email'])) {
			$this->set_name($_POST['pl_name']);
			$this->set_firstname($_POST['pl_firstname']);
			$this->set_email($_POST['pl_email']);
		//check the data type	
			if ($this->check_player_type()) {				
		// update result in the database 
					$resultat = $dbr->update_player( $this ); //DB Request	
					if ( $resultat != FALSE ){ 
						bt_display_ack( __('The user has been successfully updated.', 'bt' ));
					}					
			}
			else {
				bt_display_error( __('The data you entered are not valid.','bt'));
			} 
			
		} 
		return ob_get_clean();
	}	

	/** 
	* 	Display players list
	*  @param none 
	*	@return : display output 
	**/
	
	public function display_players_list() {
		$dbr = new DB_Access();
		$players = $dbr->get_players_list(); // DB Request

		if (!empty($_POST['checkbox'])) {
			$deleted_rows = $_POST['checkbox'] ;
			$delete_errors = FALSE ;	
			foreach ($deleted_rows as $player_id) {
				//First : check if the player exist in tournament base
				// If the player is registered or has history, the transaction will be rejected 
				$player_deleted = new player ( $dbr->get_player_by_id( $player_id ));

				if ( $dbr->search_player_in_registration( $player_id )) {
					bt_display_error ( sprintf ( __('The player %s has already played or is registered for an upcoming tournament: this data cannot be deleted from the database.', 'bt' ), $player_deleted->get_fullname()));
					$delete_errors = TRUE;							
				} else {
					$dbr->delete_player_row ( $player_id );
					bt_display_ack ( sprintf ( __('%s has been removed from the database.', 'bt' ), 
					$player_deleted->get_fullname()));													
				}						
			}
			return;
		}	
		ob_start();
		if ( ! empty( $players )) {		
			include BT_TEMPLATES_PATH . '/player-list.php'; // Display html results
		} else {
			bt_display_msg( __('No player has been registered yet. Please refer to the user manual to register new players.','bt' ));		
		}
		return ob_get_clean();		
	}	
	
	/** 
	* Method to check the type of entered values for a new player
	*  @param none 
	*	@return : boolean
	**/
	public function check_player_type() {
		$return = TRUE;
		sanitize_text_field( $this->name);
		if (!is_string($this->get_name())) {$return = FALSE; };
		sanitize_text_field( $this->firstname);
		if (!is_string($this->get_firstname())) {$return = FALSE; };
		if (!is_email($this->get_email())) {$return = FALSE; };
		return $return;
	}
	
	/** 
	* 	Method to display object content
	*  @param none 
	*	@return : none (printf)
	**/
	public function display_player_info() {
         printf ("-- %s -- %s -- %s", $this->get_name(),$this->get_firstname(),$this->get_email());
        }

	/** 
	* 	Method to manage request from new players
	*  @param none 
	*	@return : none 
	**/

	public function request() {
		include BT_TEMPLATES_PATH . '/bt-player-request.php';
		if(!empty($_POST['pl_name'])&& !empty($_POST['firstname'])&& !empty($_POST['email'])) {
			$this->set_name($_POST['pl_name']);
			$this->set_firstname($_POST['firstname']);
			$this->set_email($_POST['email']);
			if ( $this->check_player_type()) {
				$this->send_request_email();
				} else {
			 	bt_display_error( __('The data provided are not correct, please check the values your entered in the form','bt'));					
			}
		}
	}	
	
	/** 
	* 	Method to send email from new users requests to contact address 
	*  @param none 
	*	@return : none (email)
	**/	
	
	public function send_request_email() {
		
		$content = __('You have received a registration request from: <br>','bt') . "\n\n";
		$content = $content . __('Name : ','bt') . $this->get_name() . "<br>\n";
		$content = $content . __('First Name : ','bt') . $this->get_firstname() . "<br>\n";
		$content = $content . __('Email Address : ','bt') . $this->get_email() . "<br><br>\n\n";
		$content = $content . __('If this information seems correct, you still have to add this player to the list.','bt');
		$subject = get_bloginfo( 'name' ) . __(' : new user request.', 'bt' );
		$bt_headers = 'Content-type: text/html' ;
		if ( wp_mail(get_option( 'bt_contact_address' ), $subject, $content, $bt_headers) ) {
			_e('Your request has been sent successfully, you will receive an answer shortly.','bt' );
		} else {
			bt_display_error( __('An error occured during the operation, please try again later. We apologize for the inconvenience.','bt'));	
		}			
	}
	
	public function contact_request() {
		$dbr = new DB_Access;
		$contact_id = get_query_var( 'contact_id' );				
		$contact = new player ( $dbr->get_player_by_id( $contact_id ));
		
		include BT_TEMPLATES_PATH . '/player-contact-form.php';
		if( !empty( $_POST['Contact_Send'] )) {		
			if(!empty($_POST['sender_name'])&& !empty($_POST['sender_email'])&& !empty($_POST['sender_message'])) {

				$bt_sender_name = $_POST['sender_name'];
				sanitize_text_field( $bt_sender_name );
				$bt_sender_email = $_POST['sender_email'];
				sanitize_email( $bt_sender_email );
				$bt_sender_message = $_POST['sender_message'];
				sanitize_text_field( $bt_sender_message) ;
			
				if ( is_email( $bt_sender_email) &&  is_string( $bt_sender_name) &&  is_string ($bt_sender_message)) {
					$subject = 	get_bloginfo( 'name' ) . 
									sprintf ( __(' - You received a message from %s','bt'), $bt_sender_name );
					$bt_sender_message = $bt_sender_message . "\n\n" . 					
											sprintf ( __('Message sent by : %s (%s)', 'bt' ), $bt_sender_name, $bt_sender_email);  // End of message 
					$bt_headers = 'Content-type: text/html' ;
					if ( wp_mail($contact->get_email(), $subject, $bt_sender_message ) ) {

						$ack_msg = __('Your request has been sent successfully.','bt' );
						bt_display_ack ( $ack_msg );

					} else {	
						$error_msg = __('An error occured during the operation. We apologize for the inconvenience.','bt');
						bt_display_error( $error_msg );	
					}
				} else {
					$error_msg = __( 'Incorrect data entered in the form, please check again.' , 'bt ');	
			 		bt_display_error( $error_msg );				
				}
			}	else {
				$error_msg = __( ' All the fields must completed before sending the form.', 'bt');
				bt_display_error( $error_msg );
			}
		}
	}	
}
