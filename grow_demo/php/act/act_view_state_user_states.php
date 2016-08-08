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
	
	if(!$bad_user)
	{
		$User = Membership\Staff::LoadStaff($UserID);
				
		//jump out if bad user id is entered
		if($User == false)
		{
			$bad_user = true;
		} else {
			
		}
		
	}
	
	//all further processing restricted by bad user detection
	if(!$bad_user)
	{
		$state_activity_dates = $User->get_state_activity_dates();
		
		$StaffName = $User->GetFirstName().' '.$User->GetLastname();
		
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
			}
			
			
		}
		
		if( $secure )
		{
			
			$url_add_state_user_state_activity_date = '<a href="'.$lnk_add_edit_state_user_state_activity_dates.'&id_user='.$UserID.'">Add State</a>';
		} else {
			
			$url_add_state_user_state_activity_date = '';
		}
		
		
		
		
		
	}
	
?>