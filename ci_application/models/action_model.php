<?php
class Action_model extends CI_Model {

	//called when constructed
	public function __construct() {
		$this->load->database();

        $this->load->library('session');
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

    //Upvotes the relationship between the passed tag and company using
    //the user id who sent it, or deletes the vote the user previously made 
    //depending on the boolean value post (voted)
    //Returns: false if the tag id is corrupt or if the query failed, 
    //true otherwise
    public function upvoteIndustry($userid) {
        $tagid = $this->security->xss_clean($this->input->post('industryID'));
        $companyid = $this->security->xss_clean($this->input->post('companyID'));
        $voted = $this->security->xss_clean($this->input->post('voted'));

        //check tagid is for industry
        $isIndustry = $this->db->get_where('Tags', array('TagsID' => $tagid , 'Type' => 'Industry'));
        if(!$isIndustry) {  //if not, return flase
            return false;
        }else {             //else, execute query
            $data = array(
               'Company_CompanyID' => $companyid ,
               'Tags_TagsID' => $tagid ,
               'User_ID' => $userid
            );
            if(!$voted) {    //user hasnt voted, insert row
                $result = $this->db->insert('Company_has_Tags', $data); 
            }else {         //user has voted, remove row
                $result = $this->db->delete('Company_has_Tags', $data);
            }
            return $result;
        }
    }
}