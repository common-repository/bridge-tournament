<?php
/**
 * Display tournament information + modification options
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

?>

<div class="tournaments_results">
	<table class="wp-t-table widefat fixed posts" cellspacing="0">
		<thead>
			<tr>
				<th>
<?php					_e('Delete','bt') ?>
				</th>
            <th>
<?php				_e('Reg ID','bt') ?>
				</th>
            <th>
<?php				_e('Pair','bt') ?>
				</th>
				<th>
<?php				_e('First Player','bt') ?>
				</th>
                				<th>
<?php				_e('Second player','bt') ?>
				</th>
                				<th>
<?php				_e('score','bt') ?>
				</th>
	<th>
<?php				_e('Percent','bt') ?>
				</th>
        </tr>
		</thead>
		<tbody>
<?php		foreach ( $trn_result as $pair ): ?>
			<tr>
				<td align="center">
					<input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $pair['reg_id']; ?>"></td>
				<td><?php echo $pair['reg_id'] ?></td>				
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
