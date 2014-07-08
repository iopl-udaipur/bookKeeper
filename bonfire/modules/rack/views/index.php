<div class="row">
	<h1> Racks</h1>
	<div style="float: right;">
	<?php echo anchor('/rack/create', lang('rack_create'), 'class="btn btn-primary"'); ?>
	</div>
	<ol class="breadcrumb">
		<li  class="active">
			<i class="fa fa-dashboard"></i> Manage Racks
		</li>
	</ol>
</div>
<div class="row">
	<div class="table-responsive">
		<table class="table table-striped dataTablestable table-bordered table-hover dataTables no-footer">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Number of Shelves</th>
					<th>Rack Details</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php if (isset($rack_records) && is_array($rack_records) && count($rack_records)) : ?>
				<?php foreach ($rack_records as $record) : ?>
					<?php
						$style = ' class="danger"';
						if (array_key_exists($record->id, $num_of_shelf))
							$style = ' class="success"';
					?>
					<tr<?php echo $style;?>>
						<td><?php echo $record->name?></td>
						<td><?php echo $record->description?></td>
						<td><?php echo array_key_exists($record->id, $num_of_shelf)?$num_of_shelf[$record->id]:'0'?></td>

						<td><?php echo anchor(site_url('/rack/rack_detail/'. $record->id), '<i class="fa fa-external-link">&nbsp;</i>','title="Rack Details"') ?></td>							
						<td><?php echo anchor(site_url('/rack/add_shelf_to_rack/'. $record->id), '<i class="fa fa-plus-square">&nbsp;</i>','title="Add Shelf"') ?></td>
						<td><?php echo anchor(site_url('/rack/edit/'. $record->id), '<i class="fa fa-pencil-square-o">&nbsp;</i>','title="Edit"') ?></td>
						<?php 
						if (!array_key_exists($record->id, $num_of_shelf)){
							echo "<td>".anchor(site_url('/rack/delete/'. $record->id), '<i class="fa fa-trash-o">&nbsp;</i>','title="Delete Rack" onclick="return confirm(\''.lang('rack_delete_confirm').'\')"')."</td>";
						}else
						{
							echo "<td></td>";
						}
						?>
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