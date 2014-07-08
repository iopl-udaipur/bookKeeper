<div class="row">
	<h1> Book <small>Detail</small></h1>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo site_url('/books')?>"> <i class="fa fa-dashboard"></i> Manage Books</a>
		</li>
		<li  class="active">
			<i class="fa fa-dashboard"></i> Book's Detail
		</li>
	</ol>
</div>

<div class="row">
	<div class="col-lg-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-book"></i> Book Detail</h3>
			</div>
			<div class="panel-body">
		       		<div class="form-group">
		            	<label  class="control-label">Title:</label><label class="detail"><?php echo $book_details->title?></label>
		        	</div>
		        	<div class="form-group">
		            	<label>Author: </label><label class="detail"><?php echo $book_details->author?></label>
		        	</div>
		        	<div class="form-group">
		            	<label>Publisher: </label><label class="detail"><?php echo $book_details->publisher?></label>
		        	</div>
		        	<div class="form-group">
		            	<label>Year: </label><label class="detail"><?php echo date("Y", strtotime($book_details->year))?></label>
		        	</div>
					<?php
						if ($book_details->deleted == 0)
						{
							echo anchor(site_url('/books/add_book_quantity/'. $book_id), 'Add Book','class="btn btn-primary" title="Add Quantity"');
						}
					?>
			</div>
		</div>
	</div>
</div>

<hr>
<div class="row">
	<div class="col-lg-6">
	<h3><u>Book Copies Available</u></h3>
		<div class="table-responsive">
			<table class="table table-striped dataTablestable table-bordered table-hover dataTables no-footer">
				<thead>
					<tr>
						<th></th>
						<th>Book U.ID</th>
						<th>Rack</th>
						<th>Shelf</th>
						<th>Issued To</th>
						<th>Return Date</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
				<?php if (isset($books_in_category_arr) && is_array($books_in_category_arr) && count($books_in_category_arr)) : ?>
				<?php foreach ($books_in_category_arr as $id=>$book) : ?>
					<?php
						$status_lbl = '<span class="label label-success">Avaliable</span>';
						$style = ' style="color: green;"';
						if(array_key_exists($id,$books_in_category_issued_arr) && $books_in_category_issued_arr[$id]->submit_date == NULL)
						{
							$status_lbl = '<span class="label label-danger">Defaulter</span>';
							$style = ' style="color: red;"';
							if(strtotime($books_in_category_issued_arr[$id]->return_date) > time())
							{
								$status_lbl = '<span class="label label-warning">Issued</span>';
								$style = ' style="color: orange;"';
							}
						}

					?>
					<tr<?php echo $style;?>>
						<td>
							<?php echo $status_lbl;?>
						</td>
						<td><?php echo $book->id ?></td>
						<?php
						if(array_key_exists($id,$rack_detail_arr))
						{
							echo "<td>".$rack_detail_arr[$id]->rackname."</td>";
							echo "<td>".$rack_detail_arr[$id]->shelf_number."</td>";
						}
						else
						{
							echo "<td> - </td>
								<td> - </td>";
						}

						if(array_key_exists($id,$books_in_category_issued_arr) && $books_in_category_issued_arr[$id]->submit_date==NULL)
						{
							echo "<td>".anchor(site_url('/student/student_detail/'.$books_in_category_issued_arr[$id]->student_id), $books_in_category_issued_arr[$id]->student_name)."</td>";
							echo "<td>".date("d-m-Y", strtotime($books_in_category_issued_arr[$id]->return_date))."</td>";
							echo "<td>".anchor(site_url('/books/submit_book/'.$book_id.'/'.$books_in_category_issued_arr[$id]->id), '<i class="icon-pencil">&nbsp;</i>'.'sumbit')."</td>";
							echo "<td> - </td>";
						}
						else
						{
							echo "<td> - </td>";
							echo "<td> - </td>";
							echo "<td>".anchor(site_url('/issue_book/issue_book_by_book_detail/'.$book_id.'/'.$book->id), '<i class="icon-pencil">&nbsp;</i>'.'issue')."</td>";
							echo "<td>".anchor(site_url('/books/delete_book_copy/'.$book_id.'/'.$book->id), '<i class="fa fa-trash-o">&nbsp;</i>','title="Delete Book" onclick="return confirm(\''.lang('books_delete_confirm').'\')"')."</td>";
						}
						?>
						<?php echo "<td>".anchor(site_url('/rack/add_book_to_shelf/'.$book->id), '<i class="fa fa-bars"></i>','title="Add to Rack"')."</td>"; ?>
						<?php 
						if(array_key_exists($id,$rack_detail_arr))
						{
							echo "<td>".anchor(site_url('/books/remove_book_from_rack/'.$book_id.'/'.$book->id), '<i class="fa fa-times">&nbsp;</i>','title="Remove Book From Rack" onclick="return confirm(\''.lang('book_remove_from_rack_confirm').'\')"')."</td>";
						}
						else 
						{
							echo "<td> - </td>";
						}
						?>
						
					</tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="9">No Books</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>