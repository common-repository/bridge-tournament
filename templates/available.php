<?php
/**
 * Player availability	
 *
 * @license GPLv3
 * @version 0.1
 * @author Frederic Hantrais
 */


?>
<?php wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
?>

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
</script>

<form name="single-registration" id="" method="post" enctype="multipart/form-data" action="">
		<div >
        <p>
				<label for="Date">
<?php				_e('Tournament date (yyyy-mm-dd)','bt') ?>:
				</label>
				<input type="text" class="custom_date" name="tr_date" id="tr_date" value="<?php echo $date_from_href; ?>">
			</p>
        <p>
				<label for="Player Name">
<?php				_e('Player Name','bt') ?>:
				</label>
				<input type="text" class="" name="pl1_name" id="pl1_name" />
			</p>
		</div>
	</form>