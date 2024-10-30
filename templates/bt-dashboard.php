<?php
/**
 * Display defaut template page
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

?>

<div class="bt-dashboard">
	<h1 class="bt-title"><?php _e('Bridge Tournament dashboard','bt')?></h1>
        		
	<div class="bt-dashboard-link"><a href=<?php echo site_url('bridge-tournament/upcoming-tournaments') ?>><?php _e('Upcoming Tournaments','bt')?></a></div>
	<div class="bt-dashboard-comment"><?php _e('Register or find a partner for an upcoming tournaments.','bt')?></div>
	<div class="bt-dashboard-link"><a href=<?php echo site_url('bridge-tournament/bt-past-tournaments') ?>><?php _e('Past Tournaments','bt')?></a></div>
	<div class="bt-dashboard-comment"><?php _e('Check the results and scores of recent past tournaments.','bt')?></div>
	<div class="bt-dashboard-link"><a href=<?php echo site_url('bridge-tournament/bt-players') ?>><?php _e('Players List','bt')?></a></div>        		
	<div class="bt-dashboard-comment"><?php _e('Have a look on the players\' list of the club.','bt')?></div>
	<div class="bt-dashboard-link"><a href=<?php echo site_url('bridge-tournament/bt-player-request') ?>><?php _e('Join Us !','bt')?></a></div>        		
	<div class="bt-dashboard-comment"><?php _e('Before registering for the tournament, you have to be accepted by the organizers.','bt')?></div>
	<div class="bt-dashboard-link"><?php wp_loginout();  ?></div>		
		<div class="bt-dashboard-comment"><?php _e('If you have an account, use this link to login/logout.','bt')?></div>
	<?php if ( bt_check_admin() ):?> 			
		<h1 class="bt-title">Administration</h1>
		<div><?php bt_display_comment(__('The following features are restricted to users with administration privilegies.', 'bt'))?></div>
		<div class="bt-dashboard-link"><a href=<?php echo site_url('bridge-tournament/bt-create-player') ?>><?php _e('Add Players Profiles','bt')?></a></div>
		<div class="bt-dashboard-comment"><?php _e('Allow new players to register.','bt')?></div>
		<div class="bt-dashboard-link"><a href=<?php echo site_url('bridge-tournament/bt-create-tournament') ?>><?php _e('Create Tournament','bt')?></a></div>
		<div class="bt-dashboard-comment"><?php _e('Organize new tournaments.','bt')?></div>
	<?php endif;?>
</div>