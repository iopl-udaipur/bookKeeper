<div class="row">
	<div class="col-lg-12">
		<h1> Issue Book <small>Enter Details</small></h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('/books')?>"> <i class="fa fa-dashboard"></i> Manage Books</a>
			</li>
			<li>
				<a href="<?php echo site_url('/books/book_detail'.'/'.$book_id)?>"> <i class="fa fa-dashboard"></i> Books Detail</a>
			</li>
			<li class="active">
				<i class="fa fa-user"></i>
				Issue Book
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
				<?php echo form_label('Book' . lang('bf_form_label_required'), 'issue_book_book_id', array('class' => "control-label")); ?>
				<input type="hidden" name="issue_book_book_id" value="<?php echo $book->id;?>">
				<input readonly class="form-control" id="issue_book_book_id" type="text" maxlength="150" value="<?php echo $book->title; ?>" style="width: 530px;"/>
					
				<?php echo form_label('Book\'s UID' . lang('bf_form_label_required'), 'issue_book_book_copy_id', array('class' => "control-label")); ?>
				<input type="hidden" name="issue_book_book_copy_id" value="<?php echo $book_copy->id;?>">
				<input readonly class="form-control" id="issue_book_book_copy_id" type="text" maxlength="150" value="<?php echo $book_copy->id; ?>" style="width: 530px;"/>
				
				<?php echo form_label('Student' . lang('bf_form_label_required'), 'issue_book_student_id', array('class' => "control-label")); ?>
				<?php echo form_dropdown('issue_book_student_id', $students_dropdown_values, '','','class="form-control" style="width: 530px;"');?>
				<span class="help-inline"><?php echo form_error('issue_book_student_id'); ?></span>

				<?php echo form_label('Issue Date', 'issue_book_issue_date', array('class' => "control-label")); ?>
				<input class="form-control datepicker" placeholder="Issue Date" id="issue_book_issue_date" type="text" name="issue_book_issue_date" maxlength="150" value="<?php echo set_value('issue_book_issue_date', isset($issue_book['issue_date']) ? $issue_book['issue_date'] : ''); ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('issue_book_issue_date'); ?></span>
    			
				<?php echo form_label('Return Date', 'issue_book_return_date', array('class' => "control-label")); ?>
				<input class="form-control datepicker" placeholder="Return Date" id="issue_book_return_date" type="text" name="issue_book_return_date" maxlength="150" value="<?php echo set_value('v', isset($issue_book['return_date']) ? $issue_book['return_date'] : ''); ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('issue_book_return_date'); ?></span>

		        <div class="form-actions">
					<br/>
					<input type="submit" name="save" class="btn btn-primary" value="Issue" />
					or <?php echo anchor('/issue_book', lang('issue_book_cancel'), 'class="btn btn-warning"'); ?>
		        </div>
		<?php echo form_close(); ?>
	</div>
</div>
