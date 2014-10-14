<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class grade_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'tahun', 'semester', 'student_id', 'discipline_id', 'uh', 'uts', 'uas' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, GRADE);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, GRADE);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
        return $result;
    }

    function get_by_id($param) {
        $array = array();
		
        if (isset($param['id'])) {
            $select_query  = "
				SELECT grade.*
				FROM ".GRADE." grade
				WHERE grade.id = '".$param['id']."'
				LIMIT 1
			";
		} else if (isset($param['tahun']) && isset($param['semester']) && isset($param['student_id']) && isset($param['discipline_id'])) {
			$select_query  = "
				SELECT grade.*
				FROM ".GRADE." grade
				WHERE
					grade.tahun = '".$param['tahun']."'
					AND grade.semester = '".$param['semester']."'
					AND grade.student_id = '".$param['student_id']."'
					AND grade.discipline_id = '".$param['discipline_id']."'
				LIMIT 1
			";
		}
		
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$param['field_replace']['discipline_title'] = 'discipline.title';
		
		$string_tahun = (isset($param['tahun'])) ? "AND grade.tahun = '".$param['tahun']."'" : '';
		$string_semester = (isset($param['semester'])) ? "AND grade.semester = '".$param['semester']."'" : '';
		$string_student = (isset($param['student_id'])) ? "AND grade.student_id = '".$param['student_id']."'" : '';
		$string_discipline = (isset($param['discipline_id'])) ? "AND grade.discipline_id = '".$param['discipline_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS grade.*, discipline.title discipline_title, student.name student_name
			FROM ".GRADE." grade
			LEFT JOIN ".STUDENT." student ON student.id = grade.student_id
			LEFT JOIN ".DISCIPLINE." discipline ON discipline.id = grade.discipline_id
			WHERE 1 $string_tahun $string_semester $string_student $string_discipline $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, $param);
		}
		
        return $array;
    }

    function get_count($param = array()) {
		$select_query = "SELECT FOUND_ROWS() TotalRecord";
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$TotalRecord = $row['TotalRecord'];
		
		return $TotalRecord;
    }
	
    function get_raport_link($param = array()) {
		$array = array();
		
		$select_query = "
			SELECT grade.tahun, grade.semester, grade.student_id
			FROM ".GRADE." grade
			WHERE student_id = '".$param['student_id']."'
			GROUP BY grade.tahun, grade.semester, grade.student_id
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$row['link'] = base_url('raport/?tahun='.$row['tahun'].'&semester='.$row['semester'].'&student_id='.$row['student_id']);
			$array[] = $row;
		}
		
		return $array;
    }
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".GRADE." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		
		// total
		$row['total'] = 0;
		if (isset($row['uh']) && isset($row['uts']) && isset($row['uas'])) {
			$row['total'] = $row['uh'] + $row['uts'] + $row['uas'];
		}
		
		// generate raport
		$row['raport'] = 0;
		if (isset($row['uh']) && isset($row['uts']) && isset($row['uas'])) {
			$row['raport'] = (($row['uh'] * 2) + $row['uts'] + $row['uas']) / 4;
			$row['raport'] = round($row['raport']);
		}
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		
		return $row;
	}
	
	function initialize_student_grade($param = array()) {
		$array_student = $this->student_model->get_array($param);
		foreach ($array_student as $student) {
			$param_check = $param;
			$param_check['student_id'] = $student['id'];
			unset($param_check['class_level_id']);
			$check = $this->get_by_id($param_check);
			if (count($check) == 0) {
				$param_update = $param_check;
				$this->update($param_update);
			}
		}
	}
}