<?php
	
	
	$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
	if( $allow_deletes )
	{
		$DelTeamID = funRqScpVar('int_del_grp_id','');
		
		if( $DelTeamID != '' )
		{
			$TeamToDel = \Business\Team::LoadTeam($DelTeamID);
			
			if( $TeamToDel != NULL )
			{
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$TeamToDel->GetGroupID(),CHANGE_DELETE,'tbl_groups');
				
				$TeamToDel->Delete(); //safe to use parent method here
			}
		}
	}
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_team = '<a href="'.$lnk_add_edit_team.'">Add Team</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_team = '';
		$Heading = '';
		$secure = false;
	}
	 //this may only be accessible by state users
	 
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$ActiveTeams = Business\Team::LoadTeamsByStateUserID($_SESSION['User']->GetUserID());
		$ArchivedTeams = Business\Team::LoadArchivedTeamsByStateUserID($_SESSION['User']->GetUserID());
		
		//$ActiveGroups = Business\Group::load_active_groups_by_state($_SESSION['User']->GetUserID());
		//$ArchivedGroups = Business\Group::load_archived_groups_by_state($_SESSION['User']->GetUserID());
	}
	elseif($_SESSION['User']->GetUserTypeName() == $FieldWorker)
	{
		$ActiveTeams = Business\Team::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		$ArchivedTeams = array();
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	elseif($_SESSION['User']->GetUserTypeName() == $GroupUser)
	{
		$ActiveTeams = array();
		$ArchivedTeams = array();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRoles($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	else {
		$ActiveTeams = Business\Team::LoadActiveTeams();
		$ArchivedTeams =  Business\Team::LoadArchivedTeams();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroups();
		//$ArchivedGroups = Business\Group::LoadArchivedGroups();
	}
	
	$returnAddress = urlencode($lnk_view_teams_secure);
	
	
	
	
?>