<?php
	
	
	$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
	if( $allow_deletes )
	{
		$DelComOutID = funRqScpVar('int_del_grp_id','');
		
		if( $DelComOutID != '' )
		{
			$ComOutToDel = \Business\CommunityOutreach::Load($DelComOutID);
			
			if( $ComOutToDel != NULL )
			{
				$ComOutToDel->Delete(); //safe to use parent method here
				
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$ComOutToDel->GetGroupID(),CHANGE_DELETE,'tbl_groups');
			}
		}
	}
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_community_outreach = '<a href="'.$lnk_add_edit_community_outreach.'">Add Community Outreach Team</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_community_outreach = '';
		$Heading = '';
		$secure = false;
	}
	 //this may only be accessible by state users
	 
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$ActiveComOut = Business\CommunityOutreach::LoadTeamsByStateUserID($_SESSION['User']->GetUserID());
		$ArchivedComOut = Business\CommunityOutreach::LoadArchivedTeamsByStateUserID($_SESSION['User']->GetUserID());
		
		//$ActiveGroups = Business\Group::load_active_groups_by_state($_SESSION['User']->GetUserID());
		//$ArchivedGroups = Business\Group::load_archived_groups_by_state($_SESSION['User']->GetUserID());
	}
	elseif($_SESSION['User']->GetUserTypeName() == $FieldWorker)
	{
		$ActiveComOut = Business\CommunityOutreach::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		$ArchivedComOut = array();
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	elseif($_SESSION['User']->GetUserTypeName() == $GroupUser)
	{
		$ActiveComOut = array();
		$ArchivedComOut = array();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRoles($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	else {
		$ActiveComOut = Business\CommunityOutreach::LoadActive();
		$ArchivedComOut =  Business\CommunityOutreach::LoadArchived();
		
	}
	
	
?>