<?php
/**
 * Display tournament list	
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

global $title;
?>

	<h1 class="bt-title"><?php _e('Upcoming Tournaments', 'bt') ?></h1>

<div class="wrap large-table-page">
	<form name="form-suppress-players" method="post" action="">
		<table class="bt-large-table" cellspacing="0">
			<thead>
			<tr>
			<?php if ( bt_check_admin() ):?> 			
				<th>
				<?php	_e('Delete','bt') ?>
				</th>
				<th>
				<?php _e('ID (edit)', 'bt') ?>				
				</th>
				<th>
				<?php _e('Type','bt') ?>			
				</th>
			<?php	endif;?>				
			<th>
				<?php	_e('Date', 'bt') ?>
			</th>
			<th>
				<?php	_e('Location','bt') ?>
			</th>
			<th>
				<?php	_e('Comment','bt') ?>
			</th>
			<th>
				<?php _e('Players','bt') ?>
			</th>
			<th>
				<?php _e('Register','bt') ?>
			</th>
			<th>
				<?php _e('partners','bt') ?>			
			</th>
    	</tr>
		</thead>
			<tbody>
			<?php		foreach ( $trn_data as $trn ): ?>
					<tr>
				<?php if ( bt_check_admin() ):?> 			
					<td align="center">
					<input name='checkbox[]' type='checkbox' id='checkbox[]' value="<? echo $trn->get_tr_id() ;?>"> 
					</td>
					<td><a href="<?php echo add_query_arg('trn_date', $trn->get_tr_date() , site_url('./bridge-tournament/bt-create-tournament')) ?>"><?php echo $trn->get_tr_id() ?></a></td>
					<td><?php echo $trn->get_tr_type() ?></td>
				<?php	endif;?>						
					<td><?php echo $trn->get_tr_date() ?></td>
					<td><?php echo $trn->get_location() ?></td>
         	   <td><?php echo $trn->get_comment() ?></td>
            	<td><a href="<?php echo add_query_arg( 'trn_date', $trn->get_tr_date() , site_url('./bridge-tournament/bt-registered-players')) ?>"> <?php _ex('Registered players', 'table', 'bt') ?></a></td> 
	            <td><a href="<?php echo add_query_arg( 'trn_date', $trn->get_tr_date() , site_url('./bridge-tournament/bt-registration')) ?>"> <?php _e('Register now', 'bt') ?></a></td> 
					<td><a href="<?php echo  add_query_arg( 'trn_date', $trn->get_tr_date(), site_url('./bridge-tournament/list_available') ) ?>"> <?php _e('List', 'bt') ?> </a></td>
				</tr>
			<?php		endforeach; ?>
			<?php if ( bt_check_admin() ):?>
				<td colspan="9" align="center" ><input name="delete" type="submit" id="delete" value="<?php _e('Delete','bt') ?>"></td>
				</td></tr>
			<?php	endif;?>									
			</tbody>
		</table>
	</form>
</div>
