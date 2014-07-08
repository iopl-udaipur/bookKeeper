<div class="row">
	<div class="col-lg-12">
		<h1> Edit Student <small>Edit Student Data</small></h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('/student')?>"> <i class="fa fa-dashboard"></i> Manage student</a>
			</li>
			<li class="active">
				<i class="fa fa-user"></i>
				Edit student
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
		<?php
			if( isset($student) ) {
			    $student = (array)$student;
			}
			$id = isset($student['id']) ? $student['id'] : '';
		?>
		<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
    		<fieldset>
       			<div class="form-group <?php echo form_error('student_name') ? 'error' : ''; ?>">
            		<?php echo form_label('Name'. lang('bf_form_label_required'), 'student_name', array('class' => "control-label") ); ?>
        			<input class="form-control"  id="student_name" type="text" name="student_name" maxlength="100" value="<?php echo set_value('student_name', isset($student['name']) ? $student['name'] : ''); ?>"  />
    				<span class="help-inline"><?php echo form_error('student_name'); ?></span>
        		</div>
		        <div class="form-group <?php echo form_error('student_address') ? 'error' : ''; ?>">
		            <?php echo form_label('Address'. lang('bf_form_label_required'), 'student_address', array('class' => "control-label") ); ?>
		            <?php echo form_textarea( array( 'class'=>'form-control','name' => 'student_address', 'id' => 'student_address', 'rows' => '5', 'cols' => '80', 'value' => set_value('address', isset($student['address']) ? $student['address'] : '') ) )?>
		            <span class="help-inline"><?php echo form_error('student_address'); ?></span>
		        </div>

		        <div class="form-group <?php echo form_error('student_course') ? 'error' : ''; ?>">
		            <?php echo form_label('Course'. lang('bf_form_label_required'), 'student_course', array('class' => "control-label") ); ?>
				        <input class="form-control"  id="student_course" type="text" name="student_course" maxlength="50" value="<?php echo set_value('course', isset($student['course']) ? $student['course'] : ''); ?>"  />
				        <span class="help-inline"><?php echo form_error('student_course'); ?></span>
        		</div>

        		<div class="form-group <?php echo form_error('student_batch') ? 'error' : ''; ?>">
            		<?php echo form_label('Batch', 'student_batch', array('class' => "control-label") ); ?>
			        <input class="form-control"  id="student_batch" type="text" name="student_batch" maxlength="50" value="<?php echo set_value('student_batch', isset($student['batch']) ? $student['batch'] : ''); ?>"  />
			        <span class="help-inline"><?php echo form_error('student_batch'); ?></span>
		        </div>

	        	<div class="form-actions">
	            	<br/>
	            	<input type="submit" name="save" class="btn btn-primary" value="Edit Student" />
		            or <?php echo anchor('/student', lang('student_cancel'), 'class="btn btn-warning"'); ?>
		            or <?php echo anchor('/student/delete/'.$id, '<i class="fa fa-trash-o">&nbsp;</i>&nbsp;'.lang('student_delete_record'), 'class="btn btn-danger" onclick="return confirm(\''.lang('student_delete_confirm').'\')"'); ?>
	        	</div>
	   	 	</fieldset>
    	<?php echo form_close(); ?>
	</div>
</div>
