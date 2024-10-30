<?php
/**
 * Contact form for players
 *
 * @license GPLv3
 * @version 0.1	
 * @author Frederic Hantrais
 */

?>
<div class="bt-link-back">
	<a href="<?php echo site_url('bridge-tournament/bt-players') ?>" 
		><?php echo _e('back to player list', 'bt') ?>    </a>  &nbsp;&nbsp;&nbsp;&nbsp;     
	<a href="<?php echo site_url('bridge-tournament/upcoming-tournaments') ?>"
		><?php echo _e('back to tournament list', 'bt') ?></a>
</div>

<h1 class="bt-title"><?php _e('Player Contact Form','bt'); ?></h1>

<div class="wrap bt_player_add">

<p><?php printf ( __('Please send an email to %s %s' , 'bt' ), $contact->get_firstname(), $contact->get_name() ); ?> 

	<form action="" name="bt-contact-form" id="" method="post" enctype="multipart/form-data">
		<div >
			<p>
				<label for="name">
				<?php	_e('Your Name','bt') ?>:
				</label>
				<input type="text" name="sender_name" class="" />
			</p>
			<p>
				<label for="email">
				<?php _e('Email Address','bt') ?>:
				</label>
				<input type="text" name="sender_email" />
			</p>
			<p>
				<label for="message">
				<?php _e('Your Message','bt') ?>:
				</label>
				<textarea name="sender_message" class="bt-message-area"/></textarea>
			</p>
			
			<p class="submit">
				<input id="submit" class="button-primary" type="submit"
					   value="<?php _e('Send','bt') ?>" name="Contact_Send">
			</p>
		</div>
	</form>
</div>
