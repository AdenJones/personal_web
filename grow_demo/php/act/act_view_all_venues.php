<?php
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_venue = '<a href="'.$lnk_add_edit_venue.'">Add Venue</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_venue = '';
		$Heading = '';
		$secure = false;
	}
	
	$ActiveVenues = Business\Venue::LoadAllVenues(true);
	$ArchivedVenues =  Business\Venue::LoadAllVenues(false);
	
?>