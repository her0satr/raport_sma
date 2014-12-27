<?php

class ranking extends SYGAAS_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$this->load->view( 'ranking');
	}
	
	function view() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		if ($action == 'get_ranking_grid') {
			$this->load->view( 'ranking_grid');
		}
	}
}