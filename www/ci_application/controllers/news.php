<?php
class News extends CI_Controller {

	//calls when created
	public function __construct() {
		parent::__construct();
		$this->load->model('news_model');
		//url, need to be able to view
		$this->load->helper('url');
	}

	//default action
	public function index() {
		//query for all news stories (will pass to view)
		$data['news'] = $this->news_model->get_news();
		//$data['title'] = 'News Archive';

		// Capitalize the first letter
		$data['headerTitle'] = 'News Archive';
		$data['pageTitle'] = 'The News Today';

		$this->load->view('templates/header', $data);
		$this->load->view('news/index', $data);
		$this->load->view('templates/footer');	
	}

	//slug identifies news record to be returned
	public function view($slug) {
		$data['news_item'] = $this->news_model->get_news($slug);

		if (empty($data['news_item'])) {
			show_404();
		}

		// Capitalize the first letter
		$data['headerTitle'] = $data['news_item']['title'];
		$data['pageTitle'] = $data['news_item']['title'];

		$this->load->view('templates/header', $data);
		$this->load->view('news/view', $data);
		$this->load->view('templates/footer');
	}

	//validates and submits new news items
	
	public function create() {
		//grab form helpers
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['headerTitle'] = 'Create Story';
		$data['pageTitle'] = 'Create a News Story';
		
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('text', 'text', 'required');
		
		//if form doesn't validate, ask again
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header', $data);	
			$this->load->view('news/create');
			$this->load->view('templates/footer');
			
		}else { //show success method
			$this->news_model->set_news();
			$this->load->view('news/success');
		}
	}
	
}