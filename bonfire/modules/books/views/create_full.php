<!-- =================Sample <tr> used in clone on book_copies_num_of_books keyup================= -->
<table style="display: none;" id="book_uid_field">
	<tr>
		<td style="width: 60px;"><input readonly class="form-control" placeholder="Book UID" stype="text" name="book_copies_book_uid[]" maxlength="150" value=""/></td>
		<td style="width: 115px;">
			<input class="form-control datepicker" placeholder="Purchase Date" type="text" name="book_copies_purchase_date[]" maxlength="150" value=""/>
		</td>
		<td style="width: 115px;">
			<input class="form-control" placeholder="Purchase By" type="text" name="book_copies_purchase_by[]" maxlength="150" value=""/>
		</td>
		<td style="width: 115px;">
			<input class="form-control" placeholder="Price" type="text" name="book_price[]" maxlength="10" value=""/>
		</td>
		<td>
			<select class="form-control" name="book_copies_donated[]">
				<option value="0">Not Donated</option>
				<option value="1">Donated</option>
			</select>
		</td>
		<td>
			<select name="shelf_detail_rack_id[]" class="form-control shelf_detail_rack_id">
				<?php foreach ($rack_dropdown_values as $key=>$value): ?>
					<option value="<?php echo $key;?>"><?php echo $value;?></option>
				<?php endforeach;?>
			</select>
		</td>

		<td>
			<select name="shelf_detail_shelf_id[]" class="form-control shelf_detail_shelf_id">
				<option value="">Shelf</option>
			</select>
        </td>
	</tr>
</table>
<!-- ============================================================================================= -->


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
	<div class="col-lg-12">
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
		</div>
		<?php echo form_open($this -> uri -> uri_string()); ?>
			<div class="col-lg-6">
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
<!-- ================================ to add book-quantity ================================ -->
			
		        <?php echo form_label('Number of books' . lang('bf_form_label_required'), 'book_copies_num_of_books', array('class' => "control-label")); ?>
	            <input class="form-control" placeholder="Enter number of books" name="book_copies_num_of_books" id="book_copies_num_of_books" type="text" maxlength="150" value="<?php echo set_value('book_copies_num_of_books', isset($_POST['book_copies_num_of_books']) ? $_POST['book_copies_num_of_books'] : ''); ?>" style="width: 530px;"/>
				<span class="help-inline"><?php echo form_error('book_copies_num_of_books'); ?></span>
			</div>

			<div class="col-lg-9">
				<table id="book_uid"></table>
			</div>
<!-- ======================================================================================== -->
			<div class="col-lg-6">
		        <div class="form-actions">
		            <br/>
		            <input type="submit" name="save" class="btn btn-primary" value="Add Books" />
		            or <?php echo anchor('/books', lang('books_cancel'), 'class="btn btn-warning"'); ?>
		        </div>
			</div>
		<?php echo form_close(); ?>
	
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

<?php 
//script for adding book-quantity
Assets::add_js("

	jQuery('#book_copies_num_of_books').keyup(function(){
		var num = jQuery(this).val();
		var uid_fields = '';

		jQuery('#book_uid').empty();

		if(/^[0-9]+$/.test(num))
		{
			jQuery('#book_uid').html(\"<label class='control-label'>Book UID<span class='required'>*</span></label>\");

			for (var i=0;i<num;i++)
			{
				jQuery('#book_uid_field tr').clone().appendTo('#book_uid');
			}
			var last_book_id = ".$max_book_id.";
			jQuery('[name=\"book_copies_book_uid[]\"]').each( function(i, obj) {
				jQuery(obj).val(last_book_id + i);
				i++;
			});
			$('.datepicker').each(function(){
				$(this).datepicker({ format: 'yyyy-mm-dd' });
			})
		}
		else if(num!='')
		{
			alert('Enter valid number');
			jQuery(this).val('');
		}
	});

",'inline');

if (isset($_POST['book_copies_num_of_books']))
{
	Assets::add_js("
		jQuery('#book_copies_num_of_books').trigger('keyup');
	",'inline');
}
	?>

<?php 
//script for rack - shelf
Assets::add_js("
	jQuery('#book_uid').on('change','.shelf_detail_rack_id',function(){
			var shelf_arr = ".json_encode($shelf_arr).";
			var shelf_dropdown = '<option value=\"\">Select Shelf</option>';
			var rack_id = jQuery(this).val();
			if (rack_id != '')
			{
				jQuery.each(shelf_arr[rack_id], function(i, item) {
				    shelf_dropdown = shelf_dropdown + '<option value = \"' +item.id+ '\">' +item.shelf_number+ '</option>';
				});
			}
			jQuery(this).parent().parent().find('.shelf_detail_shelf_id').html(shelf_dropdown);
	});
",'inline');?>