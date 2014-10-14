<?php
	// initialize student grade
	$this->grade_model->initialize_student_grade($_POST);
	
	// get array
	$array_student = $this->grade_model->get_array($_POST);
?>

<?php foreach ($array_student as $row) { ?>
<tr>
	<td>
		<?php echo $row['student_name']; ?>
		<input type="hidden" name="id[]" value="<?php echo $row['id']; ?>" />
		<span class="hide"><?php echo json_encode($row); ?></span>
	</td>
	<td class="center"><input type="text" class="form-control center" name="uh[]" value="<?php echo $row['uh']; ?>" ></td>
	<td class="center"><input type="text" class="form-control center" name="uts[]" value="<?php echo $row['uts']; ?>" ></td>
	<td class="center"><input type="text" class="form-control center" name="uas[]" value="<?php echo $row['uas']; ?>" ></td>
</tr>
<?php } ?>