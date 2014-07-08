<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rack_model extends BF_Model {

	protected $table		= "rack";
	protected $key			= "id";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";
	protected $set_created	= true;
	protected $set_modified = true;
	protected $created_field = "created_on";
	protected $modified_field = "modified_on";
	
	public function get_books_rack_shelf_details()
	{
		$query = "
			select bf_shelf_detail.book_copy_id, bf_rack.id rack_id, bf_shelf.id shelf_id, bf_rack.name rackname, bf_shelf.shelf_number
			from bf_rack
			inner join bf_shelf
			on bf_rack.id = bf_shelf.rack_id
			inner join bf_shelf_detail
			on bf_shelf.id = bf_shelf_detail.shelf_id;
			";
		
		return $this->db->query($query)->result();
	}
	
}
