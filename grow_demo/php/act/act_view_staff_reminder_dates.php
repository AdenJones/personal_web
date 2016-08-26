<?php

	$UserID = funRqScpVar('id_user','');
	
	$bad_staff = ($UserID == '');
	
	$ReturnTo =  funRqScpVar('return_to','');
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}
	
	if( !$bad_staff )
	{
		
		$thisStaff = \Membership\Staff::LoadStaff($UserID);
	
		$bad_staff = ($thisStaff == false);
		
		if( !$bad_staff )
		{
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
				
				$arrActiveVolunteers = Membership\Staff::LoadVolunteersByBranch($Staff->GetBranch()->GetBranchAbbreviation());
				$arrArchivedVolunteers = Membership\Staff::LoadArchivedVolunteersByBranch($Staff->GetBranch()->GetBranchAbbreviation());
				
			} else {
				$arrActiveStaff = Membership\Staff::LoadActiveStaff();
				$arrArchivedStaff = Membership\Staff::LoadArchivedStaff();
				$arrActiveVolunteers = Membership\Staff::LoadActiveVolunteers();
				$arrArchivedVolunteers =  Membership\Staff::LoadArchivedVolunteers();
			}
			
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$arrMerged = array_merge($arrActiveVolunteers,$arrArchivedVolunteers);
			} else {
				$arrMerged = array_merge($arrActiveStaff,$arrArchivedStaff,$arrActiveVolunteers,$arrArchivedVolunteers);
			}
			
			$found = false;
			
			foreach($arrMerged as $Person)
			{
				if($thisStaff->GetUserID() == $Person->GetUserID())
				{
					$found = true;
					break;
				}
			}
			
			$bad_staff = !$found;
		}
		
	}
	
	if( !$bad_staff )
	{
		$thisUser = $_SESSION['User'];
		
		$StaffName = $thisStaff->GetFirstName().' '.$thisStaff->GetLastname();
		
		if($_SESSION['return_to'] != 'not_set')
		{
			
			if($_SESSION['return_to'] == 'view_user')
			{
				$url_return_to_staff = '<a href="'.$lnk_view_user.'&id_user='.$UserID.'">'.$StaffName.'</a>';
				
			}
			elseif($_SESSION['return_to'] == 'view_staff_new')
			{
				$url_return_to_staff = '<a href="'.$lnk_view_staff_new.'&int_staff_hidden_input='.$UserID.'">'.$StaffName.'</a>';
			}elseif($_SESSION['return_to'] == 'view_vol_new' )
			{
				$url_return_to_staff = '<a href="'.$lnk_view_vol_new.'&int_vol_hidden_input='.$UserID.'">'.$StaffName.'</a>';
			}elseif($_SESSION['return_to'] == 'view_staff')
			{
				$url_return_to_staff = '<a href="'.$lnk_view_staff_secure.'">'.$StaffName.'</a>';
			}
			elseif($_SESSION['return_to'] == 'rem_pol_check')
			{
				$url_return_to_staff = '<a href="'.$lnk_reminder_police_checks_due.'">'.$StaffName.'</a>';
			}

			
			
		}
		
		$PoliceCheckDates = $thisStaff->LoadPoliceCheckDates();
		
		$url_add_police_check_date = '<a href="'.$lnk_add_edit_police_check_reminder_dates.'&id_user='.$UserID.'">Add Police Check Date</a>';
	}

?>