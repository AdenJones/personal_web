<?php
/* 	Edit My Profile
	 */
	 
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$ScreenName = funRqScpVar('ScreenName',$_SESSION['User']->GetScreenName());
	$Email = funRqScpVar('Email',$_SESSION['User']->GetEmailAddress());
	
	$is_edit = true; //assume we are dealing with an edit
	
	//if the form has been submitted
	if($intSubmitted == 1)
	{
		$blnIsGood = true;
		
		Membership\CreateErrorMessageSName($_SESSION['User']->GetUserID(),Validation\ValidateString($ScreenName,2,45),$ScreenName,'Screen Name',$is_edit);
		Validation\CreateErrorMessageEmail(Validation\ValidateString($Email,0,60),Validation\ValidateEmail($Email,true),'Email');
		
		//if all values are good
		if($blnIsGood)
		{
			$_SESSION['User']->UpdateUser($ScreenName,$Email);
			
			header( "Location: $lnk_my_details" );
			//ensure no further processing is performed
			exit;
			
		} else {
			$msg_general_notifier = 'There are errors in this record!';
		}
	}
?>