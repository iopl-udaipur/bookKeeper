<div style="display: none;">
	<div id="shelf_number_field">
		<div style="padding-bottom: 7px;">
			<input class="form-control" placeholder="Shelf Number" id="shelf_shelf_number" type="text" name="shelf_shelf_number[]" maxlength="150" value="" style="width: 530px;"/>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<h1> Add Shelves </h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('/rack')?>"> <i class="fa fa-dashboard"></i> Manage Rack</a>
			</li>
			<li class="active">
				<i class="fa fa-user"></i>
				Add Rack
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php if (validation_errors()) : ?>
			<div class="alert alert-dismissable alert-danger">
		  		<a class="close" data-dismiss="alert">&times;</a>
		  		<h4 class="alert-heading">Please fix the following errors :</h4>
		 		<?php echo validation_errors(); ?>
			</div>
		<?php endif; ?>
		<?php Template::message();?>
		
		<?php echo form_open($this -> uri -> uri_string(), 'class="form-horizontal"'); ?>
			<fieldset>
			
        		<?php echo form_label('Rack', 'rack_name', array('class' => "control-label")); ?>
        		<input type="hidden" name="rack_id" value="<?php echo $rack->id;?>">
				<input readonly class="form-control" name="rack_name" id="rack_name" type="text" maxlength="150" value="<?php echo $rack->name ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('rack_name'); ?></span>
				
				<?php echo form_label('Number of shelves' . lang('bf_form_label_required'), 'shelf_num_of_shelf', array('class' => "control-label")); ?>
	            <input class="form-control" placeholder="Enter number of shelves" name="shelf_num_of_shelf" id="shelf_num_of_shelf" type="text" maxlength="150" value="<?php echo set_value('shelf_num_of_shelf', isset($_POST['shelf_num_of_shelf']) ? $_POST['shelf_num_of_shelf'] : ''); ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('shelf_num_of_shelf'); ?></span>
				
				<div id="shelf_num_div"></div>

		        <div class="form-actions">
		            <br/>
		            <input type="submit" name="save" class="btn btn-primary" value="Add Rack" />
		            or <?php echo anchor('/rack', lang('rack_cancel'), 'class="btn btn-warning"'); ?>
		        </div>
		<?php echo form_close(); ?>
	</div>
</div>

<?php Assets::add_js("

	jQuery('#shelf_num_of_shelf').keyup(function(){
		var num = jQuery(this).val();
		var uid_fields = '';
		
		jQuery('#shelf_num_div').empty();
		
		if(/^[0-9]+$/.test(num))
		{
			jQuery('#shelf_num_div').html(\"<label class='control-label'>Shelf Number<span class='required'>*</span></label>\");	
			var i;
			for (i=0;i<num;i++)
			{ 
				jQuery('#shelf_number_field div').clone().appendTo('#shelf_num_div');
			}
			var last_shelf_number = ".$max_shelf_number.";
			jQuery('[name=\"shelf_shelf_number[]\"]').each( function(i, obj) {
				jQuery(obj).val(last_shelf_number + i);
				i++;
			});
		}
		else if(num!='')
		{
			alert('Enter valid number');
			jQuery(this).val('');
		}
	});
",'inline');

if (isset($_POST['shelf_num_of_shelf']))
{
	Assets::add_js("
		jQuery('#shelf_num_of_shelf').trigger('keyup');
	",'inline');
}
	?>