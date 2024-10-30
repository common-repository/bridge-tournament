<?php
/**
 * Display tournament result for update
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

<h1 class="bt-title"><?php _e('Results Update', 'bt' )?> - <?php echo date_i18n(__('F j, Y', 'bt' ) , strtotime($this->get_tr_date())) ?></h1>

<div class="results_update">
	<form action="" name="update_result" id="" method="post" enctype="multipart/form-data">
	<table class="wp-t-table widefat fixed posts" cellspacing="0">
		<thead>
			<tr>
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
<?php $ind =0; ?>
<?php		foreach ( $trn_data as $pair ): ?>
			<tr>
                <td><input type="text" size = "3" name="pair_upd[<?php echo $ind; ?>]" id=""  class=""  value ="<?php echo $pair['pair_id']?>"/></td>
                <td><?php echo $pair['player1'] ?></td>
				<td><?php echo $pair['player2'] ?></td>
				<td><input type="text" size="2" name="score_upd[<?php echo $ind; ?>]" id=""  class=""  value ="<?php echo $pair['score']?>"/></td>
				<td><input type="text" size="5" name="percent_upd[<?php echo $ind; ?>]" id=""  class=""  value ="<?php echo $pair['percent']?>"/></td>
			</tr>
            <?php $ind++; ?>
<?php		endforeach; ?>
		</tbody>
	</table>
    			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Save') ?>" name="bt_upd_save">
			</p>
    </form>
</div>
