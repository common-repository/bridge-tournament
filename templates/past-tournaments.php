<?php
/**
 * Display past tournaments list
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

?>

<h1 class="bt-title"><?php _e( 'Past Tournaments', 'bt' ) ?></h1>

<div class="wrap large-table-page">
	<table class="bt-table" >
		<thead>
			<tr>
				<th>
<?php				_e('Tournament Date','bt') ?>
				</th>
	<th>
<?php				_e('Results','bt') ?>
				</th>
<?php if ( bt_check_admin() ): ?>
        <th>
        <?php				_e('Update', 'bt') ?>
				</th>
<?php endif; ?>                
        </tr>
		</thead>
		<tbody>
			<?php		foreach ( $past_tournaments_list as $tournament ): ?>
			<tr>
				<td><?php echo $tournament->get_tr_date() ?></td>
                <td><a href="<?php echo add_query_arg( 'trn_date', $tournament->get_tr_date() , 
                														site_url('bridge-tournament/bt-results/')) ?>">
					 <?php _e('Results','bt') ?></a></td> 
                <?php if ( bt_check_admin() ): ?>
                <td><a href="<?php echo add_query_arg( 'trn_date', $tournament->get_tr_date() , 
                														site_url('bridge-tournament/bt-update-results/')) ?>">
					<?php _e('Update results','bt') ?></a></td>
                <?php endif; ?>
			</tr>
<?php		endforeach; ?>
		</tbody>
	</table>
	
<?php if ( ! $bt_full_list ): ?>
	<a href="<?php echo site_url('bridge-tournament/bt-past-full-tournaments') ?>"
	><?php echo _e('View full list', 'bt') ?></a>
	<?php else: ?>
		<a href="<?php echo site_url('bridge-tournament/bt-past-tournaments') ?>"
		><?php echo _e('Short list', 'bt') ?></a>
<?php endif; ?>
</div>
