<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class status_perkawinan_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'title' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, STATUS_PERKAWINAN);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, STATUS_PERKAWINAN);
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
				SELECT status_perkawinan.*
				FROM ".STATUS_PERKAWINAN." status_perkawinan
				WHERE status_perkawinan.id = '".$param['id']."'
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
		
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS status_perkawinan.*
			FROM ".STATUS_PERKAWINAN." status_perkawinan
			WHERE 1 $string_filter
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
	
    function delete($param) {
        $record_count = 0;
        $select_query = array();
        if (isset($param['id'])) {
            $select_query[] = "SELECT COUNT(*) total FROM ".BIODATA." WHERE status_perkawinan_id = '".$param['id']."'";
        }
        foreach ($select_query as $query) {
            $select_result = mysql_query($query) or die(mysql_error());
            if (false !== $row = mysql_fetch_assoc($select_result)) {
                $record_count += $row['total'];
            }
        }
		if ($record_count > 0) {
            $result['status'] = '0';
            $result['message'] = 'Data tidak bisa dihapus karena sudah terpakai.';
			return $result;
		}
		
		$delete_query  = "DELETE FROM ".STATUS_PERKAWINAN." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		
		return $row;
	}
}