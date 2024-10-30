<?php
/**
 * Bridge tournament classes
 *
 * @license GPLv3
 * @version 0.1
 * @since July 5th 2013
 * @author Frederic Hantrais frederic.hantrais@free.fr
 */

class tournament {
	/**
	 * @var interger
	 */
    private $tr_id;
	/**
	 * @var string
	 */
	private $tr_date;
    /**
	 * @var string
	 */
	 private $tr_type;
    /**
	 * @var string
	 */	 
	private $tr_location;
	/**
	 * @var string
	 */
	private $tr_comment;
		/**
	 * @var interger
	 */
	private $tr_deal_number;

	/**
	 * Constructor
	 */
	public function __construct( array $data = array() )
	{
		foreach ( $data as $key => $val )
      if ( property_exists($this, $key) )
      	$this->$key = $val;
	
	}

	/**
	* Set of Methods to retrieve object information
	*
	* @return integer/string
	*/
	public function get_tr_id() {
		return $this->tr_id;
    }

	public function get_tr_date() {
		return $this->tr_date;
   }
   public function get_tr_type() {
		return $this->tr_type;   	
   	}
	public function get_location()	{
		return $this->tr_location;
    }
	public function get_comment() {
		return $this->tr_comment;
	}


	/**
	 * Methods to set object information
	 *
	 * @return integer/string
	 */
	public function set_tr_id($id) {
		$this->tr_id = $id;
    }
	public function set_date($date) {
		$this->tr_date = $date;
    }
   public function set_tr_type($type) {
   	$this->tr_type = $type;
   } 
	public function set_location($location) {
		$this->tr_location = $location;
    }
	public function set_Comment($comment) {
		$this->tr_comment = $comment;
    }
	
	
	/**
	*	Display and edit tournaments
	* 	
	* @parameters : none 
	* @return: boolean 
	**/

