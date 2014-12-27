<?php
	$array_discipline = $this->discipline_model->get_array();
	$array_class_level = $this->class_level_model->get_array();
?>

<?php $this->load->view( 'common/meta', array( 'title' => 'Ranking' ) ); ?>

<body>
<?php $this->load->view( 'common/header'); ?>

<div class="content enlarged">
	<?php $this->load->view( 'common/sidebar'); ?>
	
  	<div class="mainbar">
	    <div class="page-head">
			<h2 class="pull-left button-back">Ranking</h2>
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
							<input type="hidden" name="action" value="get_ranking_grid" />
							
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
							
							<hr />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-9">
									<button type="submit" class="btn btn-info">Submit</button>
								</div>
							</div>
						</form></div>
					</div>
				</div>
				
				<div class="widget grid-main">
					<div class="widget-head">
						<div class="widget-icons pull-right">
							<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
							<a href="#" class="wclose"><i class="fa fa-times"></i></a>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="widget-content cnt-grid-average">
						<div class="widget-foot">
							<br /><br />
							<div class="clearfix"></div> 
						</div>
					</div>
					<br /><br /><br />
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
		is_valid_search: function() {
			var result = false;
			var param = Func.form.get_value('form-search');
			
			if (param.class_level_id != '') {
				result = true;
			}
			
			return result;
		}
	}
	
	// search
	$('#form-search [name="tahun"], #form-search [name="semester"], #form-search [name="class_level_id"]').change(function() {
		$('#form-search form').submit();
	});
	$('#form-search form').submit(function(e) {
		e.preventDefault();
		var is_valid_search = page.is_valid_search();
		if (! is_valid_search) {
			noty({ text: 'Silahkan memilih kelas terlebih dahulu', layout: 'topRight', type: 'error', timeout: 1500 });
			return false;
		}
		
		Func.ajax({ is_json: false,  url: web.host + 'ranking/view', param: Func.form.get_value('form-search'), callback: function(result) {
			$('.cnt-grid-average').html(result);
			$('#datatable').dataTable({ aaSorting: [[1, 'desc']] });
		} });
	});
});
</script>
</body>
</html>