<?php
class Action_model extends CI_Model {

	//called when constructed
	public function __construct() {
		$this->load->database();
	}

	//sends a login request to the DB object
	public function login() {
        //1. get data from post array
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        
        //2. Check for user in db
        $query = $this->db->get_where('User', array('Name' => $username , 'Password' => $password));
        //3. Verify row exists
        if($query->num_rows() == 1)
        {
            // If user exists, then create session data
            $row = $query->row();
            $data = array(
                    'userid' => $row->UserID,
                    'username' => $row->Name,
                    'email' => $row->Email
                    );
            $this->session->set_userdata($data);
            return true;
        }
        // else return false, no user found or pw incorrect
        return false;
	}


	//adds user to the system
	public function addUser() {
		//$query = $this->db->get('User');
		//return $query->result_array();		
	}

}