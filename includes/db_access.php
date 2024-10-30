<?php
//include_once 'player.php';
//include_once 'tournament.php';

/**
 * Bridge Tournament database class
 *
 * @license GPLv3
 * @version 0.1 
 * @author Frederic Hantrais frederic.hantrais@free.fr
 */
class DB_Access {
	/**
	 * @var wpdb
	 */
	private $db;
	/**
	 * @var string
	 */
	private $player_table;

	/**
	 * @var string
	 */
	private $tournament_table;
	
	/**
	 * @var string
	 */
	private $registration_table;
	
	/**
	 * @var string
	 */
	private $partners_table;

	/**
	 * Constructor
	 *
	 * @access public
	 * @global wpdb $wpdb
	 */
	public function __construct() {
		global $wpdb;
		
		$this->db = $wpdb;
		$this->player_table = $this->db->prefix."bt_player";
		$this->tournament_table = $this->db->prefix."bt_tournament";
      $this->registration_table = $this->db->prefix."bt_registration";
      $this->partners_table = $this->db->prefix."bt_partners";
	}

	/**
	 * Create the BT player table in WP database
	 *
	 * @access public
	 * @global wpdb $wpdb
	 * @return void
	 */
	public static function create_tables() {
		global $wpdb;
		// Check if the BT player table already exists
		if ( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bt_player'") != "{$wpdb->prefix}bt_player" ) {
			// Creating the BT Player table if  not exists
			$wpdb->query("
			CREATE TABLE {$wpdb->prefix}bt_player (
				pl_id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(50),
				firstname VARCHAR(50),
				fullname VARCHAR(100),
				email VARCHAR(300),
				status ENUM('active','inactive') DEFAULT 'active'
			);");
		}
		if ( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bt_tournament'") != "{$wpdb->prefix}bt_tournament" ) {
			// Creating the BT Tournament table if not exists 
 			$wpdb->query("
            CREATE TABLE {$wpdb->prefix}bt_tournament (
				tr_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				tr_date DATE,
				tr_type VARCHAR(30),
				tr_location VARCHAR(30),
				tr_comment VARCHAR(200),
				status ENUM('active','canceled') DEFAULT 'active'
			);");
    }
    if ( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bt_registration'") != "{$wpdb->prefix}bt_registration" ) {
			// Creating the BT Registration table if not exists 
 			$wpdb->query("
         	CREATE TABLE {$wpdb->prefix}bt_registration (
         	reg_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				tr_id INT NOT NULL,
				pl1_id INT NOT NULL,
            pl2_id INT NOT NULL,
            orientation VARCHAR(2),
            pair_id INT,
            score INT,
            percent DECIMAL(4,2)
			);");
     }
     if ( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bt_partners'") != "{$wpdb->prefix}bt_partners" ) {
			// Creating the BT Partners table if not exists 
 			$wpdb->query("
         	CREATE TABLE {$wpdb->prefix}bt_partners (
         	request_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				tr_id INT NOT NULL,
				pl_id INT NOT NULL
			);");
		}
	}


	/**
	 * Remove the plugin database table and folder with all files
	 * if it's set in the plugin configuration
	 *
	 * @access public
	 * @global wpdb $wpdb
	 */

function remove_tables() {
	global $wpdb;
	// Get the option to remove plugin table
	$remove_table = get_option('bt_remove_tables', "TRUE" );
	// Remove table if it's OK
	if ( "TRUE" == $remove_table ) {
	echo 'remove tables !';
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_player;");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_tournament;");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_registration;");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bt_partners;");
	}	
}

	/**
	 * Return the BT player table name
	 *
	 * @access public
	 * @return string
	 */
	public function get_player_table() {
		return $this->player_table;
	}

	/**
	 * Return the BT Tournament table name
	 *
	 * @access public
	 * @return string
	 */
	public function get_tournament_table() {
		return $this->tournament_table;
	}


								/** Players functions **/


	/** 
	 * Method to retrieve player object from player name
	 *
	 * @access public
	 * @param fullname
	 * @return array
	 *
	 */
    public function get_player_by_name($fullname) {
		$sql = "
		SELECT * 
		FROM {$this->player_table}
      WHERE fullname = '$fullname'";
		$results = $this->db->get_row( $this->db->prepare( $sql, 0), ARRAY_A);
		return $results;
	}

	/** 
	* check if a player already exists in player table
	*  @param fullname 
	*	@return : interger (player ID)
	**/

	public function player_exists( $fullname ) {
		$sql = "SELECT pl_id 
		FROM {$this->player_table} 
		WHERE fullname = '$fullname'";
		$player_id = $this->db->get_var( $this->db->prepare( $sql, 0));
		return $player_id;	
		}

	/**
	*	Return the list of players 
	*
	* @access : public 
	* @param : none 
	* @return : array
	**/ 
	public function get_players_list() {
		$players = array();
		$sql = "
		SELECT * 
		FROM {$this->player_table} 
		ORDER by NAME ASC";
		
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		
		foreach ($results as $r)
			$players[] = new player ( $r);

		return $players;
	}

	/**
	*	Return the list of player's full name 
	*
	* @access : public 
	* @param : none 
	* @return : array
	**/ 

	public function get_full_names_list() {
		$sql = "
		SELECT fullname 
		FROM {$this->player_table} ";
		$results = $this->db->get_col( $this->db->prepare( $sql, 0), 0);
		return $results;
	}

		/**
	*	Retrieve player from database for a specific pl_id
	*
	* @access : public 
	* @param : integer
	* @return : object (player)
	**/
	
	public function get_player_by_id( $pl_id ) {
		$sql = "
		SELECT * 
		FROM {$this->player_table}
      WHERE pl_id = '$pl_id'";
		$results = $this->db->get_row( $this->db->prepare( $sql, 0), ARRAY_A);
		return $results;
	}
	
		/**
	*	Insert new player in database
	*
	* @access : public 
	* @param : array
	* @return : boolean (insert request return)
	**/ 

	public function insert_players($player_data) {
		
		$data =  array('pl_id' => "", 
					'name' => $player_data->get_name(), 
					'firstname' => $player_data->get_firstname(), 
					'fullname' => $player_data->get_firstname()." ".$player_data->get_name(),
					'email' => $player_data->get_email());
      
      $dbname= $this->player_table;
      $result = $this->db->insert($dbname, $data);
      return $result;
	}
	
			/**
	*	Insert new player in database
	*
	* @access : public 
	* @param : array
	* @return : boolean (insert request return)
	**/ 

	public function update_player($player_data) {
		
		$data =  array( 'name' => $player_data->get_name(), 
					'firstname' => $player_data->get_firstname(), 
					'fullname' => $player_data->get_firstname()." ".$player_data->get_name(),
					'email' => $player_data->get_email());
				
		$where = array ('pl_id' => $player_data->get_id()) ;
      
      $dbname= $this->player_table;
      $result = $this->db->update($dbname, $data, $where);
      return $result;
	}	
	
	/**
	* Remove player from database
	*
	* @access : public 
	* @param : interger
	* @return : boolean (delete request return)
	**/ 

	public function delete_player_row( $player_id ) {
	
		$dbname = $this->player_table ;		
		$this->db->delete($dbname, array('pl_id' => $player_id ));
	}
	
		/**
	* search player in tournament table (is player registered or has already played)
	*
	* @access : public 
	* @param : integer (player _id)
	* @return : boolean (true if player is in tournament)
	**/ 

	public function search_player_in_registration( $player_id ) {
	
		$sql = "
		SELECT tr_id 
		FROM {$this->registration_table}
		WHERE pl1_id = '$player_id'
		OR pl2_id = '$player_id'";
		
		$result = $this->db->get_row( $this->db->prepare( $sql, 0), ARRAY_A);
	
		if (!empty($result)){
			return TRUE ;}
		else { 
			return FALSE;
		}	
	}
	
		/**
	*	check that the new player doesn't already exist in player table
	*
	* @access : public 
	* @param : object (player)
	* @return : interger
	**/ 

    public function check_unicity( $player ) {
		$sql = "SELECT COUNT(*) 
		FROM {$this->player_table} 
		WHERE name = '{$player->get_name()}' 
		AND firstname = '{$player->get_firstname()}';";
		$count = $this->db->get_var( $this->db->prepare( $sql, 0));	
		return $count;
    }

									/**  Tournament functions	**/
	
	/**
	*	Return the list of tournaments dates  
	*
	* @access : public 
	* @param : none 
	* @return : array
	**/ 
	public function get_available_dates() {
		$sql = "
		SELECT tr_date 
		FROM {$this->tournament_table}
      WHERE tr_date >= CURDATE() ";
		$results = $this->db->get_col( $this->db->prepare( $sql, 0), 0);
		return $results;
	}
 
 	/**
	*	check that a tournament is registered for a given date  
	*
	* @access : public 
	* @param : date 
	* @return : boolean
	**/ 
	public function check_trn_date( $date ) {
		$sql = "
		SELECT * 
		FROM {$this->tournament_table}
		WHERE tr_date = '$date'";
		
		$result = $this->db->get_row( $this->db->prepare( $sql, 0), ARRAY_A);
	
		if (!empty($result)){
			return TRUE ;}
		else { 
			return FALSE;
		}
	}
    
	/**
	*	Return the list of future tournaments  
	*
	* @access : public 
	* @param : none 
	* @return : object array
	**/ 
	
	public function get_tournaments() {
		$tournaments = array();
		$sql = "
		SELECT * 
		FROM {$this->tournament_table}
		WHERE tr_date >= CURDATE()
		order by tr_date ASC ";
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		foreach ($results as $r){ 
			$tournaments[] = new tournament ( $r);
		}	
		return $tournaments;
	}

	/**
	*	Return the list the 10 last tournaments
	*
	* @access : public 
	* @param : none 
	* @return : object array
	**/ 

	public function get_10_past_tournaments() {
		$tournaments = array();
		$sql = "
		SELECT * 
		FROM {$this->tournament_table}
      WHERE tr_date < CURDATE()
		order by tr_date DESC 
		LIMIT 10";
		
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);

		foreach ($results as $r) {
			$tournaments[] = new tournament ( $r); }
		return $tournaments;
	}
	
	
		/**
	*	Return the list of past tournaments
	*
	* @access : public 
	* @param : none 
	* @return : object array
	**/ 

	public function get_past_tournaments() {
		$tournaments = array();
		$sql = "
		SELECT * 
		FROM {$this->tournament_table}
      WHERE tr_date < CURDATE()
		order by tr_date DESC";
		
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);

		foreach ($results as $r) {
			$tournaments[] = new tournament ( $r); }
		return $tournaments;
	}

