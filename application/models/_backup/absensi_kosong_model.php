<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class absensi_kosong_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array(
			'id', 'biodata_id', 'tanggal', 'status_kosong', 'keterangan', 'upload_file'
		);
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ABSENSI_KOSONG);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ABSENSI_KOSONG);
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
				SELECT waktu_kosong.*, biodata.nama biodata_title
				FROM ".ABSENSI_KOSONG." waktu_kosong
				LEFT JOIN ".BIODATA." biodata ON biodata.id = waktu_kosong.biodata_id
				WHERE waktu_kosong.id = '".$param['id']."'
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
		
		$param['field_replace']['biodata_title'] = 'biodata.nama';
		$param['field_replace']['tanggal_text'] = 'waktu_kosong.tanggal';
		
		$string_tanggal = (isset($param['tanggal'])) ? "AND waktu_kosong.tanggal = '".$param['tanggal']."'" : '';
		$string_biodata = (isset($param['biodata_id'])) ? "AND waktu_kosong.biodata_id = '".$param['biodata_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'tanggal DESC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS waktu_kosong.*, biodata.nama biodata_title, biodata.nip
			FROM ".ABSENSI_KOSONG." waktu_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = waktu_kosong.biodata_id
			WHERE 1 $string_tanggal $string_biodata $string_filter
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
	
	function get_no_absence($param = array()) {
        $array = array();
		
		$param['field_replace']['tanggal'] = '';
		$param['field_replace']['biodata_title'] = 'biodata.nama';
		
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'nama DESC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS biodata.*, biodata.nama biodata_title, '".$param['tanggal']."' tanggal
			FROM ".BIODATA." biodata
			WHERE
				biodata.id NOT IN ( SELECT biodata_id FROM ".ABSENSI_MASUK." WHERE tanggal = '".$param['tanggal']."' )
				AND biodata.id NOT IN ( SELECT biodata_id FROM ".ABSENSI_KOSONG." WHERE tanggal = '".$param['tanggal']."' )
				$string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, $param);
		}
		
        return $array;
	}
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".ABSENSI_KOSONG." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		
		if (!empty($row['upload_file'])) {
			$row['link_upload'] = base_url('static/upload/'.$row['upload_file']);
		}
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		
		return $row;
	}
}