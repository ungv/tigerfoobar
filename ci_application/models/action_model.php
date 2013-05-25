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

    //Upvotes the relationship between the passed tag and company using
    //the user id who sent it, or deletes the vote the user previously made 
    //depending on the boolean value post (voted)
    //Returns: false if the tag id is corrupt or if the query failed, 
    //true otherwise
    public function upvoteIndustry($userid) {
        $tagid = $this->security->xss_clean($this->input->post('industryID'));
        $objectid = $this->security->xss_clean($this->input->post('objectID'));
        $tagtype = $this->security->xss_clean($this->input->post('tagType'));
        $voted = $this->security->xss_clean($this->input->post('voted'));

        //check tagid is for industry
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $isVotable = $this->db->get_where('Tags', array('TagsID' => $tagid , 'Type' => $tagtype));
        if(!$isVotable) {  //if not, return false
            return false;
        }else {             //else, execute query
            //choose tags or industries
            $data;
            $table;
            if($tagtype == 'Industry') {
                $table = 'Company_has_Tags';
                $data = array(
                   'Company_CompanyID' => $objectid ,
                   'Tags_TagsID' => $tagid ,
                   'User_ID' => $userid
                );
            }else {
                $table = 'Claim_has_Tags';
                $data = array(
                   'Claim_ClaimID' => $objectid ,
                   'Tags_TagsID' => $tagid ,
                   'User_ID' => $userid
                );
            }
            if(!$voted) {    //user hasnt voted, insert row
                $result = $this->db->insert($table, $data); 
            }else {         //user has voted, remove row
                $result = $this->db->delete($table, $data);
            }
            return $result;
        }
    }

    //adds user to the system
    public function addUser() {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $email = $this->security->xss_clean($this->input->post('email'));

        // Check to see if username already exists
        $query = $this->db->get_where('User', array('Name' => $username));
        // If username is not already taken, add user
        if ($query->num_rows() == 0) {
            $data = array(
                'Name' => $username,
                'Password' => $password,
                'Email' => $email
                );
            $result = $this->db->insert('User', $data);
            return true;
        }
        // else return false, username already exists
        return false;
    }

    //Updates the user profile with new information
    public function updateProfile($userid) {
        $col = $this->security->xss_clean($this->input->post('col'));
        $newInfo = $this->security->xss_clean($this->input->post('newInfo'));

        //2. Check for user in db
        $query = $this->db->get_where('User', array('UserID' => $userid));
        //3. Verify row exists
        if($query->num_rows() == 1)
        {
            // If user exists, then update
            $data = array(
                    $col => $newInfo
                    );
            $where = "UserID = " . $userid;
            $result = $this->db->update('User', $data, $where);
            return true;
        }
        // else return false, no user found or pw incorrect
        return false;
    }

    //Drops the user profile from database
    public function dropAccount($userid) {
        $password = $this->security->xss_clean($this->input->post('password'));

        //2. Check for user in db and for correct password
        $query = $this->db->get_where('User', array('UserID' => $userid, 'Password' => $password));
        //3. Verify row exists
        if($query->num_rows() == 1)
        {
            // If user exists, then delete
            $data = array(
                    'UserID' => $userid,
                    'Password' => $password
                    );
            $result = $this->db->delete('User', $data);
            return true;
        }
        // else return false, no user found or pw incorrect
        return false;
    }
}