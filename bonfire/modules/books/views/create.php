<div class="row">
	<div class="col-lg-12">
		<h1> New Books <small>Enter Books Data</small></h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo site_url('/books')?>"> <i class="fa fa-dashboard"></i> Manage Books</a>
			</li>
			<li class="active">
				<i class="fa fa-user"></i>
				New Books
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<?php if (validation_errors()) : ?>
			<div class="alert alert-dismissable alert-danger">
		  		<a class="close" data-dismiss="alert">&times;</a>
		  		<h4 class="alert-heading">Please fix the following errors :</h4>
		 		<?php echo validation_errors(); ?>
			</div>
		<?php endif; ?>
		<?php // Change the css classes to suit your needs
			if (isset($books)) {
				$books = (array)$books;
			}
			$id = isset($books['id']) ? $books['id'] : '';
		?>
		<?php echo form_open($this -> uri -> uri_string()); ?>
    			<div class="form-group <?php echo form_error('books_isbn') ? 'error' : ''; ?>">
        			<?php echo form_label('ISBN' . lang('bf_form_label_required'), 'books_isbn', array('class' => "control-label")); ?>
					<input class="form-control" placeholder="ISBN Number" id="books_isbn" type="text" name="books_isbn" maxlength="20" value="<?php echo set_value('books_isbn', isset($books['books_isbn']) ? $books['books_isbn'] : ''); ?>"  />
					<span class="help-inline"><?php echo form_error('books_isbn'); ?></span>
				</div>

	        	<div class="form-group <?php echo form_error('books_title') ? 'error' : ''; ?>">
		            <?php echo form_label('Title', 'books_title', array('class' => "control-label")); ?>
		            <input class="form-control" placeholder="Title" id="books_title" type="text" name="books_title" maxlength="150" value="<?php echo set_value('books_title', isset($books['books_title']) ? $books['books_title'] : ''); ?>"  />
					<span class="help-inline"><?php echo form_error('books_title'); ?></span>
    			</div>

		        <div class="form-group <?php echo form_error('books_author') ? 'error' : ''; ?>">
		            <?php echo form_label('Author', 'books_author', array('class' => "control-label")); ?>
	        		<input class="form-control"  placeholder="Author" id="books_author" type="text" name="books_author" maxlength="150" value="<?php echo set_value('books_author', isset($books['books_author']) ? $books['books_author'] : ''); ?>"  />
	        		<span class="help-inline"><?php echo form_error('books_author'); ?></span>
		        </div>

		        <div class="form-group <?php echo form_error('books_publisher') ? 'error' : ''; ?>">
		            <?php echo form_label('Publisher', 'books_publisher', array('class' => "control-label")); ?>
	        		<input class="form-control"  placeholder="Publisher" id="books_publisher" type="text" name="books_publisher" maxlength="150" value="<?php echo set_value('books_publisher', isset($books['books_publisher']) ? $books['books_publisher'] : ''); ?>"  />
	        		<span class="help-inline"><?php echo form_error('books_publisher'); ?></span>
		        </div>
		        <div class="form-group <?php echo form_error('category_name') ? 'error' : ''; ?>">
		        	<?php echo form_label('Catgeory'. lang('bf_form_label_required'), 'books_category_name', array('class' => "control-label")); ?>
		        	<?php echo form_dropdown('books_category_name',$student_category_list, set_value('books_category_name', isset($books['books_category_name']) ? $books['books_category_name'] : ''),'','class="form-control" id="category_name"');?>
		        	<button type="button" id="category_name_btn" class="btn btn btn-primary">Add New Category</button>
	        		<span class="help-inline"><?php echo form_error('books_publisher'); ?></span>
		        </div>
		        <div class="form-group <?php echo form_error('class_name') ? 'error' : ''; ?>">
		        	<?php echo form_label('Class'. lang('bf_form_label_required'), 'books_class_name', array('class' => "control-label")); ?>
		        	<?php echo form_dropdown('books_class_name',$student_class_list, set_value('books_class_name', isset($books['books_class_name']) ? $books['books_class_name'] : ''),'','class="form-control" id="class_name"');?>
		        	<button type="button" id="class_name_btn" class="btn btn btn-primary" >Add New Class</button>
	        		<span class="help-inline"><?php echo form_error('books_publisher'); ?></span>
		        </div>
		        <div class="form-group <?php echo form_error('books_year') ? 'error' : ''; ?>">
		        	<?php 
		        		$years  = array();
		        		
						for($i = 1975; $i<=intval(date("Y")); $i++)
							$years[strval($i)] = strval($i);
					?>
		            <?php echo form_label('Year', 'books_year', array('class' => "control-label")); ?>
		            <?php echo form_dropdown('books_year',$years, set_value('books_year', isset($books['books_year']) ?date("Y", strtotime($books['books_year'])) : ''),'','class="form-control" id="books_year"');?>
	        		<span class="help-inline"><?php echo form_error('books_year'); ?></span>
		        </div>
		        <div class="form-actions">
		            <br/>
		            <input type="submit" name="save" class="btn btn-primary" value="Add Books" />
		            or <?php echo anchor('/books', lang('books_cancel'), 'class="btn btn-warning"'); ?>
		        </div>
		<?php echo form_close(); ?>
	</div>
	<script>
		function page_init(){
			jQuery('#category_name_btn').click(function(){
				var val = prompt('Enter new category name');
				if (val != null && val != ''){
					jQuery('#category_name').append('<option selected "'+val+'">'+val+'</option>');
				}
			});
			jQuery('#class_name_btn').click(function(){
				var val = prompt('Enter new class name');
				if (val != null && val != ''){
					jQuery('#class_name').append('<option selected "'+val+'">'+val+'</option>');
				}
			});
		}

	</script>
	<?php Assets::add_js('page_init();','inline'); ?>
</div>
