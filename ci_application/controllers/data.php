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

	//Returns a list of all industries or claim tags that are similar to the passed
	//name
	public function tagList($root) {
		//grab list of industries from query, jsonify and return
		$root = str_replace('%20', ' ', $root);
		$i = 0;
		$tagList = $this->data_model->tagList($root);
		//$data['json'] = '{"Industries": [';
		$data['json'] = '[';
		//filler for empty list
		if(!$tagList) {
			$data['json'] .= '{"name":"empty","id":-1}';
		}
		foreach($tagList as $tag) {
			$data['json'] .= '{"name":"'. $tag['Name'] .'","id":'. $tag['TagsID'] .'}';
			if($i < count($tagList) -1) {
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
		$root = str_replace('%20', ' ', $root);
		$claimList = $this->data_model->claimsByName($root);
		$companyList = $this->data_model->companiesByName($root);
		$tagList = $this->data_model->tagsByName($root);
		$data['json'] = '[';
		$data['json'] .= $this->writeACItems("Claims","claim",$claimList);
		$data['json'] .= ',';
		$data['json'] .= $this->writeACItems("Companies","company",$companyList);
		$data['json'] .= ',';
		$data['json'] .= $this->writeACItems("Tags","tag",$tagList);
		$data['json'] .= ']';
		$this->load->view('data/json_view', $data);
	}

	//Writes out the appropriate SQL fields of the given SQL
	//result as JSON to be returned as 
	private function writeACItems($headerName , $type, $list) {
		$i = 0;
		//add header item
		$result = '{"name":"' . $headerName. '","id":-1,"type":"header","score":-1},';
		if(!$list) {
			$result .= '{"name":"No ' . $headerName . ' found","id":null,"type":"empty","score":"null"}';
		}
		//add each item from SQL
		foreach($list as $item) {
			$result .= '{"name":"'. $item['name'] .'","id":'. $item['id'] 
					.',"type":"' . $type . '","score":"'. $item['score'] . '"}';
			if($i < count($list) -1) {
				$result .= ',';
			}
			$i++;
		}
		return $result;
	}
}
