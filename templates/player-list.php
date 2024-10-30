<?php
/**
 * Display users list	
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

?>
	<h1 class="bt-title"><?php _e('Players List', 'bt') ?></h1>
	
<div class="wrap player_list">
	<form name="form-suppress-players" method="post" action="">
		<?php if ( bt_check_admin() ):?> 	
			<table class="bt-large-table" cellspacing="0">
		<?php else :?>
			<table class="bt-table" cellspacing="0">
		<?php endif; ?>
			<thead>
				<tr>
					<?php if ( bt_check_admin() ):?> 			
					<th>
					<?php	_e('Delete','bt') ?>
					</th>
					<th>
					<?php _e('Modify', 'bt') ?>					
					</th>
					<?php endif; ?>
					<th>
						<?php	_e('Name','bt') ?>
					</th>
					<th>
						<?php	_e('First Name','bt') ?>
					</th>
					<?php if ( bt_check_admin() ):?>
					<th>
						<?php	_e('email address','bt') ?>
					</th>
					<?php endif; ?>
					<th>
						<?php	_e('contact','bt') ?>
					</th>				
				</tr>
			</thead>
			<tbody>
				<?php	foreach ( $players as $player ): ?>
				<tr>
					<?php if ( bt_check_admin() ):?> 			
						<td align="center">
						<input name='checkbox[]' type='checkbox' id='checkbox[]' value="<? echo $player->get_id() ;?>"> 
						</td>
						<td>
						<a href=<?php echo add_query_arg( 'player_id', $player->get_id() , site_url('./bridge-tournament/bt-edit-player')) ?>>Edit</a>							
						</td>
					<?php	endif;?>		
					<td><?php echo $player->get_name() ?></td>
					<td><?php echo $player->get_firstname() ?></td>
					<?php if ( bt_check_admin() ):?>
						<td><?php echo $player->get_email() ?></td>
					<?php endif; ?>	
					<td><a href=<?php echo add_query_arg( 'contact_id', $player->get_id() , site_url('./bridge-tournament/bt-contact-request')) ?>>Contact</a>
				</tr>
				<?php	endforeach; ?>
				<?php if ( bt_check_admin() ):?>
					<td colspan="6" align="center" ><input name="delete" type="submit" id="delete" value="<?php _e('Delete','bt') ?>"></td>
					</td></tr>
				<?php	endif;?>	
			</tbody>
		</table>
	</form>	
</div>
