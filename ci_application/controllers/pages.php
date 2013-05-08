<?php

class Pages extends CI_Controller {

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
		$data['headTitle'] = 'Database Connect';
		$data['pageTitle'] = 'track';

		//query for all news stories (will pass to view)
		$data['tag_types'] = $this->track_model->get_types();

		$this->load->view('templates/track_header', $data);
		$this->load->view('track/form', $data);
		$this->load->view('templates/track_footer');
	}
}
