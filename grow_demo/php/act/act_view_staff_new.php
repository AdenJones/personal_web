<?php
	
	$StaffID = intval(funRqScpVar('int_staff_hidden_input',0));
	
	$this_staff = Membership\Staff::LoadStaff($StaffID);
	
	$secure = ($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin );
	
	if( $secure )
	{
		$url_add_staff = '<a href="'.$lnk_add_edit_staff.'">Add Staff</a>';
	} else {
		$url_add_staff = '';
	}
	
	if( $this_staff != false )
	{
		
	} else {
		
	}
	
?>