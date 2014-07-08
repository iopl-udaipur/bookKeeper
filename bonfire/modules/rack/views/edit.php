<div class="row">
	<div class="col-lg-12">
		<h1> Edit Rack</h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('/rack')?>"> <i class="fa fa-dashboard"></i> Manage Rack</a>
			</li>
			<li class="active">
				<i class="fa fa-user"></i>
				Edit Rack
			</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<?php if (validation_errors()) : ?>
			<div class="alert alert-block alert-error fade in ">
			  <a class="close" data-dismiss="alert">&times;</a>
			  <h4 class="alert-heading">Please fix the following errors :</h4>
			 <?php echo validation_errors(); ?>
			</div>
		<?php endif; ?>
		<?php // Change the css classes to suit your needs
			if( isset($rack) ) {
			    $rack = (array)$rack;
			}
			$id = isset($rack['id']) ? $rack['id'] : '';
		?>
		<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
    		<fieldset>
    			<input type="hidden" name="id" value="<?php echo $rack['id'];?>" >
       			<div class="form-group <?php echo form_error('rack_name') ? 'error' : ''; ?>">
            		<?php echo form_label('Name'. lang('bf_form_label_required'), 'rack_name', array('class' => "control-label") ); ?>
        			<input class="form-control" readonly id="rack_name" type="text" maxlength="20" value="<?php echo set_value('rack_name', isset($rack['name']) ? $rack['name'] : ''); ?>"  />
    				<span class="help-inline"><?php echo form_error('rack_name'); ?></span>
        		</div>
		        <div class="form-group <?php echo form_error('rack_description') ? 'error' : ''; ?>">
		            <?php echo form_label('Description', 'rack_description', array('class' => "control-label") ); ?>
		            <input class="form-control"  id="rack_description" type="text" name="rack_description" maxlength="150" value="<?php echo set_value('rack_description', isset($rack['description']) ? $rack['description'] : ''); ?>"  />
    				<span class="help-inline"><?php echo form_error('rack_description'); ?></span>
		        </div>

	        	<div class="form-actions">
	            	<br/>
	            	<input type="submit" name="save" class="btn btn-primary" value="Edit Rack" />
		            or <?php echo anchor('/rack', lang('rack_cancel'), 'class="btn btn-warning"'); ?>

	        	</div>
	   	 	</fieldset>
    	<?php echo form_close(); ?>
	</div>
</div>
