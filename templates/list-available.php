<?php
/**
 * Display players on the partner list
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 **/


?>
<?php wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
?>

<script type="text/javascript">
jQuery(document).ready(function($) {
var availableNames = <?php echo json_encode($pl_names);?>;
            $( "#pl_name" ).autocomplete({
                source: availableNames
            });
    });
</script>

<div class="bt-link-back">
	<a href="<?php echo site_url('bridge-tournament/upcoming-tournaments') ?>"
		><?php echo _e('back to tournament list', 'bt') ?></a>
</div>

<h1 class="bt-title"><?php _e('Available partners for ', 'bt') ?> 
							<?php echo date_i18n(__('F j, Y', 'bt' ) , strtotime($trndate)); ?> </h1>
<h2> </h2>

<?php if ( empty( $exception_msg )) : ?>

	<div class="registered_players">
		<table class="bt-table">
			<thead>
				<tr>
					<th>
						<?php	_e('Player Name','bt') ?>
					</th>
						<th>
						<?php	_e('Contact','bt') ?>
					</th>			
	        </tr>
			</thead>
			<tbody>
				<?php	foreach ( $trn_avail_player as $avail_player ): ?>
			<tr>
				<td><?php echo $avail_player['fullname'] ?></td>
				<td><a href=<?php echo add_query_arg( 'contact_id', $avail_player['player_id'] , site_url('./bridge-tournament/bt-contact-request')) ?>>Contact</a>
				</td>
			</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
<?php else : ?>
	<div class="bt-messages"><?php echo $exception_msg ?> </div>
<?php endif; ?>
<br>
<p><?php _e('If you are looking for a partner for this tournament, please fill the following form and your name will be added to the list', 'bt') ?><p>
</p>	



<form name="bt-available-form" id="" method="post" enctype="multipart/form-data" action="">
		<div >
        <p>
				<label for="Your Name">
<?php				_e('Your Name','bt') ?>:
				</label>
				<input type="text" class="" name="pl_name" id="pl_name" />
			</p>
			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Confirm Registration','bt') ?>" name="confirmation">
			</p>
		</div>
	</form>

	
</div>
