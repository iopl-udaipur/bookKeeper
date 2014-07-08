<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shelf_model extends BF_Model {

	protected $table		= "shelf";
	protected $key			= "id";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";
	protected $set_created	= true;
	protected $set_modified = true;
	protected $created_field = "created_on";
	protected $modified_field = "modified_on";
	
/*	public function get_all_books_in_category($book_id)
	{
		$this->where('book_id', $book_id);
		$this->where('deleted',0);
		return $this->find_all();
	}
	
	public function get_num_of_copies_each_book()
	{
		$query = "
			select book_id, count(*) as num_of_books
			from bf_book_copies
			where deleted=0
			group by book_id;
			";
		
		$result = $this->db->query($query)->result();
		
		return $result;
	}
	
	public function get_available_book_copies($book_id)
	{
		$query = "
			select * from bf_book_copies
			where bf_book_copies.book_id = ".$book_id." and bf_book_copies.id not in (
			select bf_issue_book.book_copy_id
			from bf_issue_book
			where bf_issue_book.submit_date is null);
			";
		
		$result = $this->db->query($query)->result();
		
		return $result;
	}
	
	public function get_available_book_copies_for_edit($book_id,$book_copy_id)
	{
		$query = "
			select * from bf_book_copies
			where bf_book_copies.book_id = ".$book_id." and bf_book_copies.id not in (
			select bf_issue_book.book_copy_id
			from bf_issue_book
			where bf_issue_book.submit_date is null and bf_issue_book.book_copy_id <> ".$book_copy_id.");
			";
		
		$result = $this->db->query($query)->result();
		
		return $result;
	}
*/	
	public function add_shelf($data)
	{
		return $this->db->insert_batch($this->table, $data);
	}
	
	public function get_shelf_by_rack($rack_id, $shelf_number)
	{
		$query = "
			select * from bf_shelf
				where rack_id = ".$rack_id." and shelf_number in (".implode(',', $shelf_number).")
			";
		
		$shelfs = $this->db->query($query)->result();
		
		if ($shelfs)
		{
			$shelf_number_arr = array();
			
			foreach ($shelfs as $shelf)
				array_push($shelf_number_arr, $shelf->shelf_number);
				
			return implode(",", $shelf_number_arr);
		}
		else 
		{
			return false;
		}
	}
	
	public function get_number_of_shelf_in_each_rack()
	{
		$query = "
			select rack_id, count(*) number_of_shelf
			from bf_shelf
			group by rack_id;
			";
		
		$shelf_count = $this->db->query($query)->result();
		if ($shelf_count)
		{
			$shelf_count_arr = array();
			foreach ($shelf_count as $record)
				$shelf_count_arr[$record->rack_id] = $record->number_of_shelf;
			return $shelf_count_arr;
		}
		else 
		{
			return array();
		}
	}
	
	public function check_if_shelf_exist($rack_id)
	{
		$query = "
			select * 
			from bf_shelf
			where rack_id = ".$rack_id.";
			";
		
		return $this->db->query($query)->result();
	}
	
	public function get_max_shelf_number_by_rack_id($rack_id)
	{
		$query = "
			select MAX(shelf_number) max_shelf_number
			from bf_shelf
			where rack_id = ".$rack_id.";
			";
		
		$result = $this->db->query($query)->result();
		return $result[0]->max_shelf_number;
	}
	
	public function get_all_shelfs_by_rack($rack_id)
	{
		$query = "
			select * 
			from bf_shelf
			where rack_id = ".$rack_id.";
			";
		
		return $this->db->query($query)->result();
	}
	
}