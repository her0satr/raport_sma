<?php

class user extends SYGAAS_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$this->load->view( 'user/user');
	}
	
	function grid() {
		$_POST['is_edit'] = 1;
		$_POST['column'] = array( 'email', 'fullname', 'user_type_title', 'is_active' );
		
		$array = $this->user_model->get_array($_POST);
		$count = $this->user_model->get_count();
		$grid = array( 'sEcho' => $_POST['sEcho'], 'aaData' => $array, 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count );
		
		echo json_encode($grid);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// user
		$user = $this->user_model->get_session();
		
		$result = array();
		if ($action == 'update') {
			if (isset($_POST['passwd']) && empty($_POST['passwd'])) {
				unset($_POST['passwd']);
			} else {
				$_POST['passwd'] = EncriptPassword($_POST['passwd']);
			}
			
			$result = $this->user_model->update($_POST);
			
			// check reload
			if (!empty($_POST['thumbnail']) && $user['id'] == $result['id']) {
				$result['page_reload'] = true;
				
				// set session
				$user = $this->user_model->get_by_id(array( 'id' => $result['id'] ));
				$this->user_model->set_session($user);
			}
		} else if ($action == 'get_by_id') {
			$result = $this->user_model->get_by_id(array( 'id' => $_POST['id'] ));
			unset($result['passwd']);
		} else if ($action == 'delete') {
			$result = $this->user_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
}