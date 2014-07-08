<h2 class="form-signin-heading text-center">Sign in with your Account</h2>
<div class="card card-signin">

	<?php
		echo Template::message();
		if (auth_errors() || validation_errors()) :
	?>
		<div class="alert alert-dismissable alert-danger">
			<a data-dismiss="alert" class="close">&times;</a><?php echo auth_errors() . validation_errors(); ?>
		</div>
	<?php endif; ?>
	<img class="img-circle profile-img" src="<?php echo Template::theme_url();?>img/avatar.png" alt="">
	<?php echo form_open('login', array('class' => "form-signin", 'autocomplete' => 'off')); ?>
		<input class="form-control"  autofocus type="text" name="login" id="login_value" value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucwords($this->settings_lib->item('auth.login_type')) ?>" />
		<input class="form-control"  type="password" name="password" id="password" value="" tabindex="2" placeholder="<?php echo lang('bf_password'); ?>" />
		<input class="btn btn-lg btn-primary btn-block" type="submit" name="submit" id="submit" value="Let Me In" tabindex="5" />

		<div>
			<?php echo anchor('/forgot_password', lang('us_forgot_your_password'),'class="pull-right"'); ?>
			<?php if ($this->settings_lib->item('auth.allow_remember')) : ?>
				<label class="checkbox">
					<input type="checkbox" name="remember_me" id="remember_me" value="1" tabindex="3" />
					Stay signed in
				</label>
			<?php endif;?>
		</div>
	<?php echo form_close(); ?>
</div>
