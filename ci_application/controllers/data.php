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


	//Simple Method for testing db, returns all users
	//TODO: involve in basic treemap get
	public function index() {
		// Capitalize the first letter
		$data['headTitle'] = 'Testing DB Connection';
		$data['pageTitle'] = '';

		//query for all news stories (will pass to view)
		$data['users'] = $this->data_model->testDB();

		$this->load->view('data/test', $data);
	}

	//Returns a list of all industries that are similar to the passed
	//name
	public function industryList($root) {
		//grab list of industries from query, jsonify and return
		$i = 0;
		$industryList = $this->data_model->industryList($root);
		//$data['json'] = '{"Industries": [';
		$data['json'] = '[';
		foreach($industryList as $industry) {
			$data['json'] .= '{"value":"'. $industry['Name'] .'","label":'. $industry['TagsID'] .'}';
			//$data['json'] .= '"'. $industry['Name'] .'"';
			if($i < count($industryList) -1) {
				$data['json'] .= ',';
			}
			$i++;
		}
		$data['json'] .= ']';
		//$data['json'] .= ']}';
		$this->load->view('data/json_view', $data);
	}
}
