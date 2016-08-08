<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	
	$Volunteers = Membership\Staff::LoadVolunteersBySearchString($str_text);
	
?>

