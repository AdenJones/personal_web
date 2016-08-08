<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	
	
	//Security needs to be added by user
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$Groups = Business\Group::load_groups_by_state_by_searchstring($Staff->GetUserID(),$str_text);
		
	}
	elseif($_SESSION['User']->GetUserTypeName() == $FieldWorker)
	{
		$Groups = Business\Group::LoadGroupsByRegionsBySearchString($_SESSION['User']->GetUserID(),$str_text);
		
	}
	elseif($_SESSION['User']->GetUserTypeName() == $GroupUser)
	{
		$Groups = Business\Group::LoadGroupsByRolesBySearchString($_SESSION['User']->GetUserID(),$str_text);
		
	}
	else {
		$Groups = Business\Group::LoadGroupsBySearchString($str_text);
	}
	
	
	
?>

