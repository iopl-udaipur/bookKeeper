<div class="row">
	<h1> Student <small>list</small></h1>
	<ol class="breadcrumb">
		<li  class="active">
			<i class="fa fa-dashboard"></i> Manage Student
		</li>
	</ol>
</div>
<div class="row">
	<div class="table-responsive">
		<table class="table table-striped dataTablestable table-bordered table-hover dataTables no-footer">
			<thead>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>Course</th>
					<th>Batch</th>
					<th></th>
					<th></th>
				</tr>
			</thead>

			<tbody>
			<?php if (isset($records) && is_array($records) && count($records)) : ?>
			<?php foreach ($records as $record) : ?>
				<?php
					if (array_key_exists($record->id, $book_count)){
						$status_lbl = '<span class="label label-danger">Book Issued</span>';
						$style = ' style="color: red;"';
					}else{
						$status_lbl = '<span class="label label-success">No Issue</span>';
						$style = ' style="color: green;"';
					}
				?>
				<tr<?php echo $style;?>>
					<td>
						<?php echo $status_lbl;?>
					</td>
					<td><?php echo $record->name?></td>
					<td><?php echo $record->address?></td>
					<td><?php echo $record->course?></td>
					<td><?php echo $record->batch?></td>
					<td><?php echo anchor(site_url('/student/student_detail/'. $record->id), '<i class="fa fa-external-link">&nbsp;</i>','title="Student Details"') ?></td>
					<td><?php echo anchor(site_url('/student/edit/'. $record->id), '<i class="fa fa-pencil-square-o">&nbsp;</i>','title="Edit"') ?></td>
					<td><?php echo anchor(site_url('/student/delete/'. $record->id), '<i class="fa fa-trash-o">&nbsp;</i>','title="Delete" onclick="return confirm(\''.lang('student_delete_confirm').'\')"'); ?></td>
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