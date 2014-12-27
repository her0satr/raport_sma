<?php
	$user = $this->user_model->get_session();
?>
<header>
<div class="navbar navbar-fixed-top bs-docs-nav" role="banner">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle btn-navbar" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse"><span>Menu</span></button>
            <a href="#" class="pull-left menubutton hidden-xs"><i class="fa fa-bars"></i></a>
            <a href="<?php echo base_url(); ?>" class="navbar-brand">Home</a> 
		</div>
		
		<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">         
			<ul class="nav navbar-nav pull-right">
				<li class="dropdown pull-right user-data">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<img src="<?php echo base_url('static/img/user1.png'); ?>" style="width: 25px; height: 25px;">
						<?php echo $user['user_display']; ?> <b class="caret"></b>
					</a>
					
					<ul class="dropdown-menu">
						<?php if ($user['user_type'] == 'admin') { ?>
						<li><a class="cursor btn-raise-grade"><i class="fa fa-key"></i> Kenaikan kelas otomatis</a></li>
						<?php } ?>
						<li><a href="<?php echo base_url('home/logout'); ?>"><i class="fa fa-key"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</div>
</header>