<?php
	$array_guru = $this->teacher_model->get_array();
?>

<?php $this->load->view( 'common/meta', array( 'title' => 'Wali Kelas' ) ); ?>

<body>
<?php $this->load->view( 'common/header'); ?>

<div class="content enlarged">
	<?php $this->load->view( 'common/sidebar'); ?>
	
  	<div class="mainbar">
	    <div class="page-head">
			<h2 class="pull-left button-back">Wali Kelas</h2>
			<div class="clearfix"></div>
		</div>
		
	    <div class="matter"><div class="container">
            <div class="row"><div class="col-md-12">
				
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
									<th>Tahun</th>
									<th>Semester</th>
									<th>Nama Guru</th>
									<th class="center">Control</th></tr>
							</thead>
							<tbody></tbody>
						</table>
						<div class="widget-foot">
							<br /><br />
							<div class="clearfix"></div> 
						</div>
					</div>
				</div>
				
				<div class="widget hide" id="form-homeroom">
					<div class="widget-head">
						<div class="pull-left">Form Wali Kelas</div>
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
								<label class="col-lg-2 control-label">Guru</label>
								<div class="col-lg-10">
									<select name="teacher_id" class="form-control">
										<?php echo ShowOption(array( 'Array' => $array_guru, 'ArrayTitle' => 'name' )); ?>
									</select>
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
			$('#form-homeroom').hide();
		},
		show_form: function() {
			$('.grid-main').hide();
			$('#form-homeroom').show();
		}
	}
	
	// global
	$('.btn-show-grid').click(function() {
		page.show_grid();
	});
	
	// grid
	var param = {
		id: 'datatable', debug: true,
		source: web.host + 'homeroom/grid',
		column: [ { }, { }, { }, { bSortable: false, sClass: "center" } ],
		callback: function() {
			$('#datatable .btn-edit').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.ajax({ url: web.host + 'homeroom/action', param: { action: 'get_by_id', id: record.id }, callback: function(result) {
					Func.populate({ cnt: '#form-homeroom', record: result });
					page.show_form();
				} });
			});
			
			$('#datatable .btn-delete').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.form.del({
					data: { action: 'delete', id: record.id },
					url: web.host + 'homeroom/action', callback: function() { dt.reload(); }
				});
			});
		}
	}
	dt = Func.datatable(param);
	
	// form agama
	$('.btn-add').click(function() {
		page.show_form();
		$('#form-homeroom form')[0].reset();
		$('#form-homeroom [name="id"]').val(0);
	});
	$('#form-homeroom form').validate({
		rules: {
			tahun: { required: true },
			semester: { required: true },
			homeroom_id: { required: true }
		}
	});
	$('#form-homeroom form').submit(function(e) {
		e.preventDefault();
		if (! $('#form-homeroom form').valid()) {
			return false;
		}
		
		Func.form.submit({
			url: web.host + 'homeroom/action',
			param: Func.form.get_value('form-homeroom'),
			callback: function(result) {
				dt.reload();
				page.show_grid();
				$('#form-homeroom form')[0].reset();
			}
		});
	});
});
</script>
</body>
</html>