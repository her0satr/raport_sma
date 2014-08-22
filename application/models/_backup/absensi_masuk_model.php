<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class absensi_masuk_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array(
			'id', 'biodata_id', 'tanggal', 'label', 'waktu_01', 'status_01', 'waktu_02', 'status_02', 'waktu_03', 'status_03', 'waktu_04', 'status_04', 'keterangan'
		);
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ABSENSI_MASUK);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ABSENSI_MASUK);
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
				SELECT waktu_masuk.*, biodata.nama biodata_title
				FROM ".ABSENSI_MASUK." waktu_masuk
				LEFT JOIN ".BIODATA." biodata ON biodata.id = waktu_masuk.biodata_id
				WHERE waktu_masuk.id = '".$param['id']."'
				LIMIT 1
			";
		} else if (isset($param['biodata_id']) && isset($param['tanggal'])) {
            $select_query  = "
				SELECT waktu_masuk.*
				FROM ".ABSENSI_MASUK." waktu_masuk
				WHERE
					waktu_masuk.tanggal = '".$param['tanggal']."'
					AND waktu_masuk.biodata_id = '".$param['biodata_id']."'
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
		$param['field_replace']['tanggal_text'] = 'waktu_masuk.tanggal';
		
		$string_tanggal = (isset($param['tanggal'])) ? "AND waktu_masuk.tanggal = '".$param['tanggal']."'" : '';
		$string_biodata = (isset($param['biodata_id'])) ? "AND waktu_masuk.biodata_id = '".$param['biodata_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'tanggal DESC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS waktu_masuk.*, biodata.nama biodata_title
			FROM ".ABSENSI_MASUK." waktu_masuk
			LEFT JOIN ".BIODATA." biodata ON biodata.id = waktu_masuk.biodata_id
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
		if (isset($param['is_query'])) {
			$select_query = "SELECT COUNT(*) TotalRecord FROM ".ABSENSI_MASUK;
		} else {
			$select_query = "SELECT FOUND_ROWS() TotalRecord";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$TotalRecord = $row['TotalRecord'];
		
		return $TotalRecord;
    }
	
	function set_absensi_today($param = array()) {
		$record = array();
		$tanggal = $this->config->item('current_date');
		
		// check record
		$select_query  = "
			SELECT waktu_masuk.*
			FROM ".ABSENSI_MASUK." waktu_masuk
			WHERE waktu_masuk.tanggal = '".$tanggal."'
				AND waktu_masuk.biodata_id = '".$param['biodata_id']."'
			LIMIT 1
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $record = $row;
        }
		
		// insert if user do not have record
		if (count($record) == 0) {
			$param_update = array(
				'tanggal' => $tanggal,
				'biodata_id' => $param['biodata_id']
			);
			$this->update($param_update);
		}
	}
	
    function get_rekap_by_date($param = array()) {
		$array_skpd = array();
		
		// get total pegawai
		$select_query = "
			SELECT
				skpd.id, skpd.title,
				(SELECT COUNT(*) FROM ".BIODATA." WHERE skpd_id = skpd.id) total
			FROM ".SKPD." skpd
			WHERE skpd.id = '".$param['skpd_id']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			// set default value
			$row['masuk'] = 0;
			$row['ijin'] = 0;
			$row['cuti'] = 0;
			$row['sakit'] = 0;
			$row['dl'] = 0;
			$row['dd'] = 0;
			$row['tb'] = 0;
			$row['tk'] = 0;
			$row['mpp'] = 0;
			
			$array_skpd[$row['id']] = $row;
		}
		
		// get absensi masuk
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) masuk
			FROM ".ABSENSI_MASUK." absensi_masuk
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_masuk.biodata_id
			WHERE
				absensi_masuk.tanggal = '".$param['tanggal']."'
				AND biodata.skpd_id = '".$param['skpd_id']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['masuk'] = $row['masuk'];
		}
		
		// get absensi ijin
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) ijin
			FROM ".ABSENSI_KOSONG." absensi_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_kosong.biodata_id
			WHERE
				absensi_kosong.status_kosong = 'Ijin'
				AND biodata.skpd_id = '".$param['skpd_id']."'
				AND absensi_kosong.tanggal = '".$param['tanggal']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['ijin'] = $row['ijin'];
		}
		
		// get absensi cuti
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) cuti
			FROM ".ABSENSI_KOSONG." absensi_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_kosong.biodata_id
			WHERE
				absensi_kosong.status_kosong = 'Cuti'
				AND biodata.skpd_id = '".$param['skpd_id']."'
				AND absensi_kosong.tanggal = '".$param['tanggal']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['cuti'] = $row['cuti'];
		}
		
		// get absensi sakit
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) sakit
			FROM ".ABSENSI_KOSONG." absensi_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_kosong.biodata_id
			WHERE
				absensi_kosong.status_kosong = 'Sakit'
				AND biodata.skpd_id = '".$param['skpd_id']."'
				AND absensi_kosong.tanggal = '".$param['tanggal']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['sakit'] = $row['sakit'];
		}
		
		// get absensi DL
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) dl
			FROM ".ABSENSI_KOSONG." absensi_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_kosong.biodata_id
			WHERE
				absensi_kosong.status_kosong = 'DL'
				AND biodata.skpd_id = '".$param['skpd_id']."'
				AND absensi_kosong.tanggal = '".$param['tanggal']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['dl'] = $row['dl'];
		}
		
		// get absensi DD
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) dd
			FROM ".ABSENSI_KOSONG." absensi_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_kosong.biodata_id
			WHERE
				absensi_kosong.status_kosong = 'DD'
				AND biodata.skpd_id = '".$param['skpd_id']."'
				AND absensi_kosong.tanggal = '".$param['tanggal']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['dd'] = $row['dd'];
		}
		
		// get absensi TB
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) tb
			FROM ".ABSENSI_KOSONG." absensi_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_kosong.biodata_id
			WHERE
				absensi_kosong.status_kosong = 'TB'
				AND biodata.skpd_id = '".$param['skpd_id']."'
				AND absensi_kosong.tanggal = '".$param['tanggal']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['tb'] = $row['tb'];
		}
		
		// get absensi MPP
		$select_query = "
			SELECT biodata.skpd_id, COUNT(*) mpp
			FROM ".ABSENSI_KOSONG." absensi_kosong
			LEFT JOIN ".BIODATA." biodata ON biodata.id = absensi_kosong.biodata_id
			WHERE
				absensi_kosong.status_kosong = 'MPP'
				AND biodata.skpd_id = '".$param['skpd_id']."'
				AND absensi_kosong.tanggal = '".$param['tanggal']."'
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			if (empty($row['skpd_id'])) {
				continue;
			}
			
			$array_skpd[$row['skpd_id']]['mpp'] = $row['mpp'];
		}
		
		// get absensi kosong & tanpa keterangan
		foreach ($array_skpd as $key => $row) {
			$tidak_masuk = $row['total'] - $row['masuk'];
			$tanpa_keterangan = $row['total'] - $row['masuk'] - $row['ijin'] - $row['cuti'] - $row['sakit'] - $row['dl'] - $row['dd'] - $row['tb'] - $row['mpp'];
			$tanpa_keterangan = ($tanpa_keterangan < 0) ? 0 : $tanpa_keterangan;
			
			$array_skpd[$key]['tidak_masuk'] = $tidak_masuk;
			$array_skpd[$key]['tk'] = $tanpa_keterangan;
		}
		
		return $array_skpd;
    }
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".ABSENSI_MASUK." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $param = array()) {
		$row = StripArray($row, array( 'waktu_01', 'waktu_02', 'waktu_03', 'waktu_04' ));
		
		// grid type
		if (isset($param['grid_type']) && $param['grid_type'] == 'absensi_pegawai') {
			if (empty($row['waktu_01'])) {
				$row['waktu_01'] = '<button class="btn btn-xs btn-absensi" data-absensi="waktu_01" data-original-title="Cek Absen"><img src="'.base_url('static/img/icons/icon-clock.png').'" /></button>';
			}
			if (empty($row['waktu_02'])) {
				$row['waktu_02'] = '<button class="btn btn-xs btn-absensi" data-absensi="waktu_02" data-original-title="Cek Absen"><img src="'.base_url('static/img/icons/icon-clock.png').'" /></button>';
			}
			if (empty($row['waktu_03'])) {
				$row['waktu_03'] = '<button class="btn btn-xs btn-absensi" data-absensi="waktu_03" data-original-title="Cek Absen"><img src="'.base_url('static/img/icons/icon-clock.png').'" /></button>';
			}
			if (empty($row['waktu_04'])) {
				$row['waktu_04'] = '<button class="btn btn-xs btn-absensi" data-absensi="waktu_04" data-original-title="Cek Absen"><img src="'.base_url('static/img/icons/icon-clock.png').'" /></button>';
			}
		}
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		
		return $row;
	}
}