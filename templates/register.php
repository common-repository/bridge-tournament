<?php
/**
 * Player Registration form	
 *
 * @license GPLv3
 * @version 0.1
 * @author Frederic Hantrais
 */

?>
<?php wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
?>

<div class="bt-link-back">
	<a href="<?php echo site_url('bridge-tournament/upcoming-tournaments') ?>"
		><?php echo _e('back to tournament list', 'bt') ?></a>
</div>


<script type="text/javascript">
jQuery(document).ready(function($) {
var availableNames = <?php echo json_encode($pl_names);?>;
var availableDates = <?php echo json_encode($av_dates);?>;
            $( "#tr_date" ).autocomplete({
                source: availableDates
            });
            $( "#pl1_name" ).autocomplete({
                source: availableNames
            });
            $( "#pl2_name" ).autocomplete({
                source: availableNames
            });
    });
</script>

<h1 class="bt-title"><?php _e('Register for the Tournament: ', 'bt') ?> 
							<?php echo date_i18n(__('F j, Y', 'bt' ) , strtotime($date_from_href)) ?> </h1>

<form name="registration" id="" method="post" enctype="multipart/form-data" action="">
		<div >
        <p>
				<label for="Date">
<?php				_e('Tournament date:','bt') ?>
				</label>
				<input type="text" class="custom_date" name="tr_date" id="tr_date" value="<?php echo $date_from_href; ?>">
			</p>
        <p>
				<label for="First Player Name">
<?php				_e('First Player Name: ','bt') ?>
				</label>
				<input type="text" class="" name="pl1_name" id="pl1_name" />
			</p>
            <p>
				<label for="Second Player Name">
<?php				_e('Second Player Name: ','bt') ?>
				</label>
				<input type="text" class="" name="pl2_name" id="pl2_name" />
			</p>
			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Confirm Registration','bt') ?>" name="reg_confirmation">
			</p>
		</div>
	</form>