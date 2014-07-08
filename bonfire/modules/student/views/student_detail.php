<div class="row">
	<h1> Student <small>Detail</small></h1>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo site_url('/student')?>"> <i class="fa fa-dashboard"></i> Manage student</a>
		</li>
		<li  class="active">
			<i class="fa fa-dashboard"></i>Student's Detail
		</li>
	</ol>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-book"></i> Student Detail</h3>
			</div>
			<div class="panel-body">
				<fieldset>
		       		<div class="form-group">
		            	<label  class="control-label">Name:</label><label class="detail"><?php echo $student_record->name?></label>
		        	</div>
		        	<div class="form-group">
		            	<label>Address: </label><label class="detail"><?php echo $student_record->address?></label>
		        	</div>
		        	<div class="form-group">
		            	<label>Course: </label><label class="detail"><?php echo $student_record->course?></label>
		        	</div>
		        	<div class="form-group">
		            	<label>Batch: </label><label class="detail"><?php echo $student_record->batch?></label>
		        	</div>
		        </fieldset>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-lg-6">
	<h3><u>Books Issued</u></h3>
		<div class="table-responsive">
			<table class="table table-striped dataTablestable table-bordered table-hover dataTables no-footer">
				<thead>
					<tr>
						<th></th>
						<th>ISBN</th>
						<th>Title</th>
						<th>Author</th>
						<th>Issue Date</th>
						<th>Return Date</th>
						<th></th>
					</tr>
				</thead>

				<tbody>
				<?php if (isset($books_issued) && is_array($books_issued) && count($books_issued)) : ?>
				<?php foreach ($books_issued as $book) : ?>
					<?php
						
						if(strtotime($book->return_date) > time()){
							$status_lbl = '<span class="label label-warning">Issued</span>';
							$style = ' style="color: orange;"';
						}else{
							$status_lbl = '<span class="label label-danger">Defaulter</span>';
							$style = ' style="color: red;"';
						}
					?>
					<tr<?php echo $style;?>>
						<td><?php echo $status_lbl;?></td>
						<td><?php echo $book->isbn ?></td>
						<td><?php echo $book->title ?></td>
						<td><?php echo $book->author?></td>
						<td><?php echo $book->issue_date?></td>
						<td><?php echo $book->return_date?></td>
						<td>
							<?php echo anchor(site_url('/student/submit_book/'.$student_record->id.'/'.$book->issue_id), '<i class="icon-pencil">&nbsp;</i>'.'submit');?>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7">No Books are issued</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>