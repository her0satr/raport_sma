<?php

class score extends SYGAAS_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$this->load->view( 'score');
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update_grade') {
			foreach ($_POST['id'] as $key => $value) {
				$param_update = array(
					'id' => $value,
					'uh' => $_POST['uh'][$key],
					'uts' => $_POST['uts'][$key],
					'uas' => $_POST['uas'][$key]
				);
				$result = $this->grade_model->update($param_update);
			}
		}
		
		echo json_encode($result);
	}
	
	function get_view() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'load_class_score') {
			$this->load->view( 'score_table');
		}
	}
}