<?php 
/* Action file for signing up */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$is_edit = false; //assume we are dealing with an add
	$bad_person = false;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_staff_user_types = Membership\getVolunteerUserTypes()->fetchAll();
		
	} else {
		$arr_staff_user_types = Membership\getStaffUserTypes()->fetchAll();
	}
	
	
	$UserID = funRqScpVar('id_user','');
	
	$ReturnTo =  funRqScpVar('return_to','');
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}	
	
	//check for a valid staff id
	$staff = Membership\Staff::LoadStaff($UserID);
			
	//jump out if bad user id is entered
	if(!$staff)
	{
		$bad_person = true;
	} else {
		
		if( $_SESSION['User']->GetUserTypeName() == $StateUser )
		{
			
			if( $staff->GetBranch()->GetBranchAbbreviation() != $UserStaff->GetBranch()->GetBranchAbbreviation() )
			{
				$bad_person = true;
			}
			
			if( !$staff->IsVolunteer() )
			{
				$bad_person = true;
			}
		}
			
	}
	
	if( !$bad_person )
	{
		if($staff->HasLogin())
		{
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
			$is_edit = true;
			
			$UserName = funRqScpVar('UserName',$staff->GetUserName());
			$Password = funRqScpVar('Password','');
			$CheckPassword = funRqScpVar('CheckPassword','');
			
			$ScreenName = funRqScpVar('ScreenName',$staff->GetScreenName());
			$UserType = funRqScpVar('UserType',$staff->GetUserTypeID());
			
		} else {
			$UserName = funRqScpVar('UserName','');
			$Password = funRqScpVar('Password','');
			$CheckPassword = funRqScpVar('CheckPassword','');
			
			$ScreenName = funRqScpVar('ScreenName','');
			$UserType = funRqScpVar('UserType','');
		}
		
		
		//if the form has been submitted
		if( $intSubmitted == 1)
		{
			$blnIsGood = true;
			
			//0 for username due to no self check
			Membership\CreateErrorMessageUName($UserID,Validation\ValidateString($UserName,6,45),$UserName,'User Name',$is_edit);
			
			Validation\CreateErrorMessage(Validation\ValidateString($Password,6,45),'Password');
			Validation\CreateErrorMessage(Validation\ValidateString($CheckPassword,6,45),'Re-entered Password');			
			Validation\CreateErrorMessageStringMatch($Password,$CheckPassword,'Passwords');
			
			Membership\CreateErrorMessageSName($UserID,Validation\ValidateString($ScreenName,2,45),$ScreenName,'Screen Name',$is_edit);
			
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				Validation\CreateErrorMessage(Validation\ValidateVolunteerUserType($UserType),'User Type');
			} else {
				Validation\CreateErrorMessage(Validation\ValidateStaffUserType($UserType),'User Type');
			}
			 
			
			//if all values are good
			if($blnIsGood)
			{
				
				//create the user
				$staff->AddStaffLogin($UserName,$Password,$ScreenName,$UserType);
				
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$staff->GetUserID(),CHANGE_UPDATE,'tbl_users');
				
				//redirect to view page
				
				if($_SESSION['return_to'] != 'not_set')
				{
					
					if($_SESSION['return_to'] == 'view_user')
					{
						$return_url = $lnk_view_user.'&id_user='.$UserID;
						header( "Location: $return_url" );
					}
					elseif($_SESSION['return_to'] == 'view_staff_new')
					{
						header( "Location: $lnk_view_staff_new".'&int_staff_hidden_input='.$UserID );
						
					}elseif($_SESSION['return_to'] == 'view_vol_new' )
					{
						header( "Location: $lnk_view_vol_new".'&int_vol_hidden_input='.$UserID );
						
					}
					
					
				}
				
				
				
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this record!';
			}
		}
	}
	
	
	
?>