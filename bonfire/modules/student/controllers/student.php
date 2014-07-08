<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class student extends Authenticated_Controller {

	//--------------------------------------------------------------------


	public function __construct()
	{
		parent::__construct();

		//$this->auth->restrict('Student.Content.View');
		$this->load->model('student_model', null, true);
		$this->lang->load('student');
	}

	public function index()
	{
		$this->student_model->where('deleted',0);
		$records = $this->student_model->find_all();
		
		$this->load->model('issue_book/issue_book_model', null, true);
		$issue_record = $this->issue_book_model->get_issued_group_by_students();

		$book_count = array();
		
		if($issue_record)
		{
			foreach ($issue_record as $record)
				$book_count[$record->student_id] = $record->num_of_books;
		}
		Template::set('records', $records);
		Template::set('book_count', $book_count);
		Template::set('toolbar_title', 'Manage Student');
		Template::render();
	}
	
	public function delete($id)
	{
		$this->load->model('issue_book/issue_book_model', null, true);
		$this->issue_book_model->where('submit_date IS NULL');
		$this->issue_book_model->where('student_id',$id);
		if($this->issue_book_model->find_all())
		{
			Template::set_message('Student is issued some book(s)', 'error');
		}
		else 
		{
			$result = $this->student_model->delete($id);
			if ($result)
			{
				Template::set_message(lang('student_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('student_delete_failure') . $this->student_model->error, 'error');
			}
		}
		
		redirect('student/index');
	}

	public function create()
	{
		if ($this->input->post('save'))
		{
			if ($insert_id = $this->save_student())
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('student_act_create_record').': ' . $insert_id . ' : ' . $this->input->ip_address(), 'student');

				Template::set_message(lang('student_create_success'), 'success');
				Template::redirect('/student');
			}
			else
			{
				Template::set_message(lang('student_create_failure') . $this->student_model->error, 'error');
			}
		}
		Assets::add_module_js('student', 'student.js');

		Template::set('toolbar_title', lang('student_create') . ' Student');
		Template::render();
	}

	public function edit()
	{
		$id = $this->uri->segment(3);
		if (empty($id))
		{
			Template::set_message(lang('student_invalid_id'), 'error');
			redirect('/student');
		}

		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Student.Content.Edit');

			if ($this->save_student('update', $id))
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('student_act_edit_record').': ' . $id . ' : ' . $this->input->ip_address(), 'student');

				Template::set_message(lang('student_edit_success'), 'success');
			}
			else
			{
				Template::set_message(lang('student_edit_failure') . $this->student_model->error, 'error');
			}
		}
		
		Template::set('student', $this->student_model->find($id));
		Assets::add_module_js('student', 'student.js');

		Template::set('toolbar_title', lang('student_edit') . ' Student');
		Template::render();
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	private function save_student($type='insert', $id=0)
	{
		if ($type == 'update') {
			$_POST['id'] = $id;
		}


		$this->form_validation->set_rules('student_name','Name','required|trim|max_length[100]');
		$this->form_validation->set_rules('student_address','Address','required|trim|max_length[500]');
		$this->form_validation->set_rules('student_course','Course','required|trim|max_length[50]');
		$this->form_validation->set_rules('student_batch','Batch','trim|max_length[50]');

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		// make sure we only pass in the fields we want

		$data = array();
		$data['name']        = $this->input->post('student_name');
		$data['address']        = $this->input->post('student_address');
		$data['course']        = $this->input->post('student_course');
		$data['batch']        = $this->input->post('student_batch');

		if ($type == 'insert')
		{
			$id = $this->student_model->insert($data);

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
			$return = $this->student_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------

	public function student_detail($id)
	{
		$student_record = $this->student_model->find($id);

		$this->load->model('issue_book/issue_book_model');
		$books_issued = $this->issue_book_model->get_issued_book_by_std_id($id);
		
		Template::set('student_record', $student_record);
		Template::set('books_issued', $books_issued);
		Template::set('toolbar_title', lang('student_detail'));
		Template::render();
	}
	
	public function submit_book($student_id,$issue_id)
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
		redirect('student/student_detail/'.$student_id);
	}
	public function search(){
		$records = array();
		if($_POST){
			$records = $this->student_model->search($_POST);
		}
		Template::set('records',$records);
		Template::render();
	}
}