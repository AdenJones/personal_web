<?php
	
	$StaffID = intval(funRqScpVar('int_vol_hidden_input',0));
	
	$this_staff = Membership\Staff::LoadStaff($StaffID);
	
	$LoggedInStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
	
	$url_add_staff = '<a href="'.$lnk_add_edit_staff.'">Add Staff</a>';
	
	$secure = true;
	
	if( $this_staff != false )
	{
		if( !$this_staff->IsVolunteer() )
		{
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				if( !$LoggedInStaff->has_branch_by_branch_id($this_staff->GetBranchID()) )
				{
					
				}

			}
		} 
		
		
	} else {
		
	}
	
?>