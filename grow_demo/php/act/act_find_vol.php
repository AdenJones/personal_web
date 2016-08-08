<?php
	
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$str_vol = funRqScpVar('str_vol','');
	$int_vol_hidden_input = funRqScpVar('int_vol_hidden_input','');
	
	$go_to_add_staff = '<a href="'.$lnk_add_edit_staff.'&return_to=find_vol">Add Volunteer</a>';
	
	if( $intSubmitted == 1 )
	{
		$blnIsGood = true;
		
		Validation\CreateErrorMessage(Validation\ValidateVolunteerStaffUser($int_vol_hidden_input),'Volunteer Selection'); //this is not as strict as the rules for collecting volunteers
		
		if($blnIsGood)
		{
			
			if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin or $_SESSION['User']->GetUserTypeName() == $StateUser  )
			{
				$goto_url = $lnk_view_user.'&id_user='.$int_vol_hidden_input;
			} else {
				$goto_url = $lnk_view_vol_new.'&id_user='.$int_vol_hidden_input;
			}
			
			header( "Location: $goto_url" );
			//ensure no further processing is performed
			exit;
		}
	}
?>