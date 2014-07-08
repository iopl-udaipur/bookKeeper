<?php
	Assets::add_js(
		array(
			'jquery-1.10.2.js',
			'bootstrap.js',
			'bootstrap-datepicker.js',
			'tablesorter/jquery.tablesorter.js',
			'dataTables/jquery.dataTables.js',
			'dataTables/dataTables.bootstrap.js',
			'script.js'
		)
	);

	Assets::add_css(
		array(
			'bootstrap.css',
			'datepicker.css',
		 	'sb-admin.css',
		 	'font-awesome/css/font-awesome.min.css',
		 	'dataTables/dataTables.bootstrap.css',
			'custom.css'
			)
		);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Dashboard - SB Admin</title>
		<?php echo Assets::css(); ?>
	</head>

	<body>
		<div id="wrapper">
			<?php echo theme_view('parts/menu'); ?>
			<div id="page-wrapper">