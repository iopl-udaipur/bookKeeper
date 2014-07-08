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
					<label for="isbn">ISBN</label>
					<input class="form-control" placeholder="ISBN" id="isbn" type="text" name="isbn" maxlength="100"   value="<?php isset($_POST['isbn']) ? $_POST['isbn'] : ''; ?>" />
				</div>
				<div class="form-group">
					<label for="title">Title</label>
					<input class="form-control" placeholder="Title" id="title" type="text" name="title" maxlength="100" value="<?php isset($_POST['title']) ? $_POST['title'] : ''; ?>" />
				</div>
				<div class="form-group">
					<label for="author">Author</label>
					<input class="form-control" placeholder="Author" id="author" type="text" name="author" maxlength="100" value="<?php isset($_POST['author']) ? $_POST['author'] : ''; ?>"  />
				</div>
				<div class="form-group">
					<label for="publisher">Publisher</label>
					<input class="form-control" placeholder="Publisher" id="publisher" type="text" name="publisher" maxlength="100" value="<?php isset($_POST['publisher']) ? $_POST['publisher'] : ''; ?>"  />
				</div>
			</div>
			<div style="width: 300px; margin-left: 20px;" class="pull-left">
				<div class="form-group">
					<label for="year">Year</label>
					<input class="form-control" placeholder="Year" id="year" type="text" name="year" maxlength="100" value="<?php isset($_POST['year']) ? $_POST['year'] : ''; ?>"  />
				</div>
				<div class="form-group">
					<label for="year">Class</label>
					<input class="form-control" placeholder="Class" id="class_name" type="text" name="class_name" maxlength="100" value="<?php isset($_POST['year']) ? $_POST['year'] : ''; ?>"  />
				</div>
				<div class="form-group">
					<label for="year">Category Name</label>
					<input class="form-control" placeholder="Category Name" id="category_name" type="text" name="category_name" maxlength="100" value="<?php isset($_POST['year']) ? $_POST['year'] : ''; ?>"  />
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-actions">
	            <input type="submit" class="btn btn-primary" value="Search" />
	        </div>
		<?php echo form_close(); ?>
		<h1>Search result</h1>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Edit</th>
					<th>ISBN</th>
					<th>Title</th>
					<th>Author</th>
					<th>Publisher</th>
					<th>Year</th>
					<th>Class</th>
					<th>Category</th>
				</tr>
			</thead>
			<?php //endif; ?>
			<tbody>
			<?php if ($records): ?>
			<?php foreach ($records as $record) : ?>
				<tr>
					<td><?php echo anchor(site_url('/books/edit/'. $record->id), '<i class="icon-pencil">&nbsp;</i>'.'edit') ?></td>
					<td><?php echo $record->isbn?></td>
					<td><?php echo $record->title?></td>
					<td><?php echo $record->author?></td>
					<td><?php echo $record->publisher?></td>
					<td><?php echo date("Y", strtotime($record->year));?></td>
					<td><?php echo $record->class_name?></td>
					<td><?php echo $record->category_name?></td>
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