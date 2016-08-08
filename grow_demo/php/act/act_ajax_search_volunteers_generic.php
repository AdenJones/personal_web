<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	$container_id = trim(funRqScpVar('container_id',''));
	$record_limit = intval(funRqScpVar('record_limit',''));
	$div_popup_id = trim(funRqScpVar('str_pop_up_id',''));
	$extra = trim(funRqScpVar('extra',''));
	
	$Volunteers = Membership\Staff::LoadVolunteersBySearchStringOptimised($str_text,$record_limit,$extra);
	
?>

