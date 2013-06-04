<?php
class Root_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //Helpers
        $this->load->helper('url');
		$this->load->helper('captcha');
		$this->load->library('session');
        //checking login
        $data['userid'] = null;
        $this->userid = null;
        $data['isLogged'] = $this->is_logged_in();
        if($data['isLogged']) {
            $data['userid'] = $this->session->userdata('userid');
            $this->userid = $this->session->userdata('userid');
            $data['username'] = $this->session->userdata('username');
        }
        //basic css and js
        $data['csFiles'] = array();
        $data['jsFiles'] = array();
        $this->load->vars($data);
    }

    public function is_logged_in()
    {
        $user = $this->session->userdata('userid');
        //shoudln't have to check this much, 
        //make sure users can clear session array on session end
        return isset($user) && $user != '';
    }
}