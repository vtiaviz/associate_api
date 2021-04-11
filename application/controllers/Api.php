<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('api_model');
		$this->load->library('form_validation');
	}

	function index()
	{
		$data = $this->api_model->fetch_all();
		echo json_encode($data->result_array());
	}

	function insert()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('participation', 'Participation', 'required|numeric');
		if($this->form_validation->run())
		{
			$data = array(
				'first_name'	=>	$this->input->post('first_name'),
				'last_name'		=>	$this->input->post('last_name'),
				'participation'	=>	$this->input->post('participation')
			);

			$response = $this->api_model->insert_api($data);
			echo json_encode($response);
		}			
		else
		{
			$response = array(
				'error'					=>	true,
				'first_name_error'		=>	form_error('first_name'),
				'last_name_error'		=>	form_error('last_name'),
				'participation_error'	=>	form_error('participation')
			);
			echo json_encode($response);
		}
	}
	
	function fetch_single()
	{
		if($this->input->post('id'))
		{
			$data = $this->api_model->fetch_single_user($this->input->post('id'));

			foreach($data as $row)
			{
				$output['first_name'] = $row['first_name'];
				$output['last_name'] = $row['last_name'];
				$output['participation'] = $row['participation'];
			}
			echo json_encode($output);
		}
	}

	function update()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required');

		$this->form_validation->set_rules('last_name', 'Last Name', 'required');

		$this->form_validation->set_rules('participation', 'Participation', 'required|numeric');

		if($this->form_validation->run())
		{	
			$data = array(
				'first_name'		=>	$this->input->post('first_name'),
				'last_name'			=>	$this->input->post('last_name'),
				'participation'		=>	$this->input->post('participation')
			);

			$response = $this->api_model->update_api($this->input->post('id'), $data);
			echo json_encode($response);
		}
		else
		{
			$response = array(
				'error'					=>	true,
				'first_name_error'		=>	form_error('first_name'),
				'last_name_error'		=>	form_error('last_name'),
				'participation_error'	=>	form_error('participation')
			);
			echo json_encode($response);
		}
	}

	function delete()
	{
		if($this->input->post('id'))
		{
			$response = $this->api_model->delete_single_user($this->input->post('id'));
			echo json_encode($response);
		}
	}

}


?>