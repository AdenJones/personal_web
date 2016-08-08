<?php 
/* Action file for signing up */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$UserID = funRqScpVar('id_user','');
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_user = false;	
	$arr_user_types = Membership\GetUserTypes()->fetchAll();
	$is_edit = false;
	
	if($UserID != '')
	{
		$User = Membership\User::LoadUser($UserID,false);
				
		//jump out if bad user id is entered
		if(!$User)
		{
			$bad_user = true;
		} else {
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
			$is_edit = true;
		}
	}
	
	if(!$bad_user)
	{
		if($UserID != '')
		{
			$UserName = funRqScpVar('UserName',$User->GetUserName());
			$Password = funRqScpVar('Password','');
			$CheckPassword = funRqScpVar('CheckPassword','');
			$ScreenName = funRqScpVar('ScreenName',$User->GetScreenName());
			$UserType = funRqScpVar('UserType',$User->GetUserTypeID());
		
		} else {
			$UserName = funRqScpVar('UserName','');
			$Password = funRqScpVar('Password','');
			$CheckPassword = funRqScpVar('CheckPassword','');
			$ScreenName = funRqScpVar('ScreenName','');
			$UserType = funRqScpVar('UserType','');
		}
		
	
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			$blnIsGood = true;
			
			//0 for username due to no self check
			Membership\CreateErrorMessageUName($UserID,Validation\ValidateString($UserName,6,45),$UserName,'User Name',$is_edit);
			
			Validation\CreateErrorMessage(Validation\ValidateString($Password,6,45),'Password');
			Validation\CreateErrorMessage(Validation\ValidateString($CheckPassword,6,45),'Re-entered Password');			
			Validation\CreateErrorMessageStringMatch($Password,$CheckPassword,'Passwords');
			
			Membership\CreateErrorMessageSName($UserID,Validation\ValidateString($ScreenName,2,45),$ScreenName,'Screen Name',$is_edit);
			Validation\CreateErrorMessage(Validation\ValidateAllUserTypes($UserType),'User Type');
			
			//if all values are good
			if($blnIsGood)
			{
				if($UserID != '')
				{
					$User->UpdateUserSafe($UserName,$Password,$ScreenName,'',$UserType);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$User->GetUserID(),CHANGE_UPDATE,'tbl_users');
				} else {
					//create the user
					$NewUser = Membership\User::CreateUserSafe($UserName,$Password,$ScreenName,'',$UserType);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$NewUser->GetUserID(),CHANGE_INSERT,'tbl_users');
				}
				
				//redirect to view page
				header( "Location: $lnk_view_all_users" );
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this record!';
			}
		} //End if submitted
	} // End if bad user
	
?>