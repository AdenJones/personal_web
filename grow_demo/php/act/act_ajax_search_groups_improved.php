<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	$record_limit = trim(funRqScpVar('record_limit',''));
	$limit_to = trim(funRqScpVar('limit_to',''));
	//Security needs to be added by user
	
	if( $_SESSION['User']->GetUserTypeName() == STATE_USER )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$Groups = Business\Group::loadGroupsByStateBySearchStringImproved($Staff->GetUserID(),$str_text,$record_limit,$limit_to);
		
	}
	elseif($_SESSION['User']->GetUserTypeName() == FIELD_WORKER )
	{
		$Groups = Business\Group::LoadGroupsByRegionsBySearchStringImproved($_SESSION['User']->GetUserID(),$str_text,$record_limit,$limit_to);
		
	}
	elseif($_SESSION['User']->GetUserTypeName() == GROUP_USER )
	{
		$Groups = Business\Group::LoadGroupsByRolesBySearchStringImproved($_SESSION['User']->GetUserID(),$str_text,$record_limit,$limit_to);
		
	}
	else {
		$Groups = Business\Group::LoadGroupsBySearchStringImproved($str_text,$record_limit,$limit_to);
	}
	
	
	
?>

