<div class="row">
	<div class="col-lg-12">
		<h1> Add Book to Shelf <small>Enter Details</small></h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('/books')?>"> <i class="fa fa-dashboard"></i> Manage Books</a>
			</li>
			<li>
				<a href="<?php echo site_url('/books/book_detail/'.$book->id)?>"> <i class="fa fa-dashboard"></i> Books Detail</a>
			</li>
			<li class="active">
				<i class="fa fa-user"></i>
				Add Book to Shelf
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
		
		<?php echo form_open($this -> uri -> uri_string(), 'class="form-horizontal"'); ?>
			<fieldset>
				<?php echo form_label('Book' . lang('bf_form_label_required'), 'shelf_detail_book_id', array('class' => "control-label")); ?>
				<input readonly class="form-control" id="shelf_detail_book_id" name="shelf_detail_book_id" type="text" maxlength="150" value="<?php echo $book->title; ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('shelf_detail_book_id'); ?></span>
					
				<?php echo form_label('Book\'s UID' . lang('bf_form_label_required'), 'shelf_detail_book_copy_id', array('class' => "control-label")); ?>
				<input readonly class="form-control" id="shelf_detail_book_copy_id" name="shelf_detail_book_copy_id" type="text" maxlength="150" value="<?php echo $book_copy->id; ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('shelf_detail_book_copy_id'); ?></span>
				
				<?php echo form_label('Rack' . lang('bf_form_label_required'), 'shelf_detail_rack_id', array('class' => "control-label")); ?>
				<?php echo form_dropdown('shelf_detail_rack_id', $rack_dropdown_values, '','','class="form-control" style="width: 530px;" id="shelf_detail_rack_id"');?>
				<span class="help-inline"><?php echo form_error('shelf_detail_rack_id'); ?></span>

				<?php echo form_label('Shelf' . lang('bf_form_label_required'), 'shelf_detail_shelf_id', array('class' => "control-label")); ?>
        		<?php echo form_dropdown('shelf_detail_shelf_id', array(''=>'Select Shelf'), '','','id="shelf_detail_shelf_id" class="form-control" style="width: 530px;"');?>

		        <div class="form-actions">
					<br/>
					<input type="submit" name="save" class="btn btn-primary" value="Add" />
					or <?php echo anchor('/books/book_detail/'.$book->id, lang('rack_cancel'), 'class="btn btn-warning"'); ?>
		        </div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
</div>

<?php Assets::add_js("
	jQuery('#shelf_detail_rack_id').change(function(){
			var shelf_arr = ".json_encode($shelf_arr).";
			var shelf_dropdown = '<option value=\"\">Select Shelf</option>';
			var rack_id = jQuery(this).val();
			if (rack_id != '')
			{
				jQuery.each(shelf_arr[rack_id], function(i, item) {
				    shelf_dropdown = shelf_dropdown + '<option value = \"' +item.id+ '\">' +item.shelf_number+ '</option>';
				});
			}
			jQuery('#shelf_detail_shelf_id').html(shelf_dropdown);
	});
",'inline');?>