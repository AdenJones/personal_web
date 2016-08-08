<?php 

	$arrErrors = array();
	
	$str_member_1 = funRqScpVar('str_member_1','');
	$int_member_hidden_input_1 = funRqScpVar('int_member_hidden_input_1','');
	
	$str_member_2 = funRqScpVar('str_member_2','');
	$int_member_hidden_input_2 = funRqScpVar('int_member_hidden_input_2','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$unselect = funRqScpVar('unselect','0');
	
	$Message = 'submit';
	
	if( !isset($_SESSION['user']['member']['members_selected']) )
	{
		$_SESSION['user']['member']['members_selected'] = false;
	}
	
	if( $unselect == 1 )
	{
		$_SESSION['user']['member']['members_selected'] = false;
	}
	
	elseif( $_SESSION['user']['member']['members_selected'] == false )
	{
		
		if( $intSubmitted == 1 )
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateMemberUser($int_member_hidden_input_1),'First Member Selection');
			Validation\CreateErrorMessage(Validation\ValidateMemberUser($int_member_hidden_input_2),'Second Member Selection');
			
			if( $int_member_hidden_input_1 == $int_member_hidden_input_2 )
			{
				Validation\CreateErrorMessage(' same as First Member Selected!','Second Member Selection');
			}
			
			if($blnIsGood)
			{
				$Message = 'good';
				$_SESSION['user']['member']['members_selected'] = true;
				$_SESSION['user']['member']['member']['1'] = Membership\Member::LoadMember($int_member_hidden_input_1);
				$_SESSION['user']['member']['member']['2'] =  Membership\Member::LoadMember($int_member_hidden_input_2);
				
				//load staff records
				$_SESSION['user']['staff']['staff']['1'] = Membership\Staff::LoadStaff($int_member_hidden_input_1);
				$_SESSION['user']['staff']['staff']['2'] =  Membership\Staff::LoadStaff($int_member_hidden_input_2);
				
			} else {
				$Message = 'bad';
			}
		}
	} 
	
	if($_SESSION['user']['member']['members_selected'] == true)
	{
		$Back = '<a href="'.$lnk_merge_members.'&unselect=1">Unselect Members</a>';
		
		$Member1 = $_SESSION['user']['member']['member']['1'];
		$Member2 = $_SESSION['user']['member']['member']['2'];
		
		$Staff1 = $_SESSION['user']['staff']['staff']['1'];
		$Staff2 = $_SESSION['user']['staff']['staff']['2'];
		
		$two_staff = ( $Staff1 != false and $Staff2 != false );
		
		$one_staff = ( !$two_staff and ( $Staff1 != false or $Staff2 != false  ) );
		
		if( $one_staff )
		{
			if( $Staff1 != false )
			{
				$sole_staff = $Staff1;
			} else {
				$sole_staff = $Staff2;
			}
		}
		
		if( $intSubmitted == 2 )
		{
			
			$member_to_keep = funRqScpVar('member','');
			$staff_to_keep = funRqScpVar('staff','');
			
			if( $Member1->GetUserID() == $member_to_keep )
			{
				$member_to_destroy = $Member2->GetUserID();
			} else {
				$member_to_destroy = $Member1->GetUserID();
			}
			
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateMemberUser($member_to_keep),'Member to keep');
			Validation\CreateErrorMessage(Validation\ValidateMemberUser($member_to_destroy),'Member to destroy');
			
			if( $staff_to_keep != '')
			{
				Validation\CreateErrorMessage(Validation\ValidateStaffUser($staff_to_keep),'Staff to keep');
			}
			
			if($blnIsGood)
			{
				
				
				\Membership\Member::MergeMembers($member_to_keep,$member_to_destroy,$staff_to_keep);
				
				$final_member = Membership\Member::LoadMember($member_to_keep);
				$final_staff = Membership\Staff::LoadStaff($member_to_keep);
				
				//merge records
			} else {
				$Message = 'bad';
			}
		}
		
	}
	
	
?>