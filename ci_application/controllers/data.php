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
	public function tagList($root) {
		//grab list of industries from query, jsonify and return
		$i = 0;
		$industryList = $this->data_model->tagList($root);
		//$data['json'] = '{"Industries": [';
		$data['json'] = '[';
		foreach($industryList as $industry) {
			$data['json'] .= '{"value":"'. $industry['Name'] .'","label":'. $industry['TagsID'] .'}';
			if($i < count($industryList) -1) {
				$data['json'] .= ',';
			}
			$i++;
		}
		$data['json'] .= ']';
		$this->load->view('data/json_view', $data);
	}

	/*----------------------Search Related-------------------------*/

	//Returns a list of companies, claims, and tags relavent to the
	//root term
	public function searchAutocomplete($root) {
		$i = 0;
		$companyList = $this->data_model->companiesByName($root);
		$claimList = $this->data_model->claimsByName($root);
		//$data['json'] = '{"Companies": ['; !!three times
		$data['json'] = '[';
		foreach($companyList as $company) {
			$data['json'] .= '{"value":"'. $company['Name'] .'","label":'. $company['CompanyID'] .'}';
			//$data['json'] .= '"'. $industry['Name'] .'"';
			if($i < count($companyList) -1) {
				$data['json'] .= ',';
			}
			$i++;
		}
		$data['json'] .= ',';
		$i = 0;
		//TODO: super redundant, fix
		foreach($claimList as $claim) {
			$data['json'] .= '{"value":"'. $claim['Title'] .'","label":'. $claim['ClaimID'] .'}';
			//$data['json'] .= '"'. $industry['Name'] .'"';
			if($i < count($claimList) -1) {
				$data['json'] .= ',';
			}
			$i++;
		}
		$data['json'] .= ']';
		$this->load->view('data/json_view', $data);
	}
}
