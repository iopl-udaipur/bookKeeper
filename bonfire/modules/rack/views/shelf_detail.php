<div class="row">
	<h1> Shelf <small>Detail</small></h1>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo site_url('/rack')?>"> <i class="fa fa-dashboard"></i> Racks</a>
		</li>
		<li>
			<a href="<?php echo site_url('/rack/rack_detail/'.$rack_details->id)?>"> <i class="fa fa-dashboard"></i> Rack Detail</a>
		</li>
		<li  class="active">
			<i class="fa fa-dashboard"></i>Shelf Detail
		</li>
	</ol>
</div>

<div class="row">
	<div class="col-lg-6">
		<fieldset>
       		<div class="form-group">
            	<label  class="control-label">Rack Name:</label><label class="detail"><?php echo $rack_details->name?></label>
        	</div>
        	<div class="form-group">
            	<label>Rack Description </label><label class="detail"><?php echo $rack_details->description?></label>
        	</div>
        </fieldset>
	</div>
	<div class="col-lg-6">
		<fieldset>
        	<div class="form-group">
            	<label>Shelf Number: </label><label class="detail"><?php echo $shelf_details->shelf_number?></label>
        	</div>
        </fieldset>
	</div>
</div>

<hr>
<div class="row">
	<div class="col-lg-6">
	<h3><u>Books in Shelf</u></h3>
		<div class="table-responsive">
			<table class="table table-striped dataTablestable table-bordered table-hover dataTables no-footer">
				<thead>
					<tr>
						<th>Book Title</th>
						<th>Remove Book</th>
					</tr>
				</thead>
				
				<tbody>
				<?php if (isset($books) && is_array($books) && count($books)) : ?>
				<?php foreach ($books as $book) : ?>
					
					<tr>
						<?php echo "<td>".anchor(site_url('/books/book_detail/'.$book->book_id), $book->book_title,'title="Book Details"')."</td>"; ?>
						<?php echo "<td>".anchor(site_url('/books/remove_book_from_rack/'.$book->book_id.'/'.$book->book_copy_id), '<i class="fa fa-trash-o">&nbsp;</i>','title="Remove Book From Rack" onclick="return confirm(\'Remove Book From Rack\')"')."</td>"; ?>
						
					</tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="9">No Book in this Shelf</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>