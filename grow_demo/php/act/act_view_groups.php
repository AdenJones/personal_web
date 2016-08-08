<?php
	
	
	$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
	if( $allow_deletes )
	{
		$DelGroupID = funRqScpVar('int_del_grp_id','');
		
		if( $DelGroupID != '' )
		{
			$GroupToDel = \Business\Group::LoadGroup($DelGroupID);
			
			if( $GroupToDel != NULL )
			{
				$GroupToDel->Delete();
				
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupToDel->GetGroupID(),CHANGE_DELETE,'tbl_groups');
			}
		}
	}
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_group = '<a href="'.$lnk_add_edit_group.'">Add Group</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_group = '';
		$Heading = '';
		$secure = false;
	}
	
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
	
	
	
	
	
	
?>