<?php

class raise_grade extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		// array student
		$array_student = $this->student_model->get_array(array( 'limit' => 3 ));
		
		// update grade
		$update_count = array();
		foreach ($array_student as $row) {
			$class_level_next = $this->class_level_model->get_next_level(array( 'id' => $row['class_level_id'] ));
			
			// update student
			if (count($class_level_next) > 0) {
				$update_count[] = $this->student_model->update(array( 'id' => $row['id'], 'class_level_id' => $class_level_next['id'] ));
			}
		}
		
		// result
		$result = array( 'status' => true, 'message' => count($update_count).' siswa berhasil naik kelas secara otomatis.' );
		echo json_encode($result);
	}
}