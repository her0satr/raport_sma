<?php $this->load->view( 'common/meta', array( 'title' => 'Pangkat' ) ); ?>

<body>
<?php $this->load->view( 'common/header'); ?>

<div class="content enlarged">
	<?php $this->load->view( 'common/sidebar'); ?>
	
  	<div class="mainbar">
	    <div class="page-head">
			<h2 class="pull-left button-back">Pangkat</h2>
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
									<th>Pangkat</th>
									<th>Golongan</th>
									<th>Ruang</th>
									<th>Urutan</th>
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
				
				<div class="widget hide" id="form-pangkat">
					<div class="widget-head">
						<div class="pull-left">Form Pangkat</div>
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
								<label class="col-lg-2 control-label">Pangkat</label>
								<div class="col-lg-10">
									<input type="text" name="pangkat" class="form-control" placeholder="Pangkat" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Golongan</label>
								<div class="col-lg-10">
									<input type="text" name="golongan" class="form-control" placeholder="Golongan" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Ruang</label>
								<div class="col-lg-10">
									<input type="text" name="ruang" class="form-control" placeholder="Ruang" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Urutan</label>
								<div class="col-lg-10">
									<input type="text" name="urutan" class="form-control" placeholder="Urutan" />
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
			$('#form-pangkat').hide();
		},
		show_form: function() {
			$('.grid-main').hide();
			$('#form-pangkat').show();
		}
	}
	
	// global
	$('.btn-show-grid').click(function() {
		page.show_grid();
	});
	
	// grid
	var param = {
		id: 'datatable',
		source: web.host + 'master/pangkat/grid',
		column: [ { }, { }, { }, { sClass: "center" }, { bSortable: false, sClass: "center" } ],
		callback: function() {
			$('#datatable .btn-edit').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.ajax({ url: web.host + 'master/pangkat/action', param: { action: 'get_by_id', id: record.id }, callback: function(result) {
					Func.populate({ cnt: '#form-pangkat', record: result });
					page.show_form();
				} });
			});
			
			$('#datatable .btn-delete').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.form.del({
					data: { action: 'delete', id: record.id },
					url: web.host + 'master/pangkat/action', callback: function() { dt.reload(); }
				});
			});
		}
	}
	dt = Func.datatable(param);
	
	// form pangkat
	$('.btn-add').click(function() {
		page.show_form();
		$('#form-pangkat form')[0].reset();
		$('#form-pangkat [name="id"]').val(0);
	});
	$('#form-pangkat form').validate({
		rules: {
			title: { required: true, minlength: 2 }
		}
	});
	$('#form-pangkat form').submit(function(e) {
		e.preventDefault();
		if (! $('#form-pangkat form').valid()) {
			return false;
		}
		
		Func.form.submit({
			url: web.host + 'master/pangkat/action',
			param: Func.form.get_value('form-pangkat'),
			callback: function(result) {
				dt.reload();
				page.show_grid();
				$('#form-pangkat form')[0].reset();
			}
		});
	});
});
</script>
</body>
</html>