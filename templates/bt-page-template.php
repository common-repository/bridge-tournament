<?php 
/*
Template Name : bt-template
*/
?>

<?php get_header(); ?>
 
<div id="container">
	<div id="content" role="main">
		<div class="entry-content">
			<?php 		
			$request = get_query_var( 'bt_id' );
			$trn_date = get_query_var( 'trn_date' );
			if ( bt_check_logged_users() ) {
				switch( $request ) {
    				case "upcoming-tournaments":
 					echo do_shortcode ( '[upcoming_tournament]');       		
        			break;				
   	 			case "bt-registered-players":
 					echo do_shortcode ( '[registered_players]');       		
        			break;					
    				case "bt-registration":
 					echo do_shortcode ( '[registration ]');       		
      	  		break;
        			case "bt-past-tournaments":
        			echo do_shortcode ( '[list_past_tournaments]' );
					break;
					case "bt-past-full-tournaments":
					echo do_shortcode ( '[list_past_full_tournaments]' );
					break;
   	     		case "bt-create-player":
      	  		echo do_shortcode( '[create_player]' );
        			break;
        			case "bt-edit-player":
	        		echo do_shortcode( '[edit_player]' );
   	     		break;
      	  		case "bt-create-tournament":
        			echo do_shortcode( '[create_tournament]' );
	        		break;
   	      	case "bt-results":
      	  		echo do_shortcode( '[display_results]' );
        			break;        		
        			case "bt-update-results":
	        		echo do_shortcode( '[update_results]' );
   	     		break;
      	  		case "bt-players":
        			echo do_shortcode( '[display_players]' );
	        		break;
					case "list_available":
      	  		echo do_shortcode( '[players_on_list]' );
        			break;
        			case "bt-player-request":
	        		echo do_shortcode( '[player_request]' );
   	     		break;
      	  		case "bt-contact-request":
        			echo do_shortcode( '[contact_player]' );
	        		break;
   	     		case "bt-error":
      	  		bt_error_page ( $error_msg );
        			break;
        			default :
	        		?>
   	     		<?php	
      	  		include BT_TEMPLATES_PATH . '/bt-dashboard.php';				
				}
			} else {
				bt_display_msg ( __('This tab is for logged in users only. Please log in to continue or contact the website administrator if your want to access this menu.', 'bt' ));
				wp_loginout();	
			}
		?>
		</div>
	</div>	
</div> 
 <br><br>
<?php get_footer(); ?>