	/**
	*	Return tournament for a specific date  
	*
	* @access : public
	* @param : string (date)
	* @return : object (tournament)
	**/ 

	public function get_trn_by_date( $date ) {
		$sql = "
		SELECT * 
		FROM {$this->tournament_table}
		WHERE tr_date = '$date'";
		
		$result = $this->db->get_row( $this->db->prepare( $sql, 0), ARRAY_A);
		if (!empty($result)){
			$tournament = new tournament($result);}
		else {_e('No Tournament available at this date.','bt'); 
				 $tournament = FALSE;}
		return $tournament;
	}	

	public function get_trn_by_id( $id ) {
		$sql = "
		SELECT * 
		FROM {$this->tournament_table}
		WHERE tr_id = '$id'";
		
		$result = $this->db->get_row( $this->db->prepare( $sql, 0), ARRAY_A);
		if (!empty($result)){
			$tournament = new tournament($result);}
		else {_e('No available tournament','bt'); 
				 $tournament = FALSE;}
		return $tournament;
	}	

	/**
	*	Insert a tournament in database  
	*
	* @access : public 
	* @param : string (date)) 
	* @return : boolean (sql request status)
	**/ 
	public function add_tournament_in_db($tr_data) {
		global $wpdb;
		$data =  array('tr_id' => "", 
							'tr_date' => $tr_data->get_tr_date(),
							'tr_type' => $tr_data->get_tr_type(), 
							'tr_location' => $tr_data->get_location(), 
							'tr_comment' => $tr_data->get_comment());
        
		$dbname= $this->tournament_table;
		$result = $this->db->insert($dbname, $data);
		return $result;
    }
    
