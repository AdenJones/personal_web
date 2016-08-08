<?php
	
	$months_till_reminder = 11;
	
	global $UserTypeCatStaff;
	global $UserTypeCatVol;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$StaffDuePoliceChecks = array();
		$VolDuePoliceChecks = \Membership\Staff::LoadStaffVolDuePoliceChecks($months_till_reminder,$UserTypeCatVol);
		
	} else {
		$StaffDuePoliceChecks = \Membership\Staff::LoadStaffVolDuePoliceChecks($months_till_reminder,$UserTypeCatStaff);
		$VolDuePoliceChecks = \Membership\Staff::LoadStaffVolDuePoliceChecks($months_till_reminder,$UserTypeCatVol);
	}
	
	
	
?>