	public function display_tournaments() {
		
		$dbr = new DB_Access();
		$trn_data = $dbr->get_tournaments(); //DB request
		if  ( ! empty( $trn_data )) {        
        include BT_TEMPLATES_PATH . '/tournament-list.php';
        } else {
        	bt_display_page_title( __('Upcoming Tournaments', 'bt' ));
        	_e( 'No tournament has been planned yet.','bt' );
      	}
      	
		// Check tournament deletion request 
		if (!empty($_POST['checkbox'])) {
			$deleted_rows = $_POST['checkbox'] ;
			$ind = 0 ;	
			foreach ($deleted_rows as $trn_id) {
			$trn = $dbr->get_trn_by_id( $trn_id );
			$trn_array = $dbr->get_tr_results( $trn );
			if (empty( $trn_array )) {
				$dbr->delete_tournament( $trn );				
					$relocation= get_permalink() ;
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$relocation.'">';
				}else { 
					bt_display_error ( sprintf( __('Tournament id %s : some players are registered for this tournament, 
						 please cancel their registration before deleting the tournament','bt'), $trn_id ) );
				}						
			}
		}	
	}
	
	/**
	*	Create a tournament
	* 	
	* @parameters : none 
	* @return: boolean 
	**/

	public function create_tournament() {
		
		include BT_TEMPLATES_PATH . '/tournament-add.php';			
		//if(!empty($_POST['tr_date'])&& !empty($_POST['tr_location'])) {
		if (!empty($_POST['confirmation'])) {
			$this->set_tr_id("");
			$this->set_date($_POST['tr_date']);
			$this->set_tr_type($_POST['tr_type']);
			$this->set_location($_POST['tr_location']);
			$this->set_comment($_POST['tr_comment']);
			//check the data type
			$dbr = new DB_Access();
			// check tournament Unicity. Is it really necessary ? It may bring unnecessary annoyances
			$this->sanitize_tournament();
			if (!empty($_POST['tr_date'])&& !empty($_POST['tr_location'])) { 
				if ( !$resultat = $dbr->add_tournament_in_db( $this )) {
							bt_display_error ( __( 'Tournament creation failed.', 'bt'));				
					} else {
					bt_display_ack ( __('Tournament successfully created.', 'bt'));	
				}
			} else {
				bt_display_error( __('Please note that Date and Location are mandatory to create a tournament', 'bt'));	
			}
		}
	}	

	/**
	*	Update a tournament
	* 	
	* @parameters : none 
	* @return: boolean 
	**/

	public function update_tournament() {

		if(!empty($_POST['tr_date'])&& !empty($_POST['tr_location'])) {
			$this->set_date($_POST['tr_date']);
			$this->set_tr_type($_POST['tr_type']);
			$this->set_location($_POST['tr_location']);
			$this->set_comment($_POST['tr_comment']);
			//check the data type
			$dbr = new DB_Access();
			$this->sanitize_tournament();
			
			if ( !$resultat = $dbr->update_tournament_in_db($this) ) {
				bt_display_error ( __( 'Tournament update failed.', 'bt'));				
			} else {
				// bt_display_ack ( __('Tournament successfully updated.', 'bt'));	
			};
		}
		include BT_TEMPLATES_PATH . '/tournament-add.php';	
	}

	/**	Sanitize tournament informations
	*
	* @parameters : none
	* @return: object
	**/

	public function sanitize_tournament() {
		
		$comment = $this->get_comment();
		$this->set_comment( sanitize_text_field (stripslashes($comment)));

		$location = $this->get_location();
		$this->set_location( sanitize_text_field (stripslashes( $location )));
		
		// Test date format or return false
		
		}
	
	
	/** Check that players are not already registered for a specific tournament 	
	* 
	* @parameters : object, object 
	* @return: boolean 
	**/

	public function check_trn_players($pl1, $pl2) {
		$dbr = new DB_Access();
		$result=$dbr->db_check_trn_players($this, $pl1, $pl2);
		return $result;
	}
	/** 
	* Send email confirmation
	*
	* @param: object (player), object (player)
	* @ return: boolean
	*
	**/

	public function send_confirmation_email($pl1, $pl2) {
    $trndate = date('F, jS, Y', strtotime($this->get_tr_date()));
    
		if ( "" == get_option('bt_custom_msg')) {     
    
   		$textpl1 = 'Dear '.$pl1->get_firstname() . ' ' . $pl1->get_name() . ',<br><br>';
    		$textpl1 = $textpl1.'You are registered to play bridge with '.
    						$pl2->get_firstname() . ' ' . $pl2->get_name() . ' on ' . $trndate . '. ';
  			$textpl2 = 'Dear '.$pl2->get_firstname().' '.$pl2->get_name().',<br><br>';
    		$textpl2 = $textpl2.'You are registered to play bridge with '. 
    						$pl1->get_firstname() . ' ' . $pl1->get_name() . ' on ' . $trndate . '. '; 
    
    		$information ='If you are not available to play on this date, please contact your partner and the Bridge club as soon as possible. <br><br>Best regards,<br> UN Bridge Club';
    		$textpl1 = $textpl1.$information; 
    		$textpl2 = $textpl2.$information;
    		$mail_subject = 'UN Bridge Club - Registration';
    	}
    	else {
    		$name1 = $pl1->get_firstname() . ' ' . $pl1->get_name();
    		$name2 = $pl2->get_firstname() . ' ' . $pl2->get_name();
    		$textpl1 = str_replace('[player]', $name1, get_option( 'bt_custom_msg' ) );
			$textpl2 = str_replace('[player]', $name2, get_option( 'bt_custom_msg' ) );
			
			$textpl1 = str_replace( '[date]', $trndate, $textpl1 );
			$textpl2 = str_replace( '[date]', $trndate, $textpl2 );
			
			$textpl1 = str_replace( '[partner]', $name2, $textpl1 );
			$textpl2 = str_replace( '[partner]', $name1, $textpl2 );
						
			$mail_subject = get_bloginfo( 'name' ) . __(' - Registration', 'bt');
			
		}
		$mail_subject = 'UN Bridge Club - Registration';
		$bt_headers = 'Content-type: text/html' ;
    	if ( wp_mail($pl1->get_email(), $mail_subject, $textpl1, $bt_headers) AND  
    	wp_mail($pl2->get_email(), $mail_subject, $textpl2, $bt_headers)) {
			bt_display_msg (' ', 'bt');    		
    	}else {
						bt_display_error ('An error occurred during the email transmission, please try again later', 'bt');
			}
	}

	/**
	* Display Tournament Results and score 
	*
	* @param: none
	* @return: none
	*
	**/
	
	public function display_results() {
		$dbr = new DB_Access();
		// Choose different request and display according to tournament type 
		
		if ( "Mitchell" == $this->get_tr_type() ) {
			$trn_data_NS = $dbr->get_tr_results_NS($this) ;
			$trn_data_EW = $dbr->get_tr_results_EW($this) ;						
			} else {	
		$trn_data = $dbr->get_tr_results($this); 	//DB request to retrieve tournament results and more
			}
		
		if ( "Mitchell" == $this->get_tr_type() ) {
			include BT_TEMPLATES_PATH . '/results-display-oriented.php';
			} else {
			include BT_TEMPLATES_PATH . '/results-display.php';				
				}
 }

	/** 
	* Update past tournament result for non oriented tournament (all except Mitchell)
	*
	* @param: none
	* @ return: none
	*
	**/

	public function update_results() {
		$dbr = new DB_Access();
		$trn_data = $dbr->get_tr_results($this); 	//DB request to retrieve tournament results and more

		// test if form has already been updated, and if so, get the data
		// test if form has already been updated, and if so, get the data
		if(isset($_POST['bt_upd_save'])) {		
			for ($ind=0;$ind<count($trn_data);$ind++) {
								
				 
				if (isset($_POST['pair_upd'][$ind])) {
					$trn_data[$ind]['pair_id'] = $this->check_update( $_POST['pair_upd'][$ind], 'pair', $ind);
					}
				if (isset($_POST['score_upd'][$ind])) {
                $trn_data[$ind]['score'] = $this->check_update( $_POST['score_upd'][$ind], 'score', $ind );
                }
            if (isset($_POST['percent_upd'][$ind])) {
                $trn_data[$ind]['percent'] = $this->check_update( $_POST['percent_upd'][$ind], 'percent', $ind );
                }
  	  		}	
    		$dbr->db_update_results($this,$trn_data);
		}

		// call php form to display and update data 
		include BT_TEMPLATES_PATH . '/results-update.php';
		// Wait for something is submitted
	}
	
		/** 
	* Update past tournament result for oriented tournament (Mitchell)
	*
	* @param: none
	* @ return: none
	*
	**/
		public function update_results_oriented() {
		$dbr = new DB_Access();
		$trn_data = $dbr->get_tr_results($this); 	//DB request to retrieve tournament results and more

		// test if form has already been updated, and if so, get the data
		if(isset($_POST['bt_upd_save'])) {		
			for ($ind=0;$ind<count($trn_data);$ind++) {
								
				 
				if (isset($_POST['pair_upd'][$ind])) {
					$trn_data[$ind]['pair_id'] = $this->check_update( $_POST['pair_upd'][$ind], 'pair', $ind);
					}
				if (isset($_POST['pair_orientation'][$ind])) {
					$trn_data[$ind]['orientation'] = $this->check_update( $_POST['pair_orientation'][$ind], 'orientation', $ind );
					}
				if (isset($_POST['score_upd'][$ind])) {
                $trn_data[$ind]['score'] = $this->check_update( $_POST['score_upd'][$ind], 'score', $ind );
                }
            if (isset($_POST['percent_upd'][$ind])) {
                $trn_data[$ind]['percent'] = $this->check_update( $_POST['percent_upd'][$ind], 'percent', $ind );
                }
  	  		}	
    		$dbr->db_update_results($this,$trn_data);
		}

		// call php form to display and update data 
		include BT_TEMPLATES_PATH . '/results-update-oriented.php';
		// Wait for something is submitted
	}
	
	public function register_players () {
		$dbr = new DB_Access();	
		// check if a date has already been entered in the href link
		$date_from_href = get_query_var( 'trn_date' );        

     	// Create user and date tables for later JS usage
		$pl_names = $dbr->get_full_names_list();
		$av_dates = $dbr->get_available_dates();
		include BT_TEMPLATES_PATH . '/register.php';
        
		if  (!empty( $_POST['reg_confirmation'] )) { 
			if(!empty($_POST['pl1_name'])&& !empty($_POST['pl2_name'])&& !empty($_POST['tr_date'])) {
      	  // check prior registration     
			 	
			  $player1_id = $dbr->player_exists($_POST['pl1_name']);
			  $player2_id = $dbr->player_exists($_POST['pl2_name']);
        
       	 $player1 = new player($dbr->get_player_by_name($_POST['pl1_name'])) ;
        	$player2 = new player($dbr->get_player_by_name($_POST['pl2_name'])) ;
        
      	  if ( $trn = $dbr->get_trn_by_date($_POST['tr_date']) ) {
      	  	} else {
      	  		return FALSE ; 
      	  		}
    
				if (($player1->check_player_type()) AND ($player2->check_player_type())) {
					if ($trn->check_trn_players($player1,$player2)) {
						if ( $resultat = $dbr->register_players($trn,$player1,$player2) ) {
							bt_display_ack( __('Your are now registered for the tournament, you (and your partner) will receive a confirmation email shortly','bt'));
						} else {
							bt_display_error (__('The registration failed, please try again later', 'bt' ));
							//return FALSE;
						}

				// remove user for availability list
						$dbr->remove_from_partners_list( $player1, $trn ) ;
						$dbr->remove_from_partners_list( $player2, $trn ) ;							
						
				// send confirmation email
						$trn->send_confirmation_email($player1, $player2);
					} else {
						bt_display_error( __('At least one player is already registered, please check the registered players list for this tournament.', 'bt'));
					}
				} else {
				  bt_display_error( __('Player name invalid format', 'bt'));
				}
			} else {
				bt_display_error( __('You can only register a pair (two players)', 'bt'));
			}
		} 	
	}
	
// Display and allow administrators to remove or modify registration entries

	public function manage_registrations ( $trn_date ) {
		$dbr = new DB_Access();
		$date_from_href = get_query_var( 'trn_date' );
		if ( ! $dbr->check_trn_date ( $date_from_href ) ) { 
		bt_display_error ( __( 'No tournament created at this date.','bt' ) );
		return FALSE;
		}

		$trndate = date('F, jS, Y', strtotime($date_from_href));
		$trn = $dbr->get_trn_by_date($date_from_href);
		$trn_result = $dbr->get_tr_results_oriented($trn); //DB request
	
		if (! empty ( $trn_result )) {
				if (isset($_POST['pair_upd']))  {
					$ind = 0;			
					for ($ind=0;$ind<count($trn_result);$ind++) { 
						if (isset($_POST['pair_upd'][$ind])) {
							$trn_result[$ind]['pair_id'] = $_POST['pair_upd'][$ind];
						}	
					}
					$dbr->db_update_results($trn,$trn_result);
				}
				if (isset($_POST['orientation']))  {
					$ind = 0;			
					for ($ind=0;$ind<count($trn_result);$ind++) { 
						if (isset($_POST['orientation'][$ind])) {
							$trn_result[$ind]['orientation'] = $_POST['orientation'][$ind];
						}	
					}
					$dbr->db_update_results($trn,$trn_result);
				}
				// Delete selected rows from form
				if (!empty($_POST['checkbox'])) {
					$deleted_rows = $_POST['checkbox'] ;
					foreach ($deleted_rows as $reg_id) {
							if ( $dbr->delete_registration_row ( $reg_id ) ){
							// bt_display_ack ('Registration has been successfully deleted', 'bt');
							$trn_result = $dbr->get_tr_results_oriented($trn);
						}
					} 
				}						
				include BT_TEMPLATES_PATH . '/registered-players.php' ;
		} else {
			bt_display_page_title ( sprintf( __( 'Registered Players for %s', 'bt'), $date_from_href ) );
			bt_display_msg( __('No player registered for this tournament yet','bt'));
		}
	}	

	/**
	/* Function used to add player on the list of available player looking for a partner
	**/


	public function set_as_available() {
		$dbr = new DB_Access();
			
		// check if  date has already been entered in the href link (should always be one)
		$date_from_href = get_query_var( 'trn_date' );
		if ( ! $dbr->check_trn_date ( $date_from_href ) ) { 
			bt_display_error ( __( 'No tournament created at this date.','bt' ) );
			return FALSE;
		}
		$trndate = date('F, jS, Y', strtotime($date_from_href));
		// Create user and date tables for later JS usag
		$pl_names = $dbr->get_full_names_list();
		$av_dates = $dbr->get_available_dates();

		if(!empty($_POST['pl1_name'])&& !empty($_POST['trn_date'])) {
        // check prior registration
        $player1 = new player($dbr->get_player_by_name($_POST['pl1_name']));
        $trn = $dbr->get_trn_by_date($_POST['tr_date']);
    
			if (($player1->check_player_type()) ) {
				if ($trn->check_trn_players($player1,$player1)) {
					$resultat = $dbr->db_set_available($trn,$player1); //DB Request
					$trn = $dbr->get_trn_by_date($_POST['tr_date']); // reload table
        // send confirmation email
        if ($trn->send_confirmation_email($player1, $player2)){
			bt_display_msg (__('You confirmation email has been sent.', 'bt'));
					} else {
						bt_display_error (__('An error occured duting the email transmission, please try again later.', 'bt'));
					}
    
				} else {bt_display_error ( __('Warning : Player(s) already registered, please check the player list.', 'bt'));}
			} else {bt_display_error ( __('The Player name has invalid format.', 'bt'));}
		include BT_TEMPLATES_PATH . '/available.php';
		}
	}
	
	public function show_available( $trn_date ) {
	$dbr = new DB_Access();		
	$date_from_href = get_query_var( 'trn_date' );
	if ( ! $dbr->check_trn_date ( $date_from_href ) ) { 
		bt_display_error ( __( 'No tournament created at this date.','bt' ) );
		return FALSE;
	}
	
	// Create user table for json usage (autocomplete)
		$pl_names = $dbr->get_full_names_list();

	$trndate = date('F, jS, Y', strtotime($date_from_href));
	$trn = $dbr->get_trn_by_date($date_from_href);
	$trn_avail_player = $dbr->db_get_available($trn); //DB request
		
		if (! empty ( $trn_avail_player )) {
		  if(!empty($_POST['pl_name'])) {
			$player = new player($dbr->get_player_by_name($_POST['pl_name']));
			$trn = $dbr->get_trn_by_date( $date_from_href );
    
			if (($player->check_player_type()) ) {
				if ($trn->check_trn_players($player,$player)) {
					$resultat = $dbr->db_set_available($trn,$player); //DB Request
					$trn_avail_player = $dbr->db_get_available($trn);  // Update data in player table
				}	else {
						bt_display_msg( __('You are already registered for this tournament! ', 'bt'));				
				}
			}	else {
			bt_display_msg( __('You have not entered the name of a valid player ', 'bt'));
			}
		 }
		include BT_TEMPLATES_PATH . '/list-available.php';	
		
		} else {
			$exception_msg = __('No player has registered on the availability list for this tournament yet','bt');
			include BT_TEMPLATES_PATH . '/list-available.php';
		}	
		// add user to Availability list
		
	}	
	
	public function display_past_tournaments() {
		$dbr = new DB_Access();
      $past_tournaments_list = $dbr->get_10_past_tournaments(); //DB request
      $bt_full_list = FALSE;
      
      if (! empty( $past_tournaments_list) ) {
	      include BT_TEMPLATES_PATH . '/past-tournaments.php';
      } else {
      	bt_display_page_title ( __( 'Past Tournaments', 'bt') );
			bt_display_msg(__( 'No tournaments past tournament has been organized yet', 'bt' ));	
		}
	}
	public function display_past_full_tournaments() {
		$dbr = new DB_Access();
      $past_tournaments_list = $dbr->get_past_tournaments(); //DB request
      $bt_full_list = TRUE;
      
      if (! empty( $past_tournaments_list) ) {
	      include BT_TEMPLATES_PATH . '/past-tournaments.php';
      } else {
      	bt_display_page_title ( __( 'Past Tournaments (full list)', 'bt') );
			bt_display_msg(__( 'No tournaments past tournament has been organized yet', 'bt' ));	
		}		
	}
	
	 /**
	 ** Type control functions
	 **/
	 
	 public function 	check_update ( $data, $type, $indice ) {
	 	$line = $indice + 1; // line where error occurred
		switch ($type ) {
			case 'pair' : 	 	
	 			if ( preg_match('/[[:digit:]]{1,2}/', $data ) ) {
		 		return $data ;
	 			} else 
	 			{ bt_display_error( __('Invalid pair on line ', 'bt' ) . $line . ' (' . $data . ').' ); 
	 			  return "0";	
	 			 } 
	 		case 'orientation' :
	 			if ( in_array( $data, array( 'EW', 'ew', 'e', 'w', 'W', 'E', 'EO', 'eo', 'o' ))) {
	 				return 'EW'; 
	 				} elseif  ( in_array( $data, array( 'NS', 'ns', 'N', 'n', 'S', 's' ))) {
	 					return 'NS';
	 					} else {
	 						bt_display_error( __('Invalid orientation in line ', 'bt' ) . $line. ' (' . $data . ').');
	 						return '';
	 			}
	 		case 'score':
	 			if ( preg_match('/^([[:digit:]]{1,2})(.|,)?([[:digit:]]{0,2})$/', $data )) {
	 				return str_replace(',', '.', $data);
	 				} else {
	 				bt_display_error( __('Invalid score in line ', 'bt' ) . $line . ' (' . $data . ').');
	 				return 0;
	 			}		  
			case 'percent' :
				 if ( preg_match( '/^([[:digit:]]{1,2})(.|,)?([[:digit:]]{0,2})$/' ,$data) ) {
	 				return str_replace(',', '.', $data);
	 				} else {
	 				bt_display_error( __('Invalide percentage in line: ', 'bt' ) . $line . ' (' . $data . ').');
	 				return 0;				
	 			}
	 	}
	}
}?>