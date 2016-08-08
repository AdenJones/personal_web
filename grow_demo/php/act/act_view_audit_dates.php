<?php
	
	
	
	$VenueID = funRqScpVar('id_venue','');
		
	$BadVenue = $VenueID == '';
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		
		$Heading = 'Secure';
		$secure = true;
		
		global $lnk_add_edit_audit_date;
		global $lnk_view_all_venues_secure;
		
		$add_audit_date = '<a href="'.$lnk_add_edit_audit_date.'&id_venue='.$VenueID.'">Add Audit Date</a>';
		
		$Back = '<a href="'.$lnk_view_all_venues_secure.'">View All Venues</a>';
		
	} else {
		
		$Heading = '';
		$secure = false;
		
		global $lnk_view_all_venues;
		
		$add_audit_date = '';
		$Back = '<a href="'.$lnk_view_all_venues.'">View All Venues</a>';
	}
	
	if(!$BadVenue)
	{
		$Venue = Business\Venue::LoadVenue($VenueID);
				
		//jump out if bad user id is entered
		if(!$Venue)
		{
			$BadVenue = true;
		}
	}
	
	//all further processing restricted by bad user detection
	if(!$BadVenue)
	{
		$VenueAuditDates = $Venue->LoadAuditDates();
	}
	
?>