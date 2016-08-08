<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	$container_id = trim(funRqScpVar('container_id',''));
	$record_limit = intval(funRqScpVar('record_limit',''));
	$div_popup_id = trim(funRqScpVar('str_pop_up_id',''));
	
	$this_date = date("Y-m-d");
	
	if( $_SESSION['User']->GetUserTypeName() == $Admin )
	{
		$arrMembers = Membership\Member::LoadMembersBySearchStringOptimised($str_text,$this_date,$record_limit,false);;
	} else {
		$arrMembers = Membership\Member::LoadMembersBySearchStringOptimised($str_text,$this_date,$record_limit);;
	}
	
	
	
?>

