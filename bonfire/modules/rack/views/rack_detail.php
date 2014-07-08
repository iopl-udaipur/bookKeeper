<div class="row">
	<h1> Rack <small>Detail</small></h1>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo site_url('/rack')?>"> <i class="fa fa-dashboard"></i> Racks</a>
		</li>
		<li  class="active">
			<i class="fa fa-dashboard"></i>Rack Detail
		</li>
	</ol>
</div>

<div class="row">
	<div class="col-lg-6">
		<fieldset>
       		<div class="form-group">
            	<label  class="control-label">Name:</label><label class="detail"><?php echo $rack_details->name?></label>
        	</div>
        	<div class="form-group">
            	<label>Description </label><label class="detail"><?php echo $rack_details->description?></label>
        	</div>
        </fieldset>
	</div>
</div>

<?php echo anchor(site_url('/rack/add_shelf_to_rack/'. $rack_details->id), '<i class="btn btn-primary">Add Shelf</i>','title="Add Shelf"');?>
<hr>
<div class="row">
	<div class="col-lg-6">
	<h3><u>Shelf Available</u></h3>
		<div class="table-responsive">
			<table class="table table-striped dataTablestable table-bordered table-hover dataTables no-footer">
				<thead>
					<tr>
						<th>Shelf Number</th>
						<th>Number of Books</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				
				<tbody>
				<?php if (isset($shelfs_arr) && is_array($shelfs_arr) && count($shelfs_arr)) : ?>
				<?php foreach ($shelfs_arr as $id=>$shelf) : ?>
					<?php
						$style = ' class="danger"';
						if(array_key_exists($id, $shelf_book_count_arr))
						{
							$style = ' class="success"';
						}
						
					?>
					<tr<?php echo $style;?>>
						<td><?php echo $shelf->shelf_number ?></td>
						<?php 
						if(array_key_exists($id, $shelf_book_count_arr))
						{
							echo "<td>".$shelf_book_count_arr[$id]->num_of_books."</td>";
						}
						else 
						{
							echo "<td> - </td>";
						}
						echo "<td>".anchor(site_url('/rack/shelf_detail/'.$rack_details->id.'/'.$shelf->id), '<i class="fa fa-external-link">&nbsp;</i>','title="Shelf Details"')."</td>";
						
						if(array_key_exists($id, $shelf_book_count_arr))
						{
							echo "<td></td>";
						}
						else 
						{
							echo "<td>".anchor(site_url('/rack/delete_shelf/'.$rack_details->id.'/'.$shelf->id), '<i class="fa fa-trash-o">&nbsp;</i>','title="Delete Shelf" onclick="return confirm(\''.lang('shelf_delete_confirm').'\')"')."</td>";
						}
						?>
						
					</tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="9">No Shelfs</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>