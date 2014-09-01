<?php
	$array_student = $this->student_model->get_array();
	$array_discipline = $this->discipline_model->get_array();
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
								<label class="col-lg-2 control-label">Siswa</label>
								<div class="col-lg-10">
									<select name="student_id" class="form-control">
										<?php echo ShowOption(array( 'Array' => $array_student, 'ArrayTitle' => 'name' )); ?>
									</select>
								</div>
							</div>
							
							<hr />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-9">
									<button type="submit" class="btn btn-info">Cetak</button>
								</div>
							</div>
						</form></div>
					</div>
				</div>
				
				<div class="widget grid-main">
					<div class="widget-head">
						<div class="pull-left">
							<button class="btn btn-info btn-xs btn-add">Tambah</button>
						</div>
						<div class="widget-icons pull-right">
							<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
							<a href="#" class="wclose"><i class="fa fa-times"></i></a>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="widget-content">
						<table id="datatable" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th style="width: 40%;">Mata Pelajaran</th>
									<th style="width: 10%;">UH</th>
									<th style="width: 10%;">UTS</th>
									<th style="width: 10%;">US</th>
									<th style="width: 10%;">Raport</th>
									<th style="width: 20%;">Control</th></tr>
							</thead>
							<tbody></tbody>
						</table>
						<div class="widget-foot">
							<br /><br />
							<div class="clearfix"></div> 
						</div>
					</div>
				</div>
				
				<div class="widget hide" id="form-grade">
					<div class="widget-head">
						<div class="pull-left">Form Penilaian</div>
						<div class="widget-icons pull-right">
							<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
							<a href="#" class="wclose"><i class="fa fa-times"></i></a>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="widget-content">
						<div class="padd"><form class="form-horizontal">
							<input type="hidden" name="action" value="update" />
							<input type="hidden" name="id" value="0" />
							
							<div class="form-group">
								<label class="col-lg-2 control-label">Mata Pelajaran</label>
								<div class="col-lg-10">
									<select name="discipline_id" class="form-control">
										<?php echo ShowOption(array( 'Array' => $array_discipline )); ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">UH</label>
								<div class="col-lg-10">
									<input type="text" name="uh" class="form-control" placeholder="UH" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">UTS</label>
								<div class="col-lg-10">
									<input type="text" name="uts" class="form-control" placeholder="UTS" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">US</label>
								<div class="col-lg-10">
									<input type="text" name="uas" class="form-control" placeholder="US" />
								</div>
							</div>
							
							<hr />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-9">
									<button type="submit" class="btn btn-info">Save</button>
									<button type="button" class="btn btn-info btn-show-grid">Cancel</button>
								</div>
							</div>
						</form></div>
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
		show_grid: function() {
			$('.grid-main').show();
			$('#form-grade').hide();
			$('#form-search [name="tahun"], #form-search [name="semester"], #form-search [name="student_id"]').prop('disabled', false);
		},
		show_form: function() {
			$('.grid-main').hide();
			$('#form-grade').show();
			$('#form-search [name="tahun"], #form-search [name="semester"], #form-search [name="student_id"]').prop('disabled', true);
		},
		is_valid_search: function() {
			var result = false;
			var param = Func.form.get_value('form-search');
			
			if (param.student_id != '') {
				result = true;
			}
			
			return result;
		}
	}
	
	// global
	$('.btn-show-grid').click(function() {
		page.show_grid();
	});
	
	// grid
	var param = {
		id: 'datatable',
		source: web.host + 'grade/grid',
		column: [ { }, { sClass: 'center' }, { sClass: 'center' }, { sClass: 'center' }, { bSortable: false, sClass: 'center' }, { bSortable: false, sClass: "center" } ],
		fnServerParams: function ( aoData ) {
			var param = Func.form.get_value('form-search');
			aoData.push(
				{ "name": "tahun", "value": param.tahun },
				{ "name": "semester", "value": param.semester },
				{ "name": "student_id", "value": param.student_id }
			)
		},
		callback: function() {
			$('#datatable .btn-edit').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.ajax({ url: web.host + 'grade/action', param: { action: 'get_by_id', id: record.id }, callback: function(result) {
					Func.populate({ cnt: '#form-grade', record: result });
					page.show_form();
				} });
			});
			
			$('#datatable .btn-delete').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.form.del({
					data: { action: 'delete', id: record.id },
					url: web.host + 'grade/action', callback: function() { dt.reload(); }
				});
			});
		}
	}
	dt = Func.datatable(param);
	
	// search
	$('#form-search [name="tahun"], #form-search [name="semester"], #form-search [name="student_id"]').change(function() {
		dt.reload();
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
	
	// form grade
	$('.btn-add').click(function() {
		var is_valid_search = page.is_valid_search();
		if (is_valid_search) {
			page.show_form();
			$('#form-grade form')[0].reset();
			$('#form-grade [name="id"]').val(0);
		} else {
			noty({ text: 'Silahkan memilih siswa terlebih dahulu', layout: 'topRight', type: 'error', timeout: 1500 });
		}
	});
	$('#form-grade form').validate({
		rules: {
			discipline_id: { required: true },
			uh: { required: true },
			uts: { required: true },
			uas: { required: true }
		}
	});
	$('#form-grade form').submit(function(e) {
		e.preventDefault();
		if (! $('#form-grade form').valid()) {
			return false;
		}
		
		// collect param
		var param = Func.form.get_value('form-grade');
		var param_search = Func.form.get_value('form-search');
		param.tahun = param_search.tahun;
		param.semester = param_search.semester;
		param.student_id = param_search.student_id;
		
		Func.form.submit({
			url: web.host + 'grade/action',
			param: param,
			callback: function(result) {
				dt.reload();
				page.show_grid();
				$('#form-grade form')[0].reset();
			}
		});
	});
});
</script>
</body>
</html>