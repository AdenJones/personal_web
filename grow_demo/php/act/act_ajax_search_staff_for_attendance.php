<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	$group_id = trim(funRqScpVar('group_id',''));
	$group_date = trim(funRqScpVar('group_date',''));
	
	$Staff = Membership\Staff::LoadStaffBySearchStringForAttendance($str_text,$group_id,$group_date);
	
?>

