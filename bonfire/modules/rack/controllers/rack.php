<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rack extends Authenticated_Controller {

	//--------------------------------------------------------------------


	public function __construct()
	{
		parent::__construct();

		$this->load->model('rack_model', null, true);
		$this->load->model('shelf_model', null, true);
		$this->lang->load('rack');
	}

	public function index()
	{
		$rack_records = $this->rack_model->find_all();

		$num_of_shelf = $this->shelf_model->get_number_of_shelf_in_each_rack();
		Template::set('rack_records', $rack_records);
		Template::set('num_of_shelf', $num_of_shelf);
		Template::set('toolbar_title', 'Manage Racks');
		Template::render();
	}
	
	public function delete($id)
	{

		if($this->shelf_model->check_if_shelf_exist($id))
		{
			Template::set_message('First delete all Shelf in this Rack', 'error');
		}
		else 
		{
			$result = $this->rack_model->delete($id);
			if ($result)
			{
				Template::set_message(lang('rack_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('rack_delete_failure') . $this->rack_model->error, 'error');
			}
		}
		redirect('rack/index');
	}
	
	
	public function create()
	{
		if ($this->input->post('save'))
		{
			if ($insert_id = $this->save_rack())
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('rack_act_create_record').': ' . $insert_id . ' : ' . $this->input->ip_address(), 'rack');

				Template::set_message(lang('rack_create_success'), 'success');
				Template::redirect('/rack');
			}
			else
			{
				Template::set_message(lang('rack_create_failure') . $this->rack_model->error, 'error');
			}
		}
		Assets::add_module_js('rack', 'rack.js');

		Template::set('toolbar_title', lang('rack_create') . ' Rack');
		Template::render();
	}

	public function add_shelf_to_rack($rack_id = '')
	{
		if (isset($_POST['save']))
		{
			
			$this->form_validation->set_rules('rack_id','Rack','required|max_length[100]');
			$this->form_validation->set_rules('shelf_shelf_number','Shelf Number','required');
			if ($this->form_validation->run() === FALSE)
			{
				return FALSE;
			}
			
			if ($this->add_shelf($this->input->post('rack_id'), $this->input->post('shelf_shelf_number')))
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('rack_act_shelves_add'). ' : ' . $this->input->ip_address(), 'shelf');

				Template::set_message(lang('rack_edit_success'), 'success');
				Template::redirect('/rack');
			}
			else
			{
				Template::set_message(lang('rack_edit_failure') . $this->rack_model->error, 'error');
			}
		}
		
		$rack = $this->rack_model->find($rack_id);
		$max_shelf_number = $this->shelf_model->get_max_shelf_number_by_rack_id($rack_id);
		Template::set('rack', $rack);
		Template::set('max_shelf_number', $max_shelf_number);
		Template::render();
	}
	
	public function add_book_to_shelf($book_copy_id)
	{
		$this->load->model('books/books_model', null, true);
		$this->load->model('books/book_copies_model', null, true);
		$this->load->model('shelf_detail_model', null, true);
		
		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Books.Content.Edit');
			$this->form_validation->set_rules('shelf_detail_rack_id','Rack Number','');
			$this->form_validation->set_rules('shelf_detail_book_id','Shelf Number','');
			$this->form_validation->set_rules('shelf_detail_book_copy_id','Book UID','required|max_length[100]');
			$this->form_validation->set_rules('shelf_detail_shelf_id','Shelf Number','required');
			if ($this->form_validation->run() === TRUE)
			{
				
				$data = array();
				$data['book_copy_id'] =  $this->input->post('shelf_detail_book_copy_id');
				$data['shelf_id'] =  $this->input->post('shelf_detail_shelf_id');
				
				if ($this->shelf_detail_model->add_book_to_shelf($data))
				{
					// Log the activity
					//$this->activity_model->log_activity($this->current_user->id, lang('rack_act_shelves_add'). ' : ' . $this->input->ip_address(), 'shelf');
	
					Template::set_message(lang('rack_edit_success'), 'success');
				}
				else
				{
					Template::set_message(lang('rack_edit_failure') . $this->rack_model->error, 'error');
				}
				
			}
		}
		
		$book_copy = $this->book_copies_model->find($book_copy_id);
		$book = $this->books_model->find($book_copy->book_id);
		
		$rack  = $this->rack_model->find_all();
		$shelf  = $this->shelf_model->find_all();
		
		$shelf_arr = array();
		$rack_dropdown_values = array();
		$rack_dropdown_values[''] = 'Select Rack'; 
		
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
		
		Template::set('book', $book);
		Template::set('book_copy', $book_copy);
		Template::set('rack', $rack);
		Template::set('shelf', $shelf);
		Template::set('rack_dropdown_values', $rack_dropdown_values);
		Template::set('shelf_arr', $shelf_arr);
		Template::render();
	}

	public function edit()
	{
		$id = $this->uri->segment(3);
		if (empty($id))
		{
			Template::set_message(lang('rack_invalid_id'), 'error');
			redirect('/rack');
		}

		if (isset($_POST['save']))
		{
			//$this->auth->restrict('Books.Content.Edit');

			if ($this->edit_rack($id))
			{
				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, lang('rack_act_edit_record').': ' . $id . ' : ' . $this->input->ip_address(), 'rack');

				Template::set_message(lang('rack_edit_success'), 'success');
			}
			else
			{
				Template::set_message(lang('rack_edit_failure') . $this->rack_model->error, 'error');
			}
		}
		Template::set('rack', $this->rack_model->find($id));

		Template::set('toolbar_title', lang('rack_edit') . ' Rack');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function rack_detail($rack_id)
	{
		$rack_details = $this->rack_model->find($rack_id);
		
		$shelfs = $this->shelf_model->get_all_shelfs_by_rack($rack_id);
		$shelfs_arr = array();
		if (isset($shelfs) && is_array($shelfs) && count($shelfs))
		{
			foreach ($shelfs as $shelf)
				$shelfs_arr[$shelf->id] = $shelf;
		}

		$this->load->model('shelf_detail_model');
		$shelf_book_count = $this->shelf_detail_model->get_shelf_book_count($rack_id);
		
		$shelf_book_count_arr = array();
		if (isset($shelf_book_count) && is_array($shelf_book_count) && count($shelf_book_count))
		{
			foreach ($shelf_book_count as $shelf)
				$shelf_book_count_arr[$shelf->shelf_id] = $shelf;
		}
		
		//Template::set('book_id', $book_id);
		Template::set('rack_details', $rack_details);
		Template::set('shelfs_arr', $shelfs_arr);
		Template::set('shelf_book_count_arr', $shelf_book_count_arr);
		Template::set('toolbar_title', lang('rack_detail'));
		Template::render();
	}
	
	
	public function delete_shelf($rack_id,$shelf_id)
	{
		$this->load->model('shelf_detail_model');
		$this->shelf_detail_model->where('shelf_id',$shelf_id);
		if($this->shelf_detail_model->find_all())
		{
			Template::set_message('First remove all Books from the Shelf', 'error');
		}
		else 
		{
			$result = $this->shelf_model->delete($shelf_id);
			if ($result)
			{
				Template::set_message(lang('rack_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('rack_delete_failure') . $this->rack_model->error, 'error');
			}
		}
		redirect('rack/rack_detail/'.$rack_id);
	}
	
	public function shelf_detail($rack_id,$shelf_id)
	{
		$this->load->model('shelf_detail_model');
		
		$rack_details = $this->rack_model->find($rack_id);
		$shelf_details = $this->shelf_model->find($shelf_id);
		
		$books = $this->shelf_detail_model->get_all_books_by_shelf($shelf_id);

		Template::set('rack_details', $rack_details);
		Template::set('shelf_details', $shelf_details);
		Template::set('books', $books);
		Template::set('toolbar_title', lang('shelf_detail'));
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	private function save_rack($type='insert', $id=0)
	{
		$this->form_validation->set_rules('rack_name','Rack Name','required|is_unique[rack.name]|trim|max_length[50]');
		$this->form_validation->set_rules('rack_description','Description','max_length[100]');
		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		// make sure we only pass in the fields we want

		$data = array();
		$data['name']        = $this->input->post('rack_name');
		$data['description']        = $this->input->post('rack_description');

		if ($type == 'insert')
		{
			$this->db->trans_start();
				$id = $this->rack_model->insert($data);
	
				if (is_numeric($id))
				{
					$return = $id;
					if (isset($_POST['shelf_shelf_number']) && is_array($_POST['shelf_shelf_number']) && count($_POST['shelf_shelf_number']))
					{
						if (!$this->add_shelf($id, $this->input->post('shelf_shelf_number')))
						{
							$return = FALSE;
							$this->db->trans_off();
						}
					}
					
				} else
				{
					$return = FALSE;
				}
			$this->db->trans_complete();
		}

		return $return;
	}
	
	public function edit_rack($id)
	{
		//$this->form_validation->set_rules('rack_name','Rack Name','required|trim|max_length[50]');
		$this->form_validation->set_rules('rack_description','Description','max_length[100]');
		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		// make sure we only pass in the fields we want

		$data = array();
		//$data['name']        = $this->input->post('rack_name');
		$data['description']        = $this->input->post('rack_description');
		
		$return = $this->rack_model->update($id, $data);
		return $return;
	}
	
	private function add_shelf($rack_id, $shelf_number)
	{
		$existing_shelf = $this->shelf_model->get_shelf_by_rack($rack_id, $shelf_number);
		if($existing_shelf)
		{
			Template::set_message('Shelf '.$existing_shelf.' already exist on this rack', 'error');
			return FALSE;
		}
		$data = array();
		for ($i=0; $i< count($shelf_number); $i++)
		{
			if($shelf_number[$i]=='' OR !(preg_match('/^[0-9]+$/', $shelf_number[$i])))
			{
				
				Template::set_message('Shelf number is not proper', 'error');
				return FALSE;
			}
			$temp = array();
			
			$temp['rack_id']	= $rack_id;
			$temp['shelf_number']	= $shelf_number[$i];
			array_push($data, $temp);
		}
		
		return $this->shelf_model->add_shelf($data);
	}

	//--------------------------------------------------------------------

}