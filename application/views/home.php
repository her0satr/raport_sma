<?php
	// user
	$user = $this->user_model->get_session();
	
	// page info
	$view_type = (empty($this->uri->segments[2])) ? 'home' : $this->uri->segments[2];
?>
<?php $this->load->view( 'common/meta', array( 'title' => 'Selamat Datang' ) ); ?>

<body>
<?php $this->load->view( 'common/header'); ?>

<style>
.well h2 { padding: 0px; margin: 0px; line-height: 20px; }
.well h2 span { font-size: 20px; }

#main-menu .bg-info { padding: 15px 0 0 0; }
#main-menu .bg-info a { background: none; }
#main-menu .bg-info a img { margin: 0 0 10px 0; }
</style>

<div class="content enlarged">
	<?php $this->load->view( 'common/sidebar'); ?>
	
  	<div class="mainbar">
	    <div class="page-head">
			<h2 class="pull-left">Selamat Datang</h2>
			<div class="clearfix"></div>
		</div>
		
	    <div class="matter"><div class="container">
            <div class="row"><div class="col-md-12">
				
				<div class="widget" id="main-menu">
					<ul class="info-blocks">
						<li class="bg-info cnt-administrasi-kepegawaian">
							<a href="<?php echo base_url('home/pegawai'); ?>"><img src="<?php echo base_url('static/img/icons/icon-pegawai.png'); ?>" /></a>
							<span class="bottom-info bg-primary">
								<a href="<?php echo base_url('home/pegawai'); ?>">Administrasi<br />Kepegawaian</a>
							</span>
						</li>
						<li class="bg-info cnt-administrasi-kepegawaian">
							<a href="<?php echo base_url('home/pegawai'); ?>"><img src="<?php echo base_url('static/img/icons/icon-pegawai.png'); ?>" /></a>
							<span class="bottom-info bg-primary">
								<a href="<?php echo base_url('home/pegawai'); ?>">Administrasi<br />Kepegawaian</a>
							</span>
						</li>
					</ul>
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
	var page = {
		init: function() {
			var temp = $('.cnt-data').html();
			eval('var data = ' + temp);
			page.data = data;
		}
	}
	page.init();
});
</script>
</body>
</html>