    	/**
	*	Modify a tournament in database  
	*
	* @access : public 
	* @param : string (date)) 
	* @return : boolean (sql request status)
	**/ 
	public function update_tournament_in_db($tr_data) {
		global $wpdb;
		$dbname= $this->tournament_table;
				
		$result = $this->db->update(
					$dbname,
					array('tr_date' => $tr_data->get_tr_date(), 
							'tr_type' => $tr_data->get_tr_type(),
							'tr_location' => $tr_data->get_location(), 
							'tr_comment' => $tr_data->get_comment()),
					array( 'tr_id' => $tr_data->get_tr_id() )); 
						
		return $result;
    }
    
 /**
	*	Delete a tournament in database  
	*
	* @access : public 
	* @param : tournament 
	* @return : boolean (sql request status)
	**/ 
	public function delete_tournament( $trn ) {
		$trn_id = $trn->get_tr_id();
		$dbname = $this->tournament_table ;		
		$this->db->delete($dbname, array('tr_id' => $trn_id ));
	}

	/**
	*	Check that players are not already registered for a tournament  
	*
	* @access : public 
	* @param : object (tournament), object (player), object (player)  
	* @return : boolean
	**/ 

	public function db_check_trn_players($trn, $pl1, $pl2) {
		$sql = "
		SELECT count(*) 
		FROM {$this->registration_table}
		where  (pl1_id = {$pl1->get_id()}
			OR pl1_id = {$pl2->get_id()}
			OR pl2_id = {$pl1->get_id()}
			OR pl2_id = {$pl2->get_id()}) 
			AND tr_id = {$trn->get_tr_id()}";
			
		// $dbname = $this->registration_table; 					-> Useless ? 
		$count = $this->db->get_var( $this->db->prepare( $sql, 0));
		if ($count != 0 ){return FALSE;} else {return TRUE;}
}
	/**
	*	Register players for a tournament (insert in registration table)  
	*
	* @access : public 
	* @param : object (tournament), object (player), object (player)  
	* @return : boolean (sql request status)
	**/ 

	public function register_players ($trn, $pl1, $pl2) {
		// Build data array to insert
		$data =  array('tr_id' => $trn->get_tr_id(), 
							'pl1_id' => $pl1->get_id(), 
							'pl2_id' => $pl2->get_id(), 
							'pair_id' => "",
							'score' => "",
							'percent' => "");
							
		$dbname= $this->registration_table;
		$result = $this->db->insert($dbname, $data);
		return $result;
	}	
		
											/** Availability List **/
	
	
	/**
	*	set player on list as available for a tournament (insert in partners table)  
	*
	* @access : public 
	* @param : object (tournament), object (player)
	* @return : boolean (sql request status)
	**/ 

	public function db_set_available ($trn, $pl1) {
		// Build data array to insert
		$data =  array('request_id' => "",
							'tr_id' => $trn->get_tr_id(), 
							'pl_id' => $pl1->get_id());
							
		$dbname= $this->partners_table;
		$result = $this->db->insert($dbname, $data);
		return $result;
	}
	
		public function db_get_available($trn) {
		// Build result array 
		$sql = "
			SELECT a1.fullname as fullname,
					 b.pl_id as player_id
			FROM  {$this->player_table} a1, {$this->partners_table} b
			WHERE b.tr_id = {$trn->get_tr_id()}
				AND a1.pl_id = b.pl_id";
			
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		return($results);
	}
	
	
	/**
	*	Delete rows from registration table
	*
	* @access : public 
	* @param : interger (reg_id)  
	* @return : boolean
	**/ 		

	public function delete_registration_row ( $reg_id ) {
		
			$dbname = $this->registration_table ;		
			if ($this->db->delete($dbname, array('reg_id' => $reg_id ))) {
				return TRUE;
				}
		}
	
	/**
	*	Return the results for a specific tournament
	*
	* @access : public 
	* @param : object (tournament)  
	* @return : array
	**/ 	
	
	public function get_tr_results($trn) {
		// Build result array 
		$sql = "
			SELECT a1.fullname as player1, a2.fullname as player2, b.pair_id, b.orientation, b.score, b.percent, b.reg_id
			FROM  {$this->player_table} a1,  {$this->player_table} a2, {$this->registration_table} b
			WHERE b.tr_id = {$trn->get_tr_id()}
				AND a1.pl_id = b.pl1_id
				AND a2.pl_id = b.pl2_id
			ORDER BY score desc";
			
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		return($results);
	}
	
		/**
	*	Return the results for a specific tournament, oriented, sorted by score and line (NS/EW)
	*
	* @access : public 
	* @param : object (tournament)  
	* @return : array
	**/ 	
	
	public function get_tr_results_oriented($trn) {
		// Build result array 
		$sql = "
			SELECT a1.fullname as player1, a2.fullname as player2, b.pair_id, b.orientation, b.score, b.percent, b.reg_id
			FROM  {$this->player_table} a1,  {$this->player_table} a2, {$this->registration_table} b
			WHERE b.tr_id = {$trn->get_tr_id()}
				AND a1.pl_id = b.pl1_id
				AND a2.pl_id = b.pl2_id
			ORDER BY b.orientation desc , b.score desc, b.pair_id asc";
			
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		return($results);
	}

	/**
	*	Return the results for a specific tournament and only NS players
	*
	* @access : public 
	* @param : object (tournament)  
	* @return : array
	**/ 	
	
	public function get_tr_results_NS($trn) {
		// Build result array 
		$sql = "
			SELECT a1.fullname as player1, a2.fullname as player2, b.pair_id, b.orientation, b.score, b.percent, b.reg_id
			FROM  {$this->player_table} a1,  {$this->player_table} a2, {$this->registration_table} b
			WHERE b.tr_id = {$trn->get_tr_id()}
				AND a1.pl_id = b.pl1_id
				AND a2.pl_id = b.pl2_id
				AND b.orientation = 'NS'
			ORDER BY b.score desc";
			
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		return($results);
	}

	/**
	*	Return the results for a specific tournament and only NS players
	*
	* @access : public 
	* @param : object (tournament)  
	* @return : array
	**/ 	
	
	public function get_tr_results_EW($trn) {
		// Build result array 
		$sql = "
			SELECT a1.fullname as player1, a2.fullname as player2, b.pair_id, b.orientation, b.score, b.percent, b.reg_id
			FROM  {$this->player_table} a1,  {$this->player_table} a2, {$this->registration_table} b
			WHERE b.tr_id = {$trn->get_tr_id()}
				AND a1.pl_id = b.pl1_id
				AND a2.pl_id = b.pl2_id
				AND b.orientation IN ('EW','EO')
			ORDER BY b.score desc";
			
		$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		return($results);
	}


	/**
	*	Update registration table  
	*
	* @access : public 
	* @param : object (tournament), array 
	* @return : boolean (sql insert status)
	**/ 
		
	public function db_update_results($trn,$data) {
		for ($i=0;$i<count($data);$i++) {
    		$sql = " UPDATE {$this->registration_table} 
                  SET pair_id = {$data[$i]['pair_id']},
                  	orientation = '{$data[$i]['orientation']}',
                  	score = {$data[$i]['score']},
                  	percent = {$data[$i]['percent']}
                  WHERE tr_id = {$trn->get_tr_id()}
                  AND reg_id = {$data[$i]['reg_id']}";
                  
			$results = $this->db->get_results( $this->db->prepare( $sql, 0), ARRAY_A);
		 
      	// Update percent pert=score/sum(score)
      	// sql_percent = 
      }
		return($results);
	}

	/**
	*	remove user from availabilily database   
	*
	* @access : public 
	* @param : object (player), object (tournament)  
	* @return : boolean (sql status)
	**/ 
	
	public function remove_from_partners_list ( $player, $trn ) {
		$dbname = $this->partners_table ;		
		$this->db->delete($dbname, array('pl_id' => $player->get_id(), 'tr_id' => $trn->get_tr_id() ));
		}
	
}?>