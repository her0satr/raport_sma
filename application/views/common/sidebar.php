<?php
	$user = $this->user_model->get_session();
?>

<div class="sidebar" id="cnt-sidebar">
	<div class="sidebar-dropdown"><a href="#">Navigation</a></div>
	
	<form class="navbar-form" role="search">
		<div class="form-group">&nbsp;</div>
	</form>
	
	<ul id="nav">
		<li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i><span>Dashboard</span></a></li>
		<?php if ($user['user_type'] != 'student') { ?>
		<li><a href="<?php echo base_url('teacher'); ?>"><i class="fa fa-users"></i><span>Guru</span></a></li>
		<li><a href="<?php echo base_url('homeroom'); ?>"><i class="fa fa-foursquare"></i><span>Wali Kelas</span></a></li>
		<li><a href="<?php echo base_url('discipline'); ?>"><i class="fa fa-book"></i><span>Mata Pelajaran</span></a></li>
		<li><a href="<?php echo base_url('student'); ?>"><i class="fa fa-male"></i><span>Siswa</span></a></li>
		<li><a href="<?php echo base_url('class_level'); ?>"><i class="fa fa-list-ol"></i><span>Kelas</span></a></li>
		<li><a href="<?php echo base_url('score'); ?>"><i class="fa fa-check"></i><span>Penilaian</span></a></li>
		<li><a href="<?php echo base_url('grade'); ?>"><i class="fa fa-flask"></i><span>Rekap</span></a></li>
		<?php } ?>
	</ul>
</div>

<script>
	// parent active having class "open"
	// child active having class "active"
	
	// set active menu from link location
	var link = window.location.href;
	link = link.replace(web.host, '');
	var string_match = link.match(new RegExp('([a-z0-9\_]+)(\/([a-z0-9\_]+))*', 'gi'));
	if (typeof(string_match) != 'undefined' && string_match != null) {
		var array_link = string_match[0].split('/');
		
		// set parent
		if (typeof(array_link[0]) == 'string') {
			$('a[data-link="' + array_link[0] + '"]').addClass('open');
		}
		
		// set sub parent
		if (typeof(array_link[1]) == 'string') {
			if (typeof(array_link[2]) == 'string') {
				$('a[data-link="' + array_link[1] + '"]').addClass('subdrop');
				$('a[data-link="' + array_link[1] + '"]').next().css('display', 'block');
			} else {
				$('a[data-link="' + array_link[1] + '"]').addClass('active');
			}
		}
		
		// set child
		if (typeof(array_link[2]) == 'string') {
			$('a[data-link="' + array_link[2] + '"]').addClass('active');
		}
	}
</script>