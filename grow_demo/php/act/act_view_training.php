<?php
	
	
	$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
	if( $allow_deletes )
	{
		$DelTrainingID = funRqScpVar('int_del_grp_id','');
		
		if( $DelTrainingID != '' )
		{
			$TrainingToDel = \Business\Training::Load($DelTrainingID);
			
			if( $TrainingToDel != NULL )
			{
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$TrainingToDel->GetGroupID(),CHANGE_DELETE,'tbl_groups');
				
				$TrainingToDel->Delete(); //safe to use parent method here
			}
		}
	}
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_training = '<a href="'.$lnk_add_edit_training.'">Add Training Team</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_training = '';
		$Heading = '';
		$secure = false;
	}
	 //this may only be accessible by state users
	 
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$ActiveTraining = Business\Training::LoadTeamsByStateUserID($_SESSION['User']->GetUserID());
		$ArchivedTraining = Business\Training::LoadArchivedTeamsByStateUserID($_SESSION['User']->GetUserID());
		
		//$ActiveGroups = Business\Group::load_active_groups_by_state($_SESSION['User']->GetUserID());
		//$ArchivedGroups = Business\Group::load_archived_groups_by_state($_SESSION['User']->GetUserID());
	}
	elseif($_SESSION['User']->GetUserTypeName() == $FieldWorker)
	{
		$ActiveTraining = Business\Training::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		$ArchivedTraining = array();
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRegions($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	elseif($_SESSION['User']->GetUserTypeName() == $GroupUser)
	{
		$ActiveTraining = array();
		$ArchivedTraining = array();
		
		
		//$ActiveGroups = Business\Group::LoadActiveGroupsByRoles($_SESSION['User']->GetUserID());
		//$ArchivedGroups = array();//shouldn't see archived groups
	}
	else {
		$ActiveTraining = Business\Training::LoadActive();
		$ArchivedTraining =  Business\Training::LoadArchived();
		
	}
	
	
?>