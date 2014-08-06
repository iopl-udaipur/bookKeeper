<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Books extends Authenticated_Controller {

	//--------------------------------------------------------------------


	public function __construct()
	{
		parent::__construct();

		//$this->auth->restrict('Books.Content.View');
		$this->load->model('books_model', null, true);
		$this->load->model('book_copies_model', null, true);
		$this->lang->load('books');
	}

	public function index($filter='all', $offset=0)
	{

		$where = array();
		$show_deleted = FALSE;

		// Filters
		if (preg_match('{first_letter-([A-Z])}', $filter, $matches))
		{
			$filter_type = 'first_letter';
			$first_letter = $matches[1];
		}
		elseif (preg_match('{isbn-([A-Za-z0-9]+)}',$filter, $matches))
		{			
			$filter_type = 'isbn';
			$isbn = $matches[1];
		}
		else
		{
			$filter_type = $filter;
		}
		
		switch($filter_type)
		{
		
			case 'deleted':
				$where['books.deleted'] = 1;
				$show_deleted = TRUE;
				break;
		
			case 'first_letter':
				$where['SUBSTRING( LOWER(title), 1, 1)='] = $first_letter;
				break;
				
			case 'isbn':
				$where['isbn'] = $isbn;
				Template::set('filter_isbn', $isbn);
				break;
		
			case 'all':
				$where['books.deleted'] = 0;
				$show_deleted = FALSE;
				break;
				
			default:
				show_404("books/index/$filter/");
		}
		
		// Fetch the members to display
		$this->books_model->where($where);
		$book_records = $this->books_model->find_all();

//=====================================================================
		$books_copies = array();
		
		$books_copies_records = $this->book_copies_model->get_num_of_copies_each_book();
		
		foreach ($books_copies_records as $record)
		{
			$books_copies[$record->book_id]=$record->num_of_books;
		}
//=====================================================================		
		Template::set('book_records', $book_records);
		Template::set('books_copies', $books_copies);
		Template::set('filter_type', $filter_type);
		Template::set('toolbar_title', 'Manage Books');
		Template::render();
	}
	
	public function delete($id)
	{

		if($this->book_copies_model->check_if_book_copies_exist($id))
		{
			Template::set_message('First delete all book copies', 'error');
		}
		else 
		{
			$result = $this->books_model->delete($id);
			if ($result)
			{
				Template::set_message(lang('books_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('books_delete_failure') . $this->books_model->error, 'error');
			}
		}
		
		redirect('books/index');
	}
	
	public function delete_book_copy($book_id,$id)
	{
		$student_category_list = $this->books_model->get_category_list();
		$student_category_list_arr = array(''=>'select category');
		foreach ($student_category_list as $key) {
			array_push($student_category_list_arr,$key->category_name);
		}
		$student_class_list = $this->books_model->get_class_list();
		$student_class_list_arr = array(''=>'select class');
		foreach ($student_class_list as $key) {
			array_push($student_class_list_arr,$key->class_name);
		}
		Template::set('student_category_list',$student_category_list_arr);
		Template::set('student_class_list',$student_class_list_arr);

		$this->load->model('issue_book/issue_book_model', null, true);
		if($this->issue_book_model->check_for_issued_book($id))
		{
			Template::set_message('Can not delete issued book', 'error');
		}
		else 
		{
			$result = $this->book_copies_model->delete($id);
			if ($result)
			{
				Template::set_message(lang('books_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('books_delete_failure') . $this->books_model->error, 'error');
			}
		}
		
		redirect('books/book_detail/'.$book_id);
	}
	
	public function remove_book_from_rack($book_id,$book_copy_id)
	{
		$this->load->model('rack/shelf_detail_model', null, true);
		
		$result = $this->shelf_detail_model->remove_book_from_shelf($book_copy_id);
		if ($result)
		{
			Template::set_message(lang('book_copies_remove_from_rack_success'), 'success');
		}
		else
		{
			Template::set_message(lang('book_copies_remove_from_rack_failure') . $this->books_model->error, 'error');
		}
		
		redirect('books/book_detail/'.$book_id);
	}
	
	public function create()
	{
		if ($this->input->post('save'))
		{
			if ($insert_id = $this->save_books())
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('books_act_create_record').': ' . $insert_id . ' : ' . $this->input->ip_address(), 'books');

				Template::set_message(lang('books_create_success'), 'success');
				Template::redirect('/books');
			}
			else
			{
				Template::set_message(lang('books_create_failure') . $this->books_model->error, 'error');
			}
		}
		
		$student_category_list = $this->books_model->get_category_list();
		$student_category_list_arr = array(''=>'select category');
		if($student_category_list){
			foreach ($student_category_list as $key) {
				$student_category_list_arr[$key->category_name] = $key->category_name;
			}
		}
		
		$student_class_list = $this->books_model->get_class_list();
		$student_class_list_arr = array(''=>'select class');
		if($student_class_list)
		{
			foreach ($student_class_list as $key) {
				$student_class_list_arr[$key->class_name] = $key->class_name;
			}
		}
		
		Template::set('student_category_list',$student_category_list_arr);
		Template::set('student_class_list',$student_class_list_arr);
		
		Assets::add_module_js('books', 'books.js');

		Template::set('toolbar_title', lang('books_create') . ' Books');
		Template::render();
	}
	
	//create_full includes creating book, book-copies and adding them to rack
	public function create_full()
	{
		//var_dump($_POST);die();
		if ($this->input->post('save'))
		{
			
			$this->db->trans_begin();
			
			if ($insert_id = $this->save_books())
			{
//============================================================
				if (isset($_POST['book_copies_num_of_books']) && !empty($_POST['book_copies_num_of_books']))
				{
					if ($this->add_book_copies_rack($insert_id,$_POST['book_copies_num_of_books']))
					{
						// Log the activity
						$this->activity_model->log_activity($this->current_user->id, lang('books_act_create_record').': ' . $insert_id . ' : ' . $this->input->ip_address(), 'books');
						
						$this->db->trans_commit();
						
						Template::set_message(lang('books_create_success'), 'success');
						Template::redirect('/books');
					}
					else
					{
						$this->db->trans_rollback();
						Template::set_message(lang('books_create_failure') . $this->books_model->error, 'error');
					}
				}
				else 
				{
						$this->db->trans_commit();
						
						Template::set_message(lang('books_create_success'), 'success');
						Template::redirect('/books');
				}
//================================================================
			}
			else
			{
				$this->db->trans_rollback();
				Template::set_message(lang('books_create_failure') . $this->books_model->error, 'error');
			}
		}
		
		$student_category_list = $this->books_model->get_category_list();
		$student_category_list_arr = array(''=>'select category');
		if($student_category_list){
			foreach ($student_category_list as $key) {
				$student_category_list_arr[$key->category_name] = $key->category_name;
			}
		}
		
		$student_class_list = $this->books_model->get_class_list();
		$student_class_list_arr = array(''=>'select class');
		if($student_class_list)
		{
			foreach ($student_class_list as $key) {
				$student_class_list_arr[$key->class_name] = $key->class_name;
			}
		}
		
//----------------------------------add Quantity----------------------------------
		$max_book_id = $this->book_copies_model->get_max_book_id();
		
		Template::set('max_book_id', $max_book_id);
//---------------------------------------------------------------------------------

//----------------------------------add Rack-Shelf---------------------------------

		$this->load->model('rack/rack_model', null, true);
		$this->load->model('rack/shelf_model', null, true);
		
		$rack  = $this->rack_model->find_all();
		$shelf  = $this->shelf_model->find_all();
		
		$shelf_arr = array();
		$rack_dropdown_values = array();
		$rack_dropdown_values[''] = 'Rack'; 
		
		if ($rack && $shelf)
		{
			foreach ($rack as $rck_record)
			{
				$shelf_arr[$rck_record->id] = array();
				$rack_dropdown_values[$rck_record->id] = $rck_record->name;
			}
				
			foreach ($shelf as $shf_record)
				array_push( $shelf_arr[$shf_record->rack_id], $shf_record); 
		}
		
		//Template::set('book', $book);
		//Template::set('book_copy', $book_copy);
		Template::set('rack', $rack);
		Template::set('shelf', $shelf);
		Template::set('rack_dropdown_values', $rack_dropdown_values);
		Template::set('shelf_arr', $shelf_arr);
		
//---------------------------------------------------------------------------------		
		Template::set('student_category_list',$student_category_list_arr);
		Template::set('student_class_list',$student_class_list_arr);
		
		Assets::add_module_js('books', 'books.js');

		Template::set('toolbar_title', lang('books_create') . ' Books');
		Template::render();
	}

	public function edit()
	{
		$student_category_list = $this->books_model->get_category_list();
		$student_category_list_arr = array(''=>'select category');
		if($student_category_list){
			foreach ($student_category_list as $key) {
				$student_category_list_arr[$key->category_name] = $key->category_name;
			}
		}
		$student_class_list = $this->books_model->get_class_list();
		$student_class_list_arr = array(''=>'select class');
		if($student_class_list)
		{
			foreach ($student_class_list as $key) {
				$student_class_list_arr[$key->class_name] = $key->class_name;
			}
		}
		Template::set('student_category_list',$student_category_list_arr);
		Template::set('student_class_list',$student_class_list_arr);

		$id = $this->uri->segment(3);
		if (empty($id))
		{
			Template::set_message(lang('books_invalid_id'), 'error');
			redirect('/books');
		}

		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Books.Content.Edit');

			if ($this->save_books('update', $id))
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('books_act_edit_record').': ' . $id . ' : ' . $this->input->ip_address(), 'books');

				Template::set_message(lang('books_edit_success'), 'success');
			}
			else
			{
				Template::set_message(lang('books_edit_failure') . $this->books_model->error, 'error');
			}
		}
		Template::set('books', $this->books_model->find($id));
		Assets::add_module_js('books', 'books.js');

		Template::set('toolbar_title', lang('books_edit') . ' Books');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function book_detail($book_id)
	{
		$book_details = $this->books_model->find($book_id);
		
		$books_in_category = $this->book_copies_model->get_all_books_in_category($book_id);
		$books_in_category_arr = array();
		if (isset($books_in_category) && is_array($books_in_category) && count($books_in_category))
		{
			foreach ($books_in_category as $book)
				$books_in_category_arr[$book->id] = $book;
		}

		$this->load->model('issue_book/issue_book_model');
		//$books_issue_info = $this->issue_book_model->get_book_issue_info($id);
		$books_in_category_issued = $this->issue_book_model->get_book_category_issue_info($book_id);
		
		$books_in_category_issued_arr = array();
		if (isset($books_in_category_issued) && is_array($books_in_category_issued) && count($books_in_category_issued))
		{
			foreach ($books_in_category_issued as $book)
				$books_in_category_issued_arr[$book->book_copy_id] = $book;
		}
		
		$this->load->model('rack/rack_model');
		$rack_details = $this->rack_model->get_books_rack_shelf_details();
		$rack_detail_arr = array();
		if ($rack_details)
		{
			foreach ($rack_details as $detail)
				$rack_detail_arr[$detail->book_copy_id] = $detail;
		}

		Template::set('rack_detail_arr', $rack_detail_arr);
		Template::set('book_id', $book_id);
		Template::set('book_details', $book_details);
		Template::set('books_in_category_arr', $books_in_category_arr);
		Template::set('books_in_category_issued_arr', $books_in_category_issued_arr);
		Template::set('toolbar_title', lang('book_detail'));
		Template::render();
	}
	public function get_copies_by_book_id($book_id)
	{
		$book_copies = $this->book_copies_model->get_available_book_copies($book_id);
		
		echo json_encode($book_copies);
	}
	
	public function get_copies_by_book_id_for_edit($book_id,$book_copy_id)
	{
		$book_copies = $this->book_copies_model->get_available_book_copies_for_edit($book_id,$book_copy_id);
		
		echo json_encode($book_copies);
	}
	
	public function add_book_quantity($book_id = '')
	{
		if (isset($_POST['add']))
		{
			
			$this->form_validation->set_rules('book_copies_num_of_books','Number of Books','required|trim|max_length[10]');
			if ($this->form_validation->run() === FALSE)
			{
				return FALSE;
			}
			//$this->form_validation->set_rules('book_copies_book_id','Book','required|trim|max_length[100]');
			$num_of_books = $_POST['book_copies_num_of_books'];
			
			if ($this->add_book($num_of_books))
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('book_copies_act_add_record'). ' : ' . $this->input->ip_address(), 'book_copies');

				Template::set_message(lang('books_edit_success'), 'success');
				Template::redirect('/books');
			}
			else
			{
				Template::set_message(lang('books_edit_failure') . $this->books_model->error, 'error');
			}
		}
		
		$books = $this->books_model->find_all();
		$books_dropdown_values = array(''=>'Select Book');
		foreach ($books as $book)
			$books_dropdown_values[$book->id] = $book->title;
			
		$max_book_id = $this->book_copies_model->get_max_book_id();
		
		Template::set('max_book_id', $max_book_id);
		Template::set('books_dropdown_values', $books_dropdown_values);
		Template::set('book_id', $book_id);
		Template::render();
	}
	
	public function add_book($num_of_books)
	{
		$data = array();
		for ($i=0; $i<$num_of_books; $i++)
		{
			$temp = array();
			
			$temp['book_id']	= $_POST['book_copies_book_id'];
			$temp['book_uid']	= $_POST['book_copies_book_uid'][$i];
			$temp['purchase_date']	= $_POST['book_copies_purchase_date'][$i];
			$temp['purchase_by']	= $_POST['book_copies_purchase_by'][$i];
			$temp['donated']	= $_POST['book_copies_donated'][$i];
			$temp['price']	= $_POST['book_price'][$i];
			array_push($data, $temp);
		}
		
		return $this->book_copies_model->add_book_quantity($data);
		
	}
	
	//used in create_full to add book copies and adding them to rack
	public function add_book_copies_rack($book_id,$num_of_books)
	{
		$data = array();
		$data_rack = array();
		
		for ($i=0; $i<$num_of_books; $i++)
		{
			$temp = array();
			$temp_shelf = array();
			
			$temp['book_id']		= $book_id;
			$temp['book_uid']		= $_POST['book_copies_book_uid'][$i];
			$temp['purchase_date']	= $_POST['book_copies_purchase_date'][$i]?$_POST['book_copies_purchase_date'][$i]:'0-0-0';
			$temp['purchase_by']	= $_POST['book_copies_purchase_by'][$i];
			$temp['donated']		= $_POST['book_copies_donated'][$i];
			$temp['price']			= $_POST['book_price'][$i]?$_POST['book_price'][$i]:0;
			array_push($data, $temp);
			
			$temp_shelf['book_copy_id'] =  $_POST['book_copies_book_uid'][$i];
			$temp_shelf['shelf_id'] =  $_POST['shelf_detail_shelf_id'][$i];
			
			
			if(!empty($temp_shelf))
				array_push($data_rack, $temp_shelf);
		}
		
		if($this->book_copies_model->add_book_quantity($data))
		{
			if(!empty($data_rack))
			{
				$this->load->model('rack/shelf_detail_model', null, true);
				return $this->shelf_detail_model->add_multi_book_to_shelf($data_rack);
			}
		}
		else 
		{
			return false;
		}
		
	}
	
	
	public function submit_book($book_id,$issue_id)
	{
		$this->load->model('issue_book/issue_book_model');
		if ($this->issue_book_model->submit_book($issue_id))
		{
			// Log the activity
			$this->activity_model->log_activity($this->current_user->id, lang('issue_book_act_edit_record').': ' . $id . ' : ' . $this->input->ip_address(), 'issue_book');

			Template::set_message('Book Submitted', 'success');
		}
		else
		{
			Template::set_message('Fail to Submit Book' . $this->issue_book_model->error, 'error');
		}
		redirect('books/book_detail/'.$book_id);
	}
	
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	private function save_books($type='insert', $id=0)
	{
		if ($type == 'update') {
			$_POST['id'] = $id;
		}


		$this->form_validation->set_rules('books_isbn','ISBN','trim|max_length[100]');
		$this->form_validation->set_rules('books_title','Title','required|trim|max_length[500]');
		$this->form_validation->set_rules('books_author','Author','trim|max_length[50]');
		$this->form_validation->set_rules('books_publisher','Publisher','trim|max_length[50]');
		$this->form_validation->set_rules('books_year','Year','trim|max_length[50]');
		$this->form_validation->set_rules('books_class_name','Class','trim|max_length[50]');
		$this->form_validation->set_rules('books_category_name','Category','trim|max_length[50]');

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		// make sure we only pass in the fields we want

		$data = array();
		$data['isbn']        = $this->input->post('books_isbn');
		$data['title']        = $this->input->post('books_title');
		$data['author']        = $this->input->post('books_author');
		$data['publisher']        = $this->input->post('books_publisher');
		$data['year']        = date("Y", strtotime($this->input->post('books_year')));
		$data['class_name']        = $this->input->post('books_class_name');
		$data['category_name']        = $this->input->post('books_category_name');

		if ($type == 'insert')
		{
			$id = $this->books_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			} else
			{
				$return = FALSE;
			}
		}
		else if ($type == 'update')
		{
			$return = $this->books_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------
	public function search(){
		$records = array();
		if($_POST){
			$records = $this->books_model->search($_POST);
		}
		Template::set('records',$records);
		Template::render();
	}
}