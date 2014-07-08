<?php
	Assets::add_css(
		array(
			'bootstrap.css',
			'signin.css',
			'style.css',
			)
		);
Assets::add_js(
		array(
			'jquery-1.10.2.js',
			'bootstrap.js'
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
		<title>Book Keeper login</title>
		<?php echo Assets::css(); ?>
	</head>

	<body>
		<div id="wrapper">
			<div id="page-wrapper">
				<?php
					echo isset($content) ? $content : Template::yield();
				?>
			</div>
		</div>
		<?php echo Assets::js(); ?>
	</body>
</html>


