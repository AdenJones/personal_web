<?php
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		
		$Heading = 'Secure';
		$secure = true;
	} else {
		
		$Heading = '';
		$secure = false;
	}
	
	$UserID = funRqScpVar('id_user','');
	$ReturnTo =  funRqScpVar('return_to','');
	
	$bad_user = $UserID == '';
	
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}	
	
	if(!$bad_user)
	{
		$User = Membership\Staff::LoadStaff($UserID);
				
		//jump out if bad user id is entered
		if($User == false)
		{
			$bad_user = true;
		} else {
			
			
			if( $User->GetUserTypeName() != $FieldWorker )
			{
				$bad_user = true;
			}
		}
	}
	
	//all further processing restricted by bad user detection
	if(!$bad_user)
	{
		$UserRegions = $User->LoadRegions();
		
		$StaffName = $User->GetFirstName().' '.$User->GetLastname();
		
		if($_SESSION['return_to'] == 'view_user')
		{
			$url_return_to_staff = '<a href="'.$lnk_view_user.'&id_user='.$UserID.'">'.$StaffName.'</a>';
			$url_add_staff_region = '<a href="'.$lnk_add_edit_staff_region.'&id_user='.$UserID.'">Add Region</a>';
			
		} else {
			if( $secure )
			{
				$url_return_to_staff = '<a href="'.$lnk_view_staff_secure.'&id_user='.$UserID.'">'.$StaffName.'</a>';
				$url_add_staff_region = '<a href="'.$lnk_add_edit_staff_region.'&id_user='.$UserID.'">Add Region</a>';
			} else {
				$url_return_to_staff = '<a href="'.$lnk_view_staff.'&id_user='.$UserID.'">'.$StaffName.'</a>';
				$url_add_staff_region = '';
			}
		
		}
		
	}
	
?>