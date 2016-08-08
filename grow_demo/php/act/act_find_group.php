<?php
	
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$str_group_name = funRqScpVar('str_group_name','');
	$int_group_hidden_input = funRqScpVar('int_group_hidden_input','');
	
	$go_to_add_group = '<a href="'.$lnk_add_edit_group.'&return_to='.'view_group">Add Group</a>';
	
	if( $intSubmitted == 1 )
	{
		$blnIsGood = true;
		
		Validation\CreateErrorMessage(Validation\ValidateGroup($int_group_hidden_input),'Group Selection');
		
		if($blnIsGood)
		{
			
			if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin or $_SESSION['User']->GetUserTypeName() == $StateUser  )
			{
				$goto_url = $lnk_view_group.'&id_group='.$int_group_hidden_input;
			} else {
				$goto_url = $lnk_view_group.'&id_group='.$int_group_hidden_input;
			}
			
			header( "Location: $goto_url" );
			//ensure no further processing is performed
			exit;
		}
	}
?>