<?php
	
	
	$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
	if( $allow_deletes )
	{
		$DelHosOrID = funRqScpVar('int_del_grp_id','');
		
		if( $DelHosOrID != '' )
		{
			$HosOrToDel = \Business\HospitalOrientation::Load($DelHosOrID);
			
			if( $HosOrToDel != NULL )
			{
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$HosOrToDel->GetGroupID(),CHANGE_DELETE,'tbl_groups');
				
				$HosOrToDel->Delete(); //safe to use parent method here
				
				
			}
		}
	}
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_hospital_orientation = '<a href="'.$lnk_add_edit_hospital_orientation.'">Add Hospital Orientation</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_hospital_orientation = '';
		$Heading = '';
		$secure = false;
	}
	 //this may only be accessible by state users
	 
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$ActiveHosOrs = Business\HospitalOrientation::LoadTeamsByStateUserID($_SESSION['User']->GetUserID());
		$ArchivedHosOrs = Business\HospitalOrientation::LoadArchivedTeamsByStateUserID($_SESSION['User']->GetUserID());
		
		//$ActiveGroups = Business\Group::load_active_groups_by_state($_SESSION['User']->GetUserID());
		//$ArchivedGroups = Business\Group::load_archived_groups_by_state($_SESSION['User']->GetUserID());
	}
	elseif($_SESSION['User']->GetUserTypeName() == $FieldWorker)
	{
		$ActiveHosOrs = Business\HospitalOrientation::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		$ArchivedHosOrs = array();
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	elseif($_SESSION['User']->GetUserTypeName() == $GroupUser)
	{
		$ActiveHosOrs = array();
		$ArchivedHosOrs = array();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRoles($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	else {
		$ActiveHosOrs = Business\HospitalOrientation::LoadActive();
		$ArchivedHosOrs =  Business\HospitalOrientation::LoadArchived();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroups();
		//$ArchivedGroups = Business\Group::LoadArchivedGroups();
	}
	
	$returnAddress = urlencode($lnk_view_teams_secure);
	
	
	
	
?>