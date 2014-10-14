<?php
	$array_discipline = $this->discipline_model->get_array();
	$array_class_level = $this->class_level_model->get_array();
?>

<?php $this->load->view( 'common/meta', array( 'title' => 'Penilaian' ) ); ?>

<body>
<?php $this->load->view( 'common/header'); ?>

<div class="content enlarged">
	<?php $this->load->view( 'common/sidebar'); ?>
	
  	<div class="mainbar">
	    <div class="page-head">
			<h2 class="pull-left button-back">Penilaian</h2>
			<div class="clearfix"></div>
		</div>
		
	    <div class="matter"><div class="container">
            <div class="row"><div class="col-md-12">
				
				<div class="widget" id="form-search">
					<div class="widget-head">
						<div class="pull-left">Form Pencarian</div>
						<div class="widget-icons pull-right">
							<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
							<a href="#" class="wclose"><i class="fa fa-times"></i></a>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="widget-content">
						<div class="padd"><form class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-2 control-label">Tahun</label>
								<div class="col-lg-10">
									<select name="tahun" class="form-control">
										<?php echo get_opt_year(); ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Semester</label>
								<div class="col-lg-10">
									<select name="semester" class="form-control">
										<?php echo get_opt_semester(); ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Kelas</label>
								<div class="col-lg-10">
									<select name="class_level_id" class="form-control">
										<?php echo ShowOption(array( 'Array' => $array_class_level )); ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Mata Pelajaran</label>
								<div class="col-lg-10">
									<select name="discipline_id" class="form-control">
										<?php echo ShowOption(array( 'Array' => $array_discipline )); ?>
									</select>
								</div>
							</div>
						</form></div>
					</div>
				</div>
				
				<div class="widget" id="form-entry">
					<input type="hidden" name="action" value="update_grade" />
					
					<div class="widget-head">
						<div class="pull-left">
							<button class="btn btn-info btn-xs btn-save">Simpan</button>
						</div>
						<div class="widget-icons pull-right">
							<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
							<a href="#" class="wclose"><i class="fa fa-times"></i></a>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="widget-content">
						<table class="table table-striped table-bordered table-hover dataTable" id="datatable" aria-describedby="datatable_info">
							<thead>
								<tr role="row">
									<th style="width: 40%;">Nama Siswa</th>
									<th class="center" style="width: 20%;">UH</th>
									<th class="center" style="width: 20%;">UTS</th>
									<th class="center" style="width: 20%;">US</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<div class="widget-foot">
							<br /><br />
							<div class="clearfix"></div> 
						</div>
					</div>
				</div>
				
			</div></div>
        </div></div>
    </div>
	<div class="clearfix"></div>
</div>

<?php $this->load->view( 'common/footer' ); ?>
<?php $this->load->view( 'common/library_js'); ?>

<script>
$(document).ready(function() {
	var dt = null;
	var page = {
		load_grid: function() {
			var is_valid_search = page.is_valid_search();
			if (is_valid_search) {
				var ajax_param = param = Func.form.get_value('form-search');
				ajax_param.action = 'load_class_score';
				
				Func.ajax({ url: web.host + 'score/get_view', param: ajax_param, is_json: false, callback: function(result) {
					$('#datatable tbody').html(result);
				} });
			} else {
				$('#datatable tbody').html('<tr><td colspan="4" class="dataTables_empty">No data available in table</td></tr>');
			}
		},
		is_valid_search: function() {
			var result = false;
			var param = Func.form.get_value('form-search');
			
			if (param.tahun != '' && param.semester != '' && param.class_level_id != '' && param.discipline_id != '') {
				result = true;
			}
			
			return result;
		}
	}
	
	// search
	$('#form-search [name="tahun"], #form-search [name="semester"], #form-search [name="class_level_id"], #form-search [name="discipline_id"]').change(function() {
		page.load_grid();
	});
	$('#form-search form').submit(function(e) {
		e.preventDefault();
		var is_valid_search = page.is_valid_search();
		if (! is_valid_search) {
			noty({ text: 'Silahkan memilih siswa terlebih dahulu', layout: 'topRight', type: 'error', timeout: 1500 });
			return false;
		}
		
		var param = Func.form.get_value('form-search');
		window.open(web.host + 'raport/?tahun=' + param.tahun + '&semester=' + param.semester + '&student_id=' + param.student_id);
	});
	
	// form
	$('#form-entry .btn-save').click(function() {
		Func.form.submit({ url: web.host + 'score/action', param: Func.form.get_value('form-entry') });
	});
});
</script>
</body>
</html>