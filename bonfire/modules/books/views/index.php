<div class="row">
	<h1> Books <small>list</small></h1>
	<ol class="breadcrumb">
		<li  class="active">
			<i class="fa fa-dashboard"></i> Manage books
		</li>
	</ol>
</div>
<div class="row">
	<div class="well shallow-well">
		<span class="filter-link-list">
			<?php /* If there's a current filter, we need to replace the caption with a clear button. */ ?>
			<?php if ($filter_type=='first_letter'): ?>
				<a href="<?php echo site_url('/books')?>" class="btn btn-small btn-primary"><?php echo lang('bf_clear') ?></a>
			<?php else: ?>
				<?php e(lang('filter_members_name')) ?>
			<?php endif; ?>

			<?php $letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'); ?>
			<?php foreach ($letters as $letter): ?>
				<a href="<?php echo site_url('/books/index').'/'. 'first_letter-' . $letter; ?>"><?php echo $letter; ?></a>
			<?php endforeach; ?>
		</span>
	</div>
</div>
<ul class="nav nav-tabs" style="margin-bottom: 15px;">
	<li <?php echo $filter_type=='all' ? 'class="active"' : ''; ?>><a href="<?php echo site_url('/books/index'); ?>">All</a></li>
	<li <?php echo $filter_type=='deleted' ? 'class="active"' : ''; ?>><a href="<?php echo site_url('/books/index').'/' .'deleted/'; ?>">Deleted</a></li>
	<li class="<?php echo $filter_type=='isbn' ? 'active ' : ''; ?>dropdown">
		<a href="#" class="drodown-toggle" data-toggle="dropdown">
			ISBN <?php echo isset($filter_isbn) ? ": $filter_isbn" : ''; ?>
			<b class="caret light-caret"></b>
		</a>
		<ul class="dropdown-menu" id="ul_isbn_filter">
			<li>
				<input class="form-control" placeholder="ISBN Number" id="txt_filter_isbn" type="text" maxlength="20" />
				<button id="searchISBN" class="btn btn-primary">Search</button>
			</li>
		</ul>
	</li>
</ul>
<div id="myTabContent" class="tab-content">
	<div id="home" class="tab-pane fade active in">
		<div class="table-responsive">
			<table class="table table-striped dataTablestable table-bordered table-hover dataTables no-footer">
				<thead>
					<tr>
						<th></th>
						<th>ISBN</th>
						<th>Title</th>
						<th>Author</th>
						<th>Publisher</th>
						<th>Year</th>
						<th>Class</th>
						<th>Category</th>
						<th>Copies Available</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php if (isset($book_records) && is_array($book_records) && count($book_records)) : ?>
					<?php foreach ($book_records as $record) : ?>
								<?php
									if (array_key_exists($record->id, $books_copies)){
										$status_lbl = '<span class="label label-success">Avaliable</span>';
										$style = ' style="color: green;"';
									}else{
										$status_lbl = '<span class="label label-danger">Not Available</span>';
										$style = ' style="color: red;"';
									}
								?>
						<tr<?php echo $style;?>>
							<td>
								<?php echo $status_lbl;?>
							</td>
							<td><?php echo $record->isbn;?></td>
							<td><?php echo $record->title;?></td>
							<td><?php echo $record->author;?></td>
							<td><?php echo $record->publisher;?></td>
							<td><?php echo date("Y", strtotime($record->year));?></td>
							<td><?php echo $record->class_name;?></td>
							<td><?php echo $record->category_name;?></td>
							<td><?php echo array_key_exists($record->id, $books_copies)?$books_copies[$record->id]:'0'?></td>
							<td><?php echo anchor(site_url('/books/book_detail/'. $record->id), '<i class="fa fa-external-link">&nbsp;</i>','title="Book Details" class="btn-"') ?></td>
							<?php if ($record->deleted==0):?>
								<td><?php echo anchor(site_url('/books/add_book_quantity/'. $record->id), '<i class="fa fa-plus-square">&nbsp;</i>','title="Add Quantity"') ?></td>
								<td><?php echo anchor(site_url('/books/edit/'. $record->id), '<i class="fa fa-pencil-square-o">&nbsp;</i>','title="Edit"') ?></td>
								<?php
								if (!array_key_exists($record->id, $books_copies)){
									echo "<td>".anchor(site_url('/books/delete/'. $record->id), '<i class="fa fa-trash-o">&nbsp;</i>','title="Delete Book" onclick="return confirm(\''.lang('books_delete_confirm').'\')"')."</td>";
								}else
								{
									echo "<td></td>";
								}
									?>
							<?php else:?>
								<td></td>
								<td></td>
								<td></td>
							<?php endif;?>
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
</div>

<?php
	Assets::add_js("
		
	jQuery('#searchISBN').click(function(){
		console.log('hii');
		var isbn = jQuery('#txt_filter_isbn').val();
		if(isbn!='')
			window.location = '". site_url('/books/index').'/'. 'isbn-'."' + isbn;
	});

	jQuery('#ul_isbn_filter').focusin(function() {
		jQuery(this).css('display', 'list-item');
	});
	
	jQuery('#ul_isbn_filter').focusout(function() {
		jQuery(this).css('display', '');
	});
	",'inline');
?>
