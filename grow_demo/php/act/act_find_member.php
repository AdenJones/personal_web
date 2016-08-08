<?php
	
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$str_member = funRqScpVar('str_member','');
	$int_member_hidden_input = funRqScpVar('int_member_hidden_input','');
	
	$go_to_add_member = '<a href="'.$lnk_add_edit_member.'&return_to='.urlencode($lnk_find_member).'">Add Member</a>';
	
	if( $intSubmitted == 1 )
	{
		$blnIsGood = true;
		
		Validation\CreateErrorMessage(Validation\ValidateMemberUser($int_member_hidden_input),'Member Selection');
		
		if($blnIsGood)
		{
			
			if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin or $_SESSION['User']->GetUserTypeName() == $StateUser  )
			{
				$goto_url = $lnk_view_user.'&id_user='.$int_member_hidden_input;
			} else {
				$goto_url = $lnk_add_edit_member.'&id_user='.$int_member_hidden_input.'&return_to='.urlencode($lnk_find_member);
			}
			header( "Location: $goto_url" );
			//ensure no further processing is performed
			exit;
		}
	}
?>