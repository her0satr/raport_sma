<?php
	// student
	$student = $this->student_model->get_by_id(array( 'id' => $_GET['student_id'] ));
	
	// grade
	$param_grade['tahun'] = $_GET['tahun'];
	$param_grade['semester'] = $_GET['semester'];
	$param_grade['student_id'] = $_GET['student_id'];
	$array_grade = $this->grade_model->get_array($param_grade);
?>

<style>
.clear { clear: both; }

#header .small { float: left; width: 150px; }
#header .medium { float: left; width: 250px; }

#cnt-raport .header { padding: 15px 0; }

#footer { padding: 25px 0; }
#footer .left { float: left; width: 400px; text-align: center; }
#footer .right { float: left; width: 400px; }
</style>

<div id="header">
	<div class="small">Nama Sekolah</div>
	<div class="medium">: SMP BOE Malang</div>
	<div class="small">&nbsp;</div>
	<!--
	<div class="small">Kelas</div>
	<div class="small">: VIII</div>
	-->
	<div class="clear"></div>
	<div class="small">Alamat</div>
	<div class="medium">: <?php echo $student['address']; ?></div>
	<div class="small">&nbsp;</div>
	<div class="small">Semester</div>
	<div class="small">: <?php echo get_value_semester($_GET['semester']); ?></div>
	<div class="clear"></div>
	<div class="small">Nama Siswa</div>
	<div class="medium">: <?php echo $student['name']; ?></div>
	<div class="small">&nbsp;</div>
	<div class="small">Tahun Pelajaran</div>
	<div class="small">: <?php echo get_value_year($_GET['tahun']); ?></div>
	<div class="clear"></div>
</div>

<div id="cnt-raport">
	<div class="header">CAPAIAN</div>
	<table border="1" style="width: 800px;">
		<tr>
			<td style="text-align: center; width: 5%;">No</td>
			<td style="text-align: center; width: 40%;">Mata Pelajaran</td>
			<td style="text-align: center; width: 10%;">UH</td>
			<td style="text-align: center; width: 10%;">UTS</td>
			<td style="text-align: center; width: 10%;">UAS</td>
			<td style="text-align: center; width: 10%;">Total</td>
		</tr>
		<?php $counter = 1; ?>
		<?php foreach ($array_grade as $key => $row) { ?>
		<tr>
			<td style="text-align: center;"><?php echo $counter; ?></td>
			<td><?php echo $row['discipline_title']; ?></td>
			<td style="text-align: center;"><?php echo $row['uh']; ?></td>
			<td style="text-align: center;"><?php echo $row['uts']; ?></td>
			<td style="text-align: center;"><?php echo $row['uas']; ?></td>
			<td style="text-align: center;"><?php echo $row['raport']; ?></td>
		</tr>
		<?php $counter++; ?>
		<?php } ?>
	</table>
</div>

<div id="footer">
	<div class="left">
		&nbsp;<br />
		Mengetahui,<br />
		Orang Tua/Wali
	</div>
	<div class="right">
		Diberikan di :<br />
		Tanggal :<br />
		Wali Kelas<br /><br /><br /><br />
		__________________________<br />
		NIP. ______________________
	</div>
</div>