<div class="row">
	<div class="col-lg-12">
		<h1> Issue Book <small>Edit Book Issued</small></h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('/issue_book')?>"> <i class="fa fa-dashboard"></i> Manage Books Issued</a>
			</li>
			<li class="active">
				<i class="fa fa-user"></i>
				Edit Books Issued
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
			if( isset($issue_book) ) {
			    $issue_book = (array)$issue_book;
			}
			$id = isset($issue_book['id']) ? $issue_book['id'] : '';
		?>
		<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
    		<fieldset>
    			<?php echo form_label('Book' . lang('bf_form_label_required'), 'issue_book_book_id', array('class' => "control-label")); ?>
        		<?php echo form_dropdown('issue_book_book_id', $books_dropdown_values,  set_value('issue_book_book_id', isset($issue_book['book_id']) ? $issue_book['book_id'] : ''),'','id="issue_book_book_id" class="form-control" style="width: 530px;"');?>
				<span class="help-inline"><?php echo form_error('issue_book_book_id'); ?></span>
					
				<?php echo form_label('Book\'s UID' . lang('bf_form_label_required'), 'issue_book_book_copies_id', array('class' => "control-label")); ?>
        		<?php echo form_dropdown('issue_book_book_copies_id', $book_copies_dropdown_values, set_value('issue_book_book_copies_id', isset($issue_book['book_copy_id']) ? $issue_book['book_copy_id'] : ''),'','id="issue_book_book_copies_id" class="form-control" style="width: 530px;"');?>
				<span class="help-inline"><?php echo form_error('issue_book_book_copies_id'); ?></span>
				
				<?php echo form_label('Student' . lang('bf_form_label_required'), 'issue_book_student_id', array('class' => "control-label")); ?>
        		<?php echo form_dropdown('issue_book_student_id', $students_dropdown_values, set_value('issue_book_student_id', isset($issue_book['student_id']) ? $issue_book['student_id'] : ''),'','class="form-control" style="width: 530px;"');?>
				<span class="help-inline"><?php echo form_error('issue_book_student_id'); ?></span>

				<?php echo form_label('Issue Date', 'issue_book_issue_date', array('class' => "control-label")); ?>
				<input class="form-control datepicker" placeholder="Issue Date" id="issue_book_issue_date" type="text" name="issue_book_issue_date" maxlength="150" value="<?php echo set_value('issue_book_issue_date', isset($issue_book['issue_date']) ? $issue_book['issue_date'] : ''); ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('issue_book_issue_date'); ?></span>
		        
				<?php echo form_label('Return Date', 'issue_book_return_date', array('class' => "control-label")); ?>
				<input class="form-control datepicker" placeholder="Return Date" id="issue_book_return_date" type="text" name="issue_book_return_date" maxlength="150" value="<?php echo set_value('issue_book_return_date', isset($issue_book['return_date']) ? $issue_book['return_date'] : ''); ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('issue_book_return_date'); ?></span>

	        	<div class="form-actions">
	            	<br/>
	            	<input type="submit" name="save" class="btn btn-primary" value="Edit" />
		            or <?php echo anchor('/issue_book', lang('issue_book_cancel'), 'class="btn btn-warning"'); ?>
		            or <button type="submit" name="delete" class="btn btn-danger" id="delete-me" onclick="return confirm('<?php echo lang('issue_book_delete_confirm'); ?>')">
		            <i class="fa fa-trash-o">&nbsp;</i>&nbsp;<?php echo lang('issue_book_delete_record'); ?>
		            </button>
	        	</div>
	   	 	</fieldset>
    	<?php echo form_close(); ?>
	</div>
</div>

<?php Assets::add_js("
	jQuery('#issue_book_book_id').change(function(){
		jQuery.get( '".site_url('/books/get_copies_by_book_id_for_edit')."' +'/'+ jQuery(this).val()+'/".$issue_book['book_copy_id']."', function( data ) {
			var book_copies = JSON.parse(data);
			var book_copies_dropdown = '<option value=\"\">Select UID</option>';
			jQuery.each(book_copies, function(i, item) {
			    book_copies_dropdown = book_copies_dropdown + '<option value = \"' +item.id+ '\">' +item.id+ '</option>';
			});
			jQuery('#issue_book_book_copies_id').html(book_copies_dropdown);
		});
	});
",'inline');?>