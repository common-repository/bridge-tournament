<?php
/**
 * Tournament creation form	
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


<script type="text/javascript">
jQuery(document).ready(function($) {
$('.custom_date').datepicker({
dateFormat : 'yy-mm-dd'
});
});
</script>

<h1 class="bt-title"><?php _e('Create Tournament', 'bt') ?></h1>
 
<div class="wrap tournament_add">

	<form name="tournament_add" id="" method="post" enctype="multipart/form-data" action="">
		<div >
			<p>
				<label for="Date">
<?php				_e('Tournament date*','bt') ?>:
				</label>
				<input type="text" class="custom_date" name="tr_date" id="tr_date" value="<?php echo $this->get_tr_date() ?> "/>
			</p>
			<p>
				<label for="Location">
<?php				_e('Location*','bt') ?>:
				</label>
				<input type="text" name="tr_location" id="tr_location"  class="" value="<?php $value = $this->get_location() ; if (!empty( $value )){echo $value;} else { echo get_option('bt_default_location') ; } ?>" />
			</p>
			<p>
			<label for="Type">
<?php				_e('type','bt') ?>:
				</label>
					<select name="tr_type">
						<option value="Mitchell">Mitchell</option>
						<option value="Howell">Howell</option>
						<option value="dulicate">Duplicate</option>
						<option value="belote">Belote</option>
					</select>
			</p>			
			<p>
				<label for="Comment">
<?php				_e('Comment','bt') ?>:
				</label>
				<textarea name="tr_comment" id="tr_comment"  cols="50" rows="5" ><?php echo $this->get_comment() ?></textarea>
			</p>
			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Confirm creation / update','bt') ?>" name="confirmation">
			</p>
		</div>
	</form>
</div>
