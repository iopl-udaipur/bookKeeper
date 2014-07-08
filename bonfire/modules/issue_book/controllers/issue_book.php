<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Issue_book extends Authenticated_Controller {

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		//$this->auth->restrict('Issue_book.Content.View');
		$this->load->model('issue_book_model', null, true);
		$this->lang->load('issue_book');
	}

	public function index($filter='issued', $offset=0)
	{
		$this->load->model('books/books_model', null, true);
		$this->load->model('student/student_model', null, true);

		// Deleting anything?
		if (isset($_POST['delete']))
		{
			$checked = $this->input->post('checked');

			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->issue_book_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(count($checked) .' '. lang('issue_book_delete_success'), 'success');
				}
				else
				{
					Template::set_message(lang('issue_book_delete_failure') . $this->issue_book_model->error, 'error');
				}
			}
		}
		
		$where = array();
		$show_deleted = FALSE;
		
		// Filters
		if (preg_match('{student-([A-Za-z0-9]+)}',$filter, $matches))
		{			
			$filter_type = 'student';
			$filter_student_name = $matches[1];
		}
		else
		{
			$filter_type = $filter;
		}
		
		switch($filter_type)
		{
		
			case 'issued':
				$where['issue_book.submit_date'] = null;
				break;
		
			case 'student':
				$query = "select bf_issue_book.*
						from bf_issue_book inner join bf_student
						on bf_issue_book.student_id = bf_student.id
						where bf_student.name like '".$filter_student_name."%';";
				$records = $this->db->query($query)->result();
				//$where['issue_book.student_id'] = $student_id;
				Template::set('filter_student', $filter_student_name);
				break;
		
			case 'submitted':
				$this->db->where('issue_book.submit_date IS NOT NULL', null,false);
				break;
				
			default:
				show_404("issue_book/index/$filter/");
		}
		
		if ($filter_type!='student')
		{
			// Fetch the members to display
			$this->issue_book_model->where($where);
	
			$records = $this->issue_book_model->find_all();
		}
//==================================================================================
		$students = array();
		$books = array();
		foreach ($records as $record)
		{
			if (!in_array($record->student_id, $students))
				array_push($students, $record->student_id);

			if (!in_array($record->book_id, $books))
				array_push($books, $record->book_id);
				
		}
		
		$student_name=array();
		if (!empty($students))
		{
			$this->db->select('id, name');
			$this->db->where_in('id', $students);
			$student_records= $this->db->get('student')->result();
			
			foreach ($student_records as $record)
			{
				$student_name[$record->id]=$record->name;
			}
		}
		
		$book_title=array();
		if (!empty($books))
		{
			$this->db->select('id, title');
			$this->db->where_in('id', $books);
			$book_records= $this->db->get('books')->result();
			
			foreach ($book_records as $record)
			{
				$book_title[$record->id]=$record->title;
			}
		}
		
		$book_uid=array();
		if (!empty($books))
		{
			$this->db->select('id, book_uid');
			
			$book_records= $this->db->get('book_copies')->result();
			
			foreach ($book_records as $record)
			{
				$book_uid[$record->id]=$record->id;
			}
		}
//======================================================================================

		Template::set('records', $records);
		Template::set('student_name', $student_name);
		Template::set('book_title', $book_title);
		Template::set('book_uid', $book_uid);
		Template::set('filter_type', $filter_type);
		Template::set('toolbar_title', 'Manage Books Issued');
		Template::render();
	}

	public function create()
	{
		if ($this->input->post('save'))
		{
			/*var_dump('inside');
			var_dump($_POST);
			die();*/
			if ($insert_id = $this->save_issue_book())
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('issue_book_act_create_record').': ' . $insert_id . ' : ' . $this->input->ip_address(), 'issue_book');

				Template::set_message(lang('issue_book_create_success'), 'success');
				Template::redirect('/issue_book/create');
			}
			else
			{
				Template::set_message(lang('issue_book_create_failure') . $this->issue_book_model->error, 'error');
			}
		}
		Assets::add_module_js('issue_book', 'issue_book.js');
		
		$this->load->model('books/books_model');
		$books = $this->books_model->get_available_books();
		
		$this->load->model('student/student_model');
		$students = $this->student_model->find_all();
		
		$books_dropdown_values = array(''=>'Select Book');
		foreach ($books as $book)
			$books_dropdown_values[$book->id] = $book->title;
			
		$students_dropdown_values = array(''=>'Select Student');
		foreach ($students as $student)
			$students_dropdown_values[$student->id] = $student->name;

		Template::set('books_dropdown_values', $books_dropdown_values);
		Template::set('students_dropdown_values', $students_dropdown_values);
		Template::set('toolbar_title', lang('issue_book_create') . ' Issue');
		Template::render();
	}

	public function edit()
	{
		$id = $this->uri->segment(3);
		if (empty($id))
		{
			Template::set_message(lang('issue_book_invalid_id'), 'error');
			redirect(SITE_AREA .'/issue_book');
		}

		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Issue_book.Content.Edit');

			if ($this->save_issue_book('update', $id))
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('issue_book_act_edit_record').': ' . $id . ' : ' . $this->input->ip_address(), 'issue_book');

				Template::set_message(lang('issue_book_edit_success'), 'success');
				Template::redirect('/issue_book');
			}
			else
			{
				Template::set_message(lang('issue_book_edit_failure') . $this->issue_book_model->error, 'error');
			}
		}
		else if (isset($_POST['delete']))
		{
			//$this->auth->restrict('Issue_book.Content.Delete');
			if ($this->issue_book_model->delete($id))
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('issue_book_act_delete_record').': ' . $id . ' : ' . $this->input->ip_address(), 'issue_book');

				Template::set_message(lang('issue_book_delete_success'), 'success');

				redirect('/issue_book');
			} else
			{
				Template::set_message(lang('issue_book_delete_failure') . $this->issue_book_model->error, 'error');
			}
		}
		$issue_book = $this->issue_book_model->find($id);
		Template::set('issue_book', $issue_book);
		Assets::add_module_js('issue_book', 'issue_book.js');
//--------------------------------------------------------------------------------------
		$this->load->model('books/books_model');
		$books = $this->books_model->get_available_books_for_edit($issue_book->book_id);
		
		$books_dropdown_values = array(''=>'Select Book');
		foreach ($books as $book)
			$books_dropdown_values[$book->id] = $book->title;
//--------------------------------------------------------------------------------------		
		$this->load->model('books/book_copies_model');
		$book_copies = $this->book_copies_model->get_available_book_copies_for_edit($issue_book->book_id,$issue_book->book_copy_id);
		
		$book_copies_dropdown_values = array(''=>'Select UID');
		foreach ($book_copies as $copy)
			$book_copies_dropdown_values[$copy->id] = $copy->id;
//--------------------------------------------------------------------------------------
		$this->load->model('student/student_model');
		$students = $this->student_model->find_all();
		
		$students_dropdown_values = array(''=>'Select Student');
		foreach ($students as $student)
			$students_dropdown_values[$student->id] = $student->name;

		Template::set('books_dropdown_values', $books_dropdown_values);
		Template::set('book_copies_dropdown_values', $book_copies_dropdown_values);
		Template::set('students_dropdown_values', $students_dropdown_values);
		
		Template::set('toolbar_title', lang('issue_book_edit') . ' Book Issued');
		Template::render();
	}
	
	public function submitbook($id)
	{
		if ($this->submit_book($id))
		{
			// Log the activity
			$this->activity_model->log_activity($this->current_user->id, lang('issue_book_act_edit_record').': ' . $id . ' : ' . $this->input->ip_address(), 'issue_book');

			Template::set_message('Book Submitted', 'success');
		}
		else
		{
			Template::set_message('Fail to Submit Book' . $this->issue_book_model->error, 'error');
		}
		redirect('/issue_book');
	}
	
	public function submit_book($id)
	{
		return $this->issue_book_model->submit_book($id);
	}
	
	
	public function issue_book_by_book_detail($book_id,$book_copy_id)
	{
		if ($this->input->post('save'))
		{
			if ($insert_id = $this->save_issue_book())
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('issue_book_act_create_record').': ' . $insert_id . ' : ' . $this->input->ip_address(), 'issue_book');

				Template::set_message(lang('issue_book_create_success'), 'success');
			}
			else
			{
				Template::set_message(lang('issue_book_create_failure') . $this->issue_book_model->error, 'error');
			}
			redirect('books/book_detail/'.$book_id);
		}
		
		$this->load->model('books/books_model');
		$book = $this->books_model->find($book_id);
		$this->load->model('books/book_copies_model');
		$book_copy = $this->book_copies_model->find($book_copy_id);
		
		$this->load->model('student/student_model');
		$students = $this->student_model->find_all();
		
		$students_dropdown_values = array(''=>'Select Student');
		foreach ($students as $student)
			$students_dropdown_values[$student->id] = $student->name;

		Template::set('book', $book);
		Template::set('book_id', $book_id);
		Template::set('book_copy', $book_copy);
		Template::set('students_dropdown_values', $students_dropdown_values);
		Template::set('toolbar_title', lang('issue_book_create') . ' Issue');
		Template::render();
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	private function save_issue_book($type='insert', $id=0)
	{
		if ($type == 'update') {
			$_POST['id'] = $id;
		}

			//var_dump($_POST);
			//die();
		if ($type != 'submit_book') {
			$this->form_validation->set_rules('issue_book_book_id','Book','required|trim|max_length[20]');
			$this->form_validation->set_rules('issue_book_book_copy_id','Book UID','required|trim|max_length[20]');
			$this->form_validation->set_rules('issue_book_student_id','Student','required|trim|max_length[20]');
			$this->form_validation->set_rules('issue_book_issue_date','Issue Date','required|trim|max_length[50]');
			$this->form_validation->set_rules('issue_book_return_date','Return Date','required|trim|max_length[50]');
			
			if ($this->form_validation->run() === FALSE)
			{
				return FALSE;
			}
		}



		// make sure we only pass in the fields we want

		$data = array();

		$data['book_id']		= $this->input->post('issue_book_book_id');
		$data['book_copy_id']	= $this->input->post('issue_book_book_copy_id');
		$data['student_id']		= $this->input->post('issue_book_student_id');
		$data['issue_date']		= date("Y-m-d", strtotime($this->input->post('issue_book_issue_date')));
		$data['return_date']	= date("Y-m-d", strtotime($this->input->post('issue_book_return_date')));

		if ($type == 'insert')
		{
			$id = $this->issue_book_model->insert($data);

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
			$return = $this->issue_book_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------

}