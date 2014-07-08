<h2 class="form-signin-heading text-center"><?php echo lang('us_reset_password'); ?></h2>
<div class="card card-signin">
	<?php
		if (auth_errors() || validation_errors()) :
	?>
		<div class="alert alert-dismissable alert-danger">
			<a data-dismiss="alert" class="close">&times;</a><?php echo auth_errors() . validation_errors(); ?>
		</div>
	<?php endif; ?>
	<img class="img-circle profile-img" src="<?php echo Template::theme_url();?>img/avatar.png" alt="">
	<?php echo form_open($this->uri->uri_string(), array('class' => "form-signin", 'autocomplete' => 'off')); ?>
		<input class="form-control"  autofocus  type="text" name="email" id="email" value="<?php echo set_value('email') ?>" />
		<input class="btn btn-lg btn-primary btn-block"  type="submit" name="submit" value="Send Password"  />
		<a href="<?php echo site_url();?>">Sign In</a>
	<?php echo form_close(); ?>
</div>
