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
<h1 class="bt-title"><?php _e('Register New Player', 'bt') ?></h1>
<div class="wrap bt_player_add">


	<form action="" name="" id="" method="post" enctype="multipart/form-data">
		<div >
			<p>
				<label for="name">
<?php				_e('Player last name','bt') ?>:
				</label>
				<input type="text" name="pl_name" id="name"  class="" />
			</p>

			<p>
				<label for="firstname">
<?php				_e('First Name','bt') ?>:
				</label>
				<input type="text" name="firstname" id="firstname"  class="" />
			</p>

			<p>
				<label for="email">
<?php				_e('Email Address','bt') ?>:
				</label>
				<input type="text" name="email" id="email" />
			</p>
			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Save','bt') ?>" name="save">
			</p>
		</div>
	</form>
</div>
