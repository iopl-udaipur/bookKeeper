<?php echo theme_view('parts/_header'); ?>
<div class="row">
	<div class="btn-group btn-group-justified">
		<a class="btn btn-primary" href="<?php echo site_url('/books/create')?>">
			<i class="fa fa-2x fa-book"></i> New Book
		</a>
		<a class="btn btn-primary" href="<?php echo site_url('/student/create')?>">
			<i class="fa fa-2x fa-user"></i> New Student
		</a>
		<a class="btn btn-primary" href="<?php echo site_url('/issue_book/create')?>">
			<i class="fa fa-2x fa-bookmark"></i> Issue Book
		</a>
	</div>
</div>
<?php
	echo Template::message();
	echo isset($content) ? $content : Template::yield();
?>

<?php echo theme_view('parts/_footer'); ?>