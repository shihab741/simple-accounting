<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	 function __construct()
	 { 
         parent::__construct(); 
         $this->load->library('session'); 
         $this->load->helper('form'); 
      }
	public function index()
	{
		$data['page_title'] = 'Home';
		$this->load->view('staticFrontEnd/under-construction');
	}

	public function page_not_found()
	{
		$this->load->view('staticFrontEnd/under-construction');
	}
}
