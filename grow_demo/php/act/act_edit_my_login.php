<?php
/* 	Edit My Profile
	 */
	 
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$UserName = funRqScpVar('UserName',$_SESSION['User']->GetUserName());
	$OldPassword = funRqScpVar('OldPassword','');
	$Password = funRqScpVar('Password','');
	$CheckPassword = funRqScpVar('CheckPassword','');
	
	$is_edit = true; //assume we are dealing with an edit
	
	//if the form has been submitted
	if($intSubmitted == 1)
	{
		$blnIsGood = true;
		
		Membership\CreateErrorMessageUName($_SESSION['User']->GetUserID(),Validation\ValidateString($UserName,6,45),$UserName,'User Name',$is_edit);
		
		Membership\CreateErrorMessagePasswordMatch(Validation\ValidateString($OldPassword,6,45),$_SESSION['User']->GetSalt(),$_SESSION['User']->GetHashedPassword(),$OldPassword,'Old Password');
		
		Validation\CreateErrorMessage(Validation\ValidateString($Password,6,45),'Password');
		Validation\CreateErrorMessage(Validation\ValidateString($CheckPassword,6,45),'Re-entered Password');
								
		Validation\CreateErrorMessageStringMatch($Password,$CheckPassword,'Passwords');
		
		
		//if all values are good
		if($blnIsGood)
		{
			$_SESSION['User']->UpdateUserLogin($UserName,$Password);
			
			Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$_SESSION['User']->GetUserID(),CHANGE_UPDATE,'tbl_users');
			
			if( $_SESSION['User']->GetUserTypeCategory() != $UserTypeCatAdmin )
			{
				//redirect to view page
				header( "Location: $lnk_edit_my_login_details" );
				//ensure no further processing is performed
			
			} else {
				header( "Location: $lnk_edit_my_login_details" );
			}
			
			exit;
			
		} else {
			$msg_general_notifier = 'There are errors in this record!';
		}
	}
?>