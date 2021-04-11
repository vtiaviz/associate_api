<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('api_model');
		$this->load->library('form_validation');
	}

	function index()
	{
		$this->load->view('api_view');
	}

	function action()
	{
		if($this->input->post('data_action'))
		{
			$data_action = $this->input->post('data_action');

			if($data_action == "Delete")
			{
				$api_url = "https://apivti.000webhostapp.com/api/delete";

				$form_data = array(
					'id'		=>	$this->input->post('user_id')
				);

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_POST, true);

				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				echo $response;

			}

			if($data_action == "Edit")
			{
				/* Validação de Limite de Participação */

				$single = $this->api_model->fetch_single_user($this->input->post('user_id'));
				$edit = $single[0]['participation'];	//participação atual

				$data = $this->api_model->fetch_all();
				$result = $data->result_array();
				$soma = 0;
				foreach ($result as $i => $value) {
					$soma += (int)$value['participation'];
				}
				$part = (int)$this->input->post('participation');	//nova participação
				$prod = $soma - $edit;
				$total = 100 - $prod;	//participação disponivel

				/* Validação de Limite de Participação */

				if ($part <= $total) {		//se a nova participação for menor ou igual a participação disponivel esta aprovado
					$api_url = "https://apivti.000webhostapp.com/api/update";

					$form_data = array(
						'first_name'		=>	$this->input->post('first_name'),
						'last_name'			=>	$this->input->post('last_name'),
						'participation'		=>	$this->input->post('participation'),
						'id'				=>	$this->input->post('user_id')
					);

					$client = curl_init($api_url);

					curl_setopt($client, CURLOPT_POST, true);

					curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

					curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

					$response = curl_exec($client);

					curl_close($client);

					echo $response;
				} 
				else {
					$array = array(
						'error'		=>	true,
						'participation_error'	=>	"<div style='color:red; display: flex; justify-content: center; align-items: center; '>Limite de participação atingido</div>"
					);
					echo json_encode($array);
				}

			}

			if($data_action == "fetch_single")
			{
				$api_url = "https://apivti.000webhostapp.com/api/fetch_single";

				$form_data = array(
					'id'		=>	$this->input->post('user_id')
				);

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_POST, true);

				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				echo $response;

			}

			if($data_action == "Insert")
			{
				/* Valida de Limite de Participação */

				$data = $this->api_model->fetch_all();
				$result = $data->result_array();
				$soma = 0;
				foreach ($result as $i => $value) {
					$soma += (int)$value['participation'];
				}
				$part = (int)$this->input->post('participation');	// participação
				$total = 100 - $soma;							// participação disponivel

				/* Valida de Limite de Participação */

				if ($part != null && $part <= $total) {

					$api_url = "https://apivti.000webhostapp.com/api/insert";

					$form_data = array(
						'first_name'		=>	$this->input->post('first_name'),
						'last_name'			=>	$this->input->post('last_name'),
						'participation'		=>	$this->input->post('participation')
					);

					$client = curl_init($api_url);

					curl_setopt($client, CURLOPT_POST, true);

					curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

					curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

					$response = curl_exec($client);

					curl_close($client);

					echo $response;
				} 
				else {
					$array = array(
						'error'		=>	true,
						'participation_error'	=>	"<div style='color:red; display: flex; justify-content: center; align-items: center; '>Limite de participação atingido</div>"
					);
					echo json_encode($array);
				}

			}

			if($data_action == "fetch_all")
			{
				$api_url = "https://apivti.000webhostapp.com/api";

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				$result = json_decode($response);

				$output = '';

				if($result != null)
				{
					$i =1;
					foreach($result as $row)
					{
						$output .= '
						<tr>
							<td>'.$i++.'</td>
							<td>'.$row->first_name.'</td>
							<td>'.$row->last_name.'</td>
							<td>'.$row->participation.'% </td>
							<td><div class="buttons"> <button type="button" name="edit" class="btn btn-primary edit" id="'.$row->id.'"><i class="fas fa-edit"></i></button><button type="button" name="delete" class="btn btn-danger delete" id="'.$row->id.'"><i class="fas fa-trash"></button></div></td>
						</tr>

						';
					}
				}
				else
				{
					$output .= '
					<tr>
						<td colspan="4" align="center">No Data Found</td>
					</tr>
					';
				}

				echo json_encode($output);
				
			}

			if($data_action == "fetch_parts")
			{
				$api_url = "https://apivti.000webhostapp.com/api";

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				$result = json_decode($response);

				if($response)
				{
					foreach ($result as $row) {
						$output[] = array(
							'value' => floatval($row->participation),
							'label' => $row->first_name
						);
                	}
				}
				echo json_encode($output);
			}
		}
	}
	
}

?>