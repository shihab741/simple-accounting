<?php
//session_start();

defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->view('admin/login');
	}

	public function check_login(){
		$email = $this->input->post('email',true);
		$password = $this->input->post('password',true);
		$result = $this->Accounts_model->check_login_info($email, $password);

		$data = array();
		if ($result) {
			$data['id'] = $result->id;
			$data['email'] = $result->email;
			$data['type'] = $result->type;
			$this->session->set_userdata($data);
			redirect('super_admin');		
		} 
		else 
		{
			$data ['exception'] = '<div class="alert alert-danger">E-mail address or password did not match!</div>';
			$this->session->set_userdata($data);
			redirect('accounts');
		}
	}
}