<?php
	
	$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
	if( $allow_deletes )
	{
		$DelRegionID = funRqScpVar('int_del_rgn_id','');
		
		if( $DelRegionID != '' )
		{
			$RegionToDel = \Business\Region::LoadRegion($DelRegionID);
			
			if( $RegionToDel != NULL )
			{
				Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$RegionToDel->GetRegionID(),CHANGE_DELETE,'tbl_regions');
				
				$RegionToDel->Delete();
			}
		}
	}
	
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_region = '<a href="'.$lnk_add_edit_region.'">Add Region</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_region = '';
		$Heading = '';
		$secure = false;
	}
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		
		$thisStaff = \Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		if( $thisStaff == false )
		{
			$Regions = array(); //not the best error management but, oh well.
		} else {
			$Regions = \Business\Region::load_regions_by_branches($thisStaff->GetUserID());
		}
		
		
	} else {
		$Regions = Business\Region::LoadRegions();
	}
	
	
	
	
	
?>