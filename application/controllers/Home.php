<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct() {
        parent::__construct();
		// Helper para pegar caminho da URL:
		$this->load->helper('url');
    }

	public function index(){
		$this->load->view('home');
	}
}