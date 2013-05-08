<?php
/*---Fetches Data for pages in the system---*/
class Data extends CI_Controller {

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
		Index Page
	*/
	public function index() {
		// Capitalize the first letter
		$data['headTitle'] = 'Testing DB Connection';
		$data['pageTitle'] = '';

		//query for all news stories (will pass to view)
		$data['users'] = $this->data_model->testDB();

		$this->load->view('data/test', $data);
	}
}
