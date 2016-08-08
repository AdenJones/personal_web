<?php
	
	
	$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
	if( $allow_deletes )
	{
		
		$DelSocEvrID = funRqScpVar('int_del_grp_id','');
		
		if( $DelSocEvrID != '' )
		{
			$SocEvToDel = \Business\SocialEvent::Load($DelSocEvrID);
			
			if( $SocEvToDel != NULL )
			{
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$SocEvToDel->GetGroupID(),CHANGE_DELETE,'tbl_groups');
					
				$SocEvToDel->Delete(); //safe to use parent method here
			}
		}
	}
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_social_event = '<a href="'.$lnk_add_edit_social_event.'">Add Social Event</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_social_event = '';
		$Heading = '';
		$secure = false;
	}
	 //this may only be accessible by state users
	 
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$ActiveSocEv = Business\SocialEvent::LoadTeamsByStateUserID($_SESSION['User']->GetUserID());
		$ArchivedSocEv = Business\SocialEvent::LoadArchivedTeamsByStateUserID($_SESSION['User']->GetUserID());
		
		//$ActiveGroups = Business\Group::load_active_groups_by_state($_SESSION['User']->GetUserID());
		//$ArchivedGroups = Business\Group::load_archived_groups_by_state($_SESSION['User']->GetUserID());
	}
	elseif($_SESSION['User']->GetUserTypeName() == $FieldWorker)
	{
		$ActiveSocEv = Business\SocialEvent::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		$ArchivedSocEv = array();
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	elseif($_SESSION['User']->GetUserTypeName() == $GroupUser)
	{
		$ActiveSocEv = array();
		$ArchivedSocEv = array();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRoles($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	else {
		$ActiveSocEv = Business\SocialEvent::LoadActive();
		$ArchivedSocEv =  Business\SocialEvent::LoadArchived();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroups();
		//$ArchivedGroups = Business\Group::LoadArchivedGroups();
	}
	
	$returnAddress = urlencode($lnk_view_teams_secure);
	
	
	
	
?>