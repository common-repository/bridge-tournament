<?php
/**
 * Request form for new players
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

?>

<h1 class="bt-title"><?php _e('Join us !', 'bt') ?></h1>
<div>

<p><?php echo get_option( 'bt_join_message') ?></p>

	<form action="" name="bt-player-request"  method="post" enctype="multipart/form-data">
		<div >
			<p>
				<label for="name">
<?php				_e('Last name','bt') ?>:
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
			
			<?php bt_display_comment( sprintf ( __('Only website administrators will have access to your email address.', 'bt' ))); ?>

			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Send','bt') ?>" name="save">
			</p>
		</div>
	</form>
</div>