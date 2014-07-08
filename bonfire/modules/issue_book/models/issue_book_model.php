<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Issue_book_model extends BF_Model {

	protected $table		= "issue_book";
	protected $key			= "id";
	protected $soft_deletes	= true;
	protected $date_format	= "datetime";
	protected $set_created	= true;
	protected $set_modified = true;
	protected $created_field = "created_on";
	protected $modified_field = "modified_on";
	
	/*public function insert($data)
	{
		$update['issued'] = 1;
		$this->load->model('books/book_copies_model');
		if($this->book_copies_model->update($data['book_copy_id'], $update))
		{
		}
		return false;
		return parent::insert($data);
	}*/
	
	public function submit_book($issue_id)
	{
		$data['submit_date']	= date("Y-m-d");
		return parent::update($issue_id,$data);
	
	}
	
	public function get_defaulter_student()
	{
		$query = "
			select count(*) as num_of_books,MIN(bf_issue_book.return_date) as return_date,bf_issue_book.student_id,bf_student.name
			from bf_issue_book inner join bf_student
			on bf_issue_book.student_id=bf_student.id
			where DATEDIFF(bf_issue_book.return_date,CURDATE())<0 AND bf_issue_book.submit_date IS NULL
			group by bf_issue_book.student_id;";
		
		$result = $this->db->query($query)->result();

		return $result;
	}
	
	public function get_issued_group_by_students()
	{
		$query = "
			select student_id, count(*) num_of_books
			from bf_issue_book
			where submit_date is null
			group by student_id;";
		
		$result = $this->db->query($query)->result();

		return $result;
	}
	
	public function get_issued_book_by_std_id($std_id)
	{
		$query = "
		select bf_book_copies.book_uid,bf_books.isbn,bf_books.title,bf_books.author,bf_issue_book.id as issue_id, bf_issue_book.issue_date,bf_issue_book.return_date
			from bf_book_copies inner join bf_books
			on bf_book_copies.book_id = bf_books.id			
			inner join bf_issue_book
			on bf_issue_book.book_copy_id = bf_book_copies.id
			where bf_issue_book.student_id=".$std_id." AND bf_issue_book.submit_date is null;
		";
		
		return $this->db->query($query)->result();
	}
	
//	public function submit_book($id)
//	{
//		$data['submit_date']	= date("Y-m-d");
//		return $this->update($id, $data);
//	}
	
//	public function get_book_issue_info($book_copy_id)
//	{
//		$this->where("book_copy_id",$book_copy_id);
//		$this->where("submit_date",null);
//		return $this->find_all();
//	}
	
	public function get_book_category_issue_info($book_id)
	{
		$query = "
			select bf_issue_book.*, bf_student.id as student_id, bf_student.name as student_name
			from bf_issue_book inner join bf_student
			on bf_issue_book.student_id = bf_student.id
			where bf_issue_book.book_id = ".$book_id.";
		";
		
		return $this->db->query($query)->result();
	}
	
	public function check_for_issued_book($book_copy_id)
	{
		$this->where('book_copy_id',$book_copy_id);
		$this->where('submit_date',null);
		return $this->find_all();
	}
	
}
