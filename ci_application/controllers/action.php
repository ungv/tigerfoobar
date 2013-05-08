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
		//url helper: for public urls
		$this->load->helper('url');
		//Other Example Loads
		$this->load->model('data_model');
	}


	/*
		Add New User
	*/
	public function addUser() {
		// Capitalize the first letter
		//$data['headTitle'] = 'Testing DB Connection';
		//$data['pageTitle'] = '';

		//query for all news stories (will pass to view)
		//$data['users'] = $this->data_model->testDB();

		//$this->load->view('data/test', $data);
	}
}
