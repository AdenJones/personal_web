<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	
	$Staff = Membership\Staff::LoadStaffBySearchString($str_text);
	
?>

