<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class biodata_detail_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array(
			'id', 'biodata_id', 'jabatan', 'pangkat_id', 'golongan_ruang', 'tmt_pangkat', 'tmt_masa_kerja', 'tmt_tahun', 'tmt_bulan', 'hp', 'email',
			'cpns_no', 'cpns_file', 'pns_no', 'pns_file', 'non_pns_no', 'non_pns_file', 'unit_kerja_id', 'gaji'
		);
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, BIODATA_DETAIL);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, BIODATA_DETAIL);
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
				SELECT biodata_detail.*
				FROM ".BIODATA_DETAIL." biodata_detail
				LEFT JOIN ".BIODATA." biodata ON biodata.id = biodata_detail.biodata_id
				WHERE biodata_detail.id = '".$param['id']."'
				LIMIT 1
			";
        } else if (isset($param['biodata_id'])) {
            $select_query  = "
				SELECT biodata_detail.*, unit_kerja.title unit_kerja_text
				FROM ".BIODATA_DETAIL." biodata_detail
				LEFT JOIN ".BIODATA." biodata ON biodata.id = biodata_detail.biodata_id
				LEFT JOIN ".SKPD." unit_kerja ON unit_kerja.id = biodata_detail.unit_kerja_id
				WHERE biodata_detail.biodata_id = '".$param['biodata_id']."'
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
		$string_sorting = GetStringSorting($param, @$param['column'], 'id DESC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS biodata_detail.*
			FROM ".BIODATA_DETAIL." biodata_detail
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
		$delete_query  = "DELETE FROM ".BIODATA_DETAIL." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $param = array()) {
		$row = StripArray($row, array( 'tanggal_lahir' ));
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		
		return $row;
	}
}