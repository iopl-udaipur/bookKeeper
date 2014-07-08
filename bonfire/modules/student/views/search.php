<div class="row">
	<h1> Search Student</h1>
	<ol class="breadcrumb">
		<li  class="active">
			<i class="fa fa-dashboard"></i> Search Student
		</li>
	</ol>
</div>
<div class="row">
	<div class="col-lg-6">
		<?php echo form_open($this->uri->uri_string()); ?>
		<div style="width: 300px;" class="pull-left">
			<div class="form-group">
				<label for="name">Name</label>
				<input class="form-control" placeholder="Student Name" id="name" type="text" name="name" maxlength="100" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>"  />
			</div>
			<div class="form-group">
				<label for="course">Course</label>
				<input class="form-control" placeholder="Course Name" id="course" type="text" name="course" maxlength="100" value="<?php echo isset($_POST['course']) ? $_POST['course'] : ''; ?>"  />
			</div>
		</div>
		<div style="width: 300px; margin-left: 20px;" class="pull-left">
			<div class="form-group">
				<label for="batch">Batch</label>
				<input class="form-control" placeholder="Batch Name" id="batch" type="text" name="batch" maxlength="100" value="<?php echo isset($_POST['batch']) ? $_POST['batch'] : ''; ?>"  />
			</div>
			<div class="form-group">
				<label for="phone">Phone</label>
				<input class="form-control" placeholder="Phone" id="phone" type="text" name="phone" maxlength="100" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"  />
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="form-actions">
            <input type="submit" name="search" class="btn btn-primary" value="Search" />
        </div>
		<?php echo form_close(); ?>
		<h1>Search result</h1>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>Course</th>
					<th>Batch</th>
				</tr>
			</thead>
			<?php if (isset($records) && is_array($records) && count($records)) : ?>
			<tfoot>
				<?php if ($this->auth->has_permission('Student.Content.Delete')) : ?>
				<tr>
					<td colspan="9">
						<?php echo lang('bf_with_selected') ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete') ?>" onclick="return confirm('<?php echo lang('student_delete_confirm'); ?>')">
					</td>
				</tr>
				<?php endif;?>
			</tfoot>
			<?php endif; ?>
			<tbody>
			<?php if ($records) : ?>
			<?php foreach ($records as $record) : ?>
				<tr>
				<td><?php echo anchor(site_url('/student/edit/'. $record->id), '<i class="icon-pencil">&nbsp;</i>' .  $record->name) ?></td>
				<td><?php echo $record->address?></td>
				<td><?php echo $record->course?></td>
				<td><?php echo $record->batch?></td>
				</tr>
			<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="9">No records found that match your selection.</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>