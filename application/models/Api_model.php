<?php
class Api_model extends CI_Model
{
	function fetch_all()
	{
		$this->db->order_by('id', 'DESC');
		return $this->db->get('associate');
	}

	function insert_api($data = null)
	{
		$this->db->trans_begin();
		$this->db->insert('associate', $data);
		if ($this->db->trans_status() === FALSE){
		  $this->db->trans_rollback();
		  return array('error' => true, 'msg' => 'sorry, technical error occurred, please try again later...', 'code' => 400);
		} else {
		  $this->db->trans_commit();
		  return array('success' => true, 'msg' => 'success', 'code' => 200);
		}
	}

	function fetch_single_user($user_id)
	{
		$this->db->where('id', $user_id);
		$query = $this->db->get('associate');
		return $query->result_array();
	}

	function update_api($user_id = null, $data = null)
	{
		$this->db->trans_begin();
		$this->db->where('id', $user_id);
		$this->db->update('associate', $data);
		if ($this->db->trans_status() === FALSE){
		  $this->db->trans_rollback();
		  return array('error' => true, 'msg' => 'sorry, technical error occurred, please try again later...', 'code' => 400);
		} else {
		  $this->db->trans_commit();
		  return array('success' => true, 'msg' => 'success', 'code' => 200);
		}
	}

	function delete_single_user($user_id = null)
	{
		$this->db->trans_begin();
		$this->db->where('id', $user_id);
		$this->db->delete('associate');
		if ($this->db->trans_status() === FALSE){
		  $this->db->trans_rollback();
		  return array('error' => true, 'msg' => 'sorry, technical error occurred, please try again later...', 'code' => 400);
		} else {
		  $this->db->trans_commit();
		  return array('success' => true, 'msg' => 'success', 'code' => 200);
		}
	}
}

?>