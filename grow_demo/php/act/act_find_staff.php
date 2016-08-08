<?php
	
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$str_staff = funRqScpVar('str_staff','');
	$int_staff_hidden_input = funRqScpVar('int_staff_hidden_input','');
	
	$go_to_add_staff = '<a href="'.$lnk_add_edit_staff.'&return_to='.'find_staff">Add Staff</a>';
	
	if( $intSubmitted == 1 )
	{
		$blnIsGood = true;
		
		Validation\CreateErrorMessage(Validation\ValidateStaffUser($int_staff_hidden_input),'Staff Selection');
		
		if($blnIsGood)
		{
			
			if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin or $_SESSION['User']->GetUserTypeName() == $StateUser  )
			{
				$goto_url = $lnk_view_user.'&id_user='.$int_staff_hidden_input;
			} else {
				$goto_url = $lnk_view_staff_new.'&id_user='.$int_staff_hidden_input;
			}
			
			header( "Location: $goto_url" );
			//ensure no further processing is performed
			exit;
		}
	}
?>