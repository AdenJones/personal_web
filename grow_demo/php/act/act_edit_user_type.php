<?php
/* 	Add Edit Members Action File
	 */
	 
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$UserID = funRqScpVar('id_user','0'); //default of zero to capture no userid submitted
	$Submitted = funRqScpVar('form_submitted','');
	
	$bad_user = false;
	
	if(  Membership\UserExists($UserID) )
	{
		$User = Membership\User::LoadUser($UserID);
		$UserTypes = Membership\GetUserTypes();
		
		$UserTypeID = funRqScpVar('id_user_type',$User->GetUserTypeID());
		
		if($Submitted == 1)
		{
			$blnIsGood = true;
			Validation\CreateErrorMessage(Membership\UserTypeExists($UserTypeID),'User Type');
			
			if( $blnIsGood )
			{
				$User->UpdateUserType($UserTypeID);
				
				header( "Location: $lnk_view_all_users" );
				//ensure no further processing is performed
				exit;
				
			}
		}
	} 
	else
	{
		$bad_user = true;
	}
	
	
	
	
?>