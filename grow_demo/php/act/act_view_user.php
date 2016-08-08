<?php
	//this page is only visible to States Users, Staff Admins and IT Admins
	//current security will fail if other users are given access
	
	$UserID = funRqScpVar('id_user','');
	$BadUser = false;
	$BadMember = false;
	$BadStaffVol = false;
	$RestrictedStaff = false;
	$return_to = '&return_to=view_user';
	$LoggedInStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
	$intSubmitted = funRqScpVar('form_submitted','');
	$ActivityID = funRqScpVar('id_activity','');
	$CommittedID = funRqScpVar('id_committed','');
	$Delete = funRqScpVar('del_user','');
	
	if( $UserID == '' )
	{
		$BadUser = true;
	} else {
		$User = Membership\User::LoadUserUnSafe($UserID);
		
		if($intSubmitted == 1)
		{
			if($ActivityID != '' and ($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin))
			{
				
				Membership\delActivityDateByID($ActivityID);
				
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$ActivityID,CHANGE_DELETE,'tbl_user_activity_dates');
			}
			
			if($CommittedID != '' and ($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin or $_SESSION['User']->GetUserTypeName() == $StateUser) )
			{
				
				Membership\delMemberCommittedDateByID($CommittedID);
				
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$CommittedID,CHANGE_DELETE,'tbl_member_committed_dates');
			}		
		}
				
		if( $User == NULL )
		{
			$BadUser = true;
		} else {
			
			if( $Delete == 1 and ($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) )
			{
				$StaffRecord = \Membership\Staff::LoadStaff($UserID);
				$MemberRecord = \Membership\Member::LoadMember($UserID);
				
				$Notification = 'User: ';
				
				if( $StaffRecord != false )
				{
					$Notification .= $StaffRecord->GetFirstName().' '.$StaffRecord->GetLastname();
				}elseif($MemberRecord != false )
				{
					$Notification .= $MemberRecord->GetFirstName().' '.$MemberRecord->GetLastname();
				}else
				{
					$Notification .= 'ID:'.$UserID;
				}
				
				$Notification .= ' has been deleted!';
				
				$IsDeleted = $User->Delete();
				
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$UserID,CHANGE_DELETE,'tbl_users');
				
				if($IsDeleted)
				{
					$go_to_url = $lnk_default_page.'&msg_general_notifier='.urlencode($Notification);;
					
					echo $go_to_url;
					
					//redirect to view page
					header( "Location: $go_to_url" );
					//ensure no further processing is performed
					exit;
				}else{
					$msg_general_notifier = 'Delete User failed!';
				}
			}
			
			$UserLastAttendance = Business\Attendance::LoadUserMostRecentAttendance($UserID);
			if( $UserLastAttendance != NULL )
			{
				$UserLastGroup = Business\Group::LoadGroup($UserLastAttendance->GetGroupID());
			}
			
			$Member = Membership\Member::LoadMember($UserID);
			
			if( $Member == false )
			{
				$BadMember = true;
			} else {
				
				
				$MemberCommittedDates = $Member->GetCommittedDates();
				$url_add_member_committed_dates = '<a href="'.$lnk_add_edit_member_committed_dates.'&id_user='.$UserID.'&return_to=view_user">Add Committed Date</a>';
			}
			
			$StaffVol = Membership\Staff::LoadStaff($UserID);
			
			if( $StaffVol == false )
			{
				$BadStaffVol = true;
				
			} else {
				if( $_SESSION['User']->GetUserTypeName() == $StateUser )
				{
					if( Membership\wasStaff($UserID,date("Y-m-d")) )
					{
						
						$RestrictedStaff = true;
					}
					
					if( !$LoggedInStaff->has_branch_by_branch_id($StaffVol->GetBranchID()) )
					{
						$RestrictedStaff = true;
					}
				}
			}
			
			$AllActivityDates = $User->LoadAllUserActivityDates();
			
			if( !$BadStaffVol )
			{
				$UserName = $StaffVol->GetFirstName().' '.$StaffVol->GetLastname();
			}
			elseif(!$BadMember)
			{
				$UserName = $Member->GetFirstName().' '.$Member->GetLastname();
				
				
			}
			else
			{
				$UserName = 'No Member or Staff Record!';
			}	
		}
	}
	
	$url_add_member_activity_date = '<a style="margin-top:3px" href="'.$lnk_add_edit_member_dates.'&id_user='.$UserID.$return_to.'">Add Member Activity Record</a>';
	$url_add_staff_activity_date = '<a style="margin-top:3px" href="'.$lnk_add_edit_staff_volunteer_dates.'&id_user='.$UserID.$return_to.'">Add Staff or Volunteer Activity Record</a>';
?>