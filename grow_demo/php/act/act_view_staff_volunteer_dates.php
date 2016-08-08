<?php
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		
		$Heading = 'Secure';
		$secure = true;
	} else {
		
		$Heading = '';
		$secure = false;
	}
	
	$ReturnTo =  funRqScpVar('return_to','');
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}
	
	$UserID = funRqScpVar('id_user','');
	
	$bad_user = $UserID == '';
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
	}
	
	if(!$bad_user)
	{
		$User = Membership\Staff::LoadStaff($UserID);
				
		//jump out if bad user id is entered
		if($User == false)
		{
			$bad_user = true;
		} else {
			
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				
				if( !$UserStaff->HasBranch($User->GetBranch()->GetBranchID()) )
				{
					$bad_user = true;
				}
				
				if( !$User->IsVolunteer() )
				{
					$bad_user = true;
				}
			}
			
		}
		
	}
	
	//all further processing restricted by bad user detection
	if(!$bad_user)
	{
		$UserStaffActivityDates = $User->GetActivityDates();
		
		$StaffName = $User->GetFirstName().' '.$User->GetLastname();
		
		if($_SESSION['return_to'] != 'not_set')
		{
			if($_SESSION['return_to'] == 'view_staff_new')
			{
				$url_return_to_staff = '<a href="'.$lnk_view_staff_new.'&int_staff_hidden_input='.$UserID.'">'.$StaffName.'</a>';
			}elseif($_SESSION['return_to'] == 'view_vol_new' )
			{
				$url_return_to_staff = '<a href="'.$lnk_view_vol_new.'&int_vol_hidden_input='.$UserID.'">'.$StaffName.'</a>';
			}
			
			
		}
		
		if(  $secure )
		{
			$url_add_staff_activity_date = '<a href="'.$lnk_add_edit_staff_volunteer_dates.'&id_user='.$UserID.'">Add Dates</a>';
		} else {
			$url_add_staff_activity_date = '';
		}
			
	}
	
?>