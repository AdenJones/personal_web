<?php
	
	$months = 1;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$ActiveGroups = Business\Group::load_active_groups_by_state($_SESSION['User']->GetUserID());
		$ArchivedGroups = Business\Group::load_archived_groups_by_state($_SESSION['User']->GetUserID());
	}
	elseif($_SESSION['User']->GetUserTypeName() == $FieldWorker)
	{
		$ActiveGroups = Business\Group::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		$ArchivedGroups = array();//shouldn't see archived groups
	}
	elseif($_SESSION['User']->GetUserTypeName() == $GroupUser)
	{
		$ActiveGroups = Business\Group::LoadActiveGroupsByRoles($_SESSION['User']->GetUserID());
		$ArchivedGroups = array();//shouldn't see archived groups
	}
	else {
		$ActiveGroups = Business\Group::LoadActiveGroups();
		$ArchivedGroups = Business\Group::LoadArchivedGroups();
	}
	
	$GroupRecessNearEnd = \Business\Group::LoadGroupsWithRecessNearEnd($months);
	
	$displayGroups = array();
	
	foreach( $GroupRecessNearEnd as $thisGroup )
	{
		
		foreach( $ActiveGroups as $MyGroup )
		{
			if($thisGroup->GetGroupID() == $MyGroup->GetGroupID())
			{
				$displayGroups[] = $thisGroup;
				break;
			}
		}
		
	}
	
	
?>