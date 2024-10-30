<?php
/**
 * Display tournament results
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

?>

<div class="bt-link-back">
	<a href="<?php echo site_url('bridge-tournament/bt-past-tournaments') ?>"
		><?php echo _e('back to tournament list', 'bt') ?></a>
</div>


<h1 class="bt-title"><?php _e('Results', 'bt' )?> - <?php echo date_i18n(__('F j, Y', 'bt' ) , strtotime($this->get_tr_date())) ?></h1>

<div class="tournaments_results">
	<table class="wp-t-table widefat fixed posts" cellspacing="0">
		<thead>
			<tr>
            <th>
					<?php	_e('Pair','bt') ?>
				</th>
				<th>
					<?php _e('First Player','bt') ?>
				</th>
				<th>
					<?php	_e('Second player','bt') ?>
				</th>
				<th>
					<?php	_e('score','bt') ?>
				</th>
				<th>
					<?php _e('Percent','bt') ?>
				</th>
        </tr>
		</thead>
		<tbody>
<?php		foreach ( $trn_data as $pair ): ?>
			<tr>
				<td><?php echo $pair['pair_id'] ?></td>
                <td><?php echo $pair['player1'] ?></td>
				<td><?php echo $pair['player2'] ?></td>
				<td><?php echo $pair['score'] ?></td>
                <td><?php echo $pair['percent'] ?></td>
			</tr>
<?php		endforeach; ?>
		</tbody>
	</table>
</div>
