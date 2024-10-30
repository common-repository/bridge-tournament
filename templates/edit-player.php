<?php
/**
 * Player creation form	
 *
 * @license GPLv3
 * @version 0.1
 * @author Frederic Hantrais
 */

global $title;
?>

<div class="bt-link-back">
	<a href="<?php echo site_url('bridge-tournament/bt-players') ?>"
		><?php echo _e('back to player list', 'bt') ?></a>
</div>

<h1 class="bt-title"><?php _e('Update Player Information', 'bt') ?></h1>

<div class="wrap bt_player_add">

	<form action="" name="" id="" method="post" enctype="multipart/form-data">
		<div >
			<p>
				<label for="name">
<?php				_e('Player last name','bt') ?>:
				</label>
				<input type="text" name="pl_name" value="<?php echo $this->get_name(); ?>" />
			</p>

			<p>
				<label for="firstname">
<?php				_e('First Name','bt') ?>:
				</label>
				<input type="text" name="pl_firstname" value="<?php echo $this->get_firstname(); ?>"/>
			</p>
			<p>
				<label for="email">
<?php				_e('Email Address','bt') ?>:
				</label>
				<input type="text" name="pl_email" value="<?php echo $this->get_email(); ?>" />
			</p>
			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Update','bt') ?>" name="save">
			</p>
		</div>
	</form>
</div>
