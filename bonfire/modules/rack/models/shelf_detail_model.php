<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shelf_detail_model extends BF_Model {

	protected $table		= "shelf_detail";
	protected $key			= "id";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";
	protected $set_created	= true;
	protected $set_modified = true;
	protected $created_field = "created_on";
	protected $modified_field = "modified_on";
	
	public function add_book_to_shelf($data)
	{
		if($this->find_all_by('book_copy_id',$data['book_copy_id']))
		{
			$result = $this->update_where('book_copy_id', $data['book_copy_id'], $data);
		}
		else
		{
			$result = $this->insert($data);
		}
			
		return $result;
	}
	
	public function add_multi_book_to_shelf($data)
	{
		return $this->db->insert_batch($this->table, $data);
	}
	
	public function remove_book_from_shelf($book_copy_id)
	{
		$where = array('book_copy_id'=>$book_copy_id);
		return $this->delete_where($where);
	}
	
	public function get_shelf_book_count($rack_id)
	{
		$query = "
			select bf_shelf_detail.shelf_id, count(bf_shelf_detail.shelf_id) num_of_books
			from bf_shelf inner join bf_shelf_detail
			on bf_shelf.id = bf_shelf_detail.shelf_id
			where bf_shelf.rack_id = ".$rack_id."
			group by bf_shelf_detail.shelf_id;
			";
		
		return $this->db->query($query)->result();
	}
	
	public function get_all_books_by_shelf($shelf_id)
	{
		$query = "
			select bf_shelf_detail.id shelf_detail_id, bf_book_copies.id book_copy_id, bf_books.id book_id, bf_books.title book_title
			from bf_shelf_detail
			inner join bf_book_copies
			on bf_book_copies.id = bf_shelf_detail.book_copy_id
			inner join bf_books
			on bf_books.id = bf_book_copies.book_id
			where bf_shelf_detail.shelf_id = ".$shelf_id.";
			";
		
		return $this->db->query($query)->result();
	}
}