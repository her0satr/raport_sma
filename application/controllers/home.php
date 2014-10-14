<?php

class home extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$is_login = $this->user_model->is_login();
		
		if ($is_login) {
			$this->load->view( 'home');
		} else {
			$this->load->view( 'login');
		}
	}
	
	function action() {
		// get admin
		$user = $this->user_model->get_by_id(array( 'user_name' => $_POST['user_name'] ));
		if (count($user) > 0) {
			$user['user_type'] = 'admin';
		}
		
		// get teacher
		if (count($user) == 0) {
			$user = $this->teacher_model->get_by_id(array( 'name' => $_POST['user_name'], 'with_password' => 1 ));
			if (count($user) > 0) {
				$user['is_active'] = 1;
				$user['user_pass'] = $user['passwd'];
				$user['user_type'] = 'teacher';
				$user['user_display'] = $user['name'];
			}
		}
		
		// get student
		if (count($user) == 0) {
			$user = $this->student_model->get_by_id(array( 'name' => $_POST['user_name'], 'with_password' => 1 ));
			if (count($user) > 0) {
				$user['is_active'] = 1;
				$user['user_type'] = 'student';
				$user['user_pass'] = $user['passwd'];
				$user['user_display'] = $user['name'];
			}
		}
		
		$result = array( 'success' => false, 'message' => '' );
		if (count($user) == 0) {
			$result['message'] = 'Maaf, user anda tidak ditemukan';
		} else if ($user['is_active'] == 0) {
			$result['message'] = 'Maaf, user anda tidak aktif';
		} else if ($user['user_pass'] == EncriptPassword($_POST['passwd'])) {
			$result['success'] = true;
			$this->user_model->set_session($user);
		} else {
			$result['message'] = 'Maaf, passsword Anda tidak sesuai.';
		}
		
		echo json_encode($result);
		exit;
	}
	
	function logout() {
		$this->user_model->del_session();
		header("Location: ".base_url());
		exit;
	}
}