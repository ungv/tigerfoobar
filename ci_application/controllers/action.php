<?php
/*---Performs DB_Manipulation Actions in the system---*/

class Action extends CI_Controller {

	/*
		Constructor
		-Loads Models and helpers
		-Load Generic Data files
	*/
	public function __construct() {
		parent::__construct();
		//Helpers
		$this->load->helper('url');
		$this->load->library('session');
		//Other Example Loads
		$this->load->model('action_model');
	}


	/*
		Login Using User passed data
	*/
	public function login() {
		$result = $this->action_model->login();
        if($result){ 	//if logged in, return json user info
        	$id = $this->session->userdata('userid');
        	$name = $this->session->userdata('username');
        	$email = $this->session->userdata('email');
            $data['json'] = '{"userid":'. $id .',"username":"'. $name .'","useremail":"'.$email.'"}';  
        }else{			//else return error
        	$this->output->set_status_header('400'); //Triggers the jQuery error callback
        	$data['json'] = '{"error":"Incorrect username or password"}';
        }   
        	$this->load->view('data/json_view', $data); 
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}

	/*
		Upvote Industry tag on company page, or remove the current vote
		for that user
		NOTE: Send positive message even if user has already upvoted, unless tag isnt found
	*/
	public function upvoteIndustry() {
		if(!$this->is_logged_in()) {
			$this->output->set_status_header('400');
        	$data['json'] = '{"message":"Not Logged In"}'; 
		}else {
			$userid = $this->session->userdata('userid');
			$result = $this->action_model->upvoteIndustry($userid);
			if($result) {	//database updated db, send success method
				$data['json'] = '{"message":"Successfully contacted server method!"}';
			}else {			//didn't work, inform user why
	        	$this->output->set_status_header('400');
	        	$data['json'] = '{"message":"Cannot Process Upvote"}'; 
			}
		}
		$this->load->view('data/json_view', $data);
	}

	/*
		Upvote Industry tag on company page, or remove the current vote
		for that user
		NOTE: Send positive message even if user has already upvoted, unless tag isnt found
	*/
	public function voteComment() {
		if(!$this->is_logged_in()) {
			$this->output->set_status_header('400');
        	$data['json'] = '{"message":"Not Logged In"}'; 
		}else {
			$userid = $this->session->userdata('userid');
			$result = $this->action_model->voteComment($userid);
			if($result) {	//database updated db, send success method
				$data['json'] = '{"message":"Successfully contacted server method!"}';
			}else {			//didn't work, inform user why
	        	$this->output->set_status_header('400');
	        	$data['json'] = '{"message":"Cannot Process Upvote"}'; 
			}
		}
		$this->load->view('data/json_view', $data);
	}

	/*
		Adds a new user to the database, username/password required, email optional
	*/
	public function addUser() {
		$result = $this->action_model->addUser();
		if($result) {	//database updated db, send success method
			$data['json'] = '{"message":"Successfully contacted server method!"}';
		}else {			//didn't work, inform user why
        	$this->output->set_status_header('400');
        	$data['json'] = '{"message":"Cannot Process Update"}'; 
		}
		$this->load->view('data/json_view', $data);
	}

	/*
		Updates the user profile with new information
	*/
	public function updateProfile() {
		if(!$this->is_logged_in()) {
			$this->output->set_status_header('400');
        	$data['json'] = '{"message":"Not Logged In"}'; 
		}else {
			$userid = $this->session->userdata('userid');
			$result = $this->action_model->updateProfile($userid);
			if($result) {	//database updated db, send success method
				$data['json'] = '{"message":"Successfully contacted server method!"}';
			}else {			//didn't work, inform user why
	        	$this->output->set_status_header('400');
	        	$data['json'] = '{"message":"Cannot Process Update"}'; 
			}
		}
		$this->load->view('data/json_view', $data);
	}

	/*
		Drops the user profile from database
	*/
	public function dropAccount() {
		if(!$this->is_logged_in()) {
			$this->output->set_status_header('400');
        	$data['json'] = '{"message":"Not Logged In"}'; 
		}else {
			$userid = $this->session->userdata('userid');
			$result = $this->action_model->dropAccount($userid);
			if($result) {	//database updated db, send success method
				$data['json'] = '{"message":"Successfully contacted server method!"}';
			}else {			//didn't work, inform user why
	        	$this->output->set_status_header('400');
	        	$data['json'] = '{"message":"Cannot Process Update"}'; 
			}
		}
		$this->load->view('data/json_view', $data);
	}
}
