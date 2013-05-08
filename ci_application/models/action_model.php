<?php
class Action_model extends CI_Model {

	//called when constructed
	public function __construct() {
		$this->load->database();
	}

	//adds user to the system
	public function addUser() {
		//$query = $this->db->get('User');
		//return $query->result_array();		
	}

}