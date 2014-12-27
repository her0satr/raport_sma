<?php
	// array student
	$array_student = $this->student_model->get_array(array( 'class_level_id' => $_POST['class_level_id'] ));
	
	// student include grade
	$param_student_grade = array(
		'tahun' => $_POST['tahun'],
		'semester' => $_POST['semester'],
		'array_student' => $array_student
	);
	$array_student_grade = $this->grade_model->get_array_total($param_student_grade);
?>

<table id="datatable" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th style="width: 75%;">Name</th>
			<th class="center" style="width: 25%;">Nilai Raport Rata - Rata</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($array_student_grade as $row) { ?>
		<tr>
			<td><?php echo $row['name']; ?></td>
			<td class="center"><?php echo $row['average_score']; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>