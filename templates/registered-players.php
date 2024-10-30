<?php
/**
 * Display and modify registered players for a specific game (admin view)
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */
 
?>

<div class="bt-link-back">
	<a href="<?php echo site_url('bridge-tournament/upcoming-tournaments') ?>"
		><?php echo _e('back to tournament list', 'bt') ?></a>
</div>

<h1 class="bt-title"> <?php _e('Registered Players for:', 'bt') ?> <?php echo date_i18n(__('F j, Y', 'bt' ) , strtotime($date_from_href)) ?></h1>

<div class="large-table-page">
<form name="form-suppress-players" method="post" action="">
	<table class="bt-large-table">
		<thead>
			<tr>
				<?php if ( bt_check_admin() ):?> 			
				<th>
					<?php	_e('Delete','bt') ?>
				</th>
           <th>
					<?php _e('Reg ID','bt') ?>
				</th>						
				<?php endif; ?>	
				<?php if ( $trn->get_tr_type() == "Mitchell" ) : ?>														
				<th> 
					<?php _e('Line', 'bt') ?>	
				</th>
				<?php endif; ?>
            <th>
					<?php _e('Pair','bt') ?>
				</th>	
				<th>
					<?php	_e('First Player','bt') ?>
				</th>
            <th>
					<?php	_e('Second player','bt') ?>
				</th>
        </tr>
		</thead>
		<tbody>
		<?php $ind = 0; ?>
		<?php		foreach ( $trn_result as $pair ): ?>
			<tr>

				<?php if ( bt_check_admin() ):?>
					<td align="center">
						<input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $pair['reg_id']; ?>"></td>
					<td><?php echo $pair['reg_id'] ?></td>
				
						<?php if ( $trn->get_tr_type() == "Mitchell" ) : ?>			
							<td><input type="text" name="orientation[<?php echo $ind; ?>]" size="2" value ="<?php echo $pair['orientation']?>"/></td>				
						<?php endif; ?>

					<td><input type="text" name="pair_upd[<?php echo $ind; ?>]" size="2" value ="<?php echo $pair['pair_id']?>"/></td>
				<?php else: ?>	
				
					<?php if ( $trn->get_tr_type() == "Mitchell" ) : ?>										
						<td align="center"><?php echo $pair['orientation'] ?></td>			
					<?php endif ; ?>					
					<td align="center"><?php echo $pair['pair_id'] ?></td>				
				<?php endif; ?>

            <td><?php echo $pair['player1'] ?></td>
               
				<td><?php echo $pair['player2'] ?></td>
			</tr>
			<?php $ind++; ?>
		<?php		endforeach; ?>

		<?php if ( bt_check_admin() ):?>
		<td colspan="6" align="center" ><input name="Apply" type="submit" id="Apply" value="<?php _e('Apply changes','bt') ?>"></td>
		<?php endif; ?>
		
		</tbody>
	</table>
</form>

</div>
