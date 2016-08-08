<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$UserID = funRqScpVar('id_user','');
	$StaffRegionID = funRqScpVar('id_staff_region','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.($this_year + 1);
	$bad_user = $UserID == '';
	$bad_staff_region = false;
	
	$arr_regions = Business\getAllRegionsForDropDown()->fetchAll();
	
	if( !$bad_user )
	{
		
		$User = Membership\User::UniversalMemberLoader($UserID);
				
		//jump out if bad user id is entered
		if($User == NULL)
		{
			$bad_user = true;
		} 
			
		if( !$bad_user )
		{
			
			if($StaffRegionID != '')
			{
				$StaffRegion = Business\StaffRegion::LoadStaffRegion($StaffRegionID);
						
				//jump out if bad user id is entered
				if($StaffRegion == NULL)
				{
					$bad_staff_region = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $User->GetUserID() != $StaffRegion->GetUserID() ) //check to make sure URL values haven't been screwed with.
					{
						$bad_staff_region = true;
					}
				}
			}
			
			if(!$bad_staff_region)
			{
			
				//If we are dealing with an edit and we have a good person id
				if($StaffRegionID != '' )
				{
					$RegionID = funRqScpVar('RegionID',$StaffRegion->GetRegionID());
						
					$StartDate = funRqScpVar('StartDate',funDateFormat($StaffRegion->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($StaffRegion->GetEndDate(),'d/m/Y'));
					
				
				} else {
					
					$RegionID = funRqScpVar('RegionID','');
						
					$StartDate = funRqScpVar('StartDate','');
					$EndDate = funRqScpVar('EndDate','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					Validation\CreateErrorMessage(Validation\ValidateRegion($RegionID),'Region');
						
					Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
					Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
					
							
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$SafeSD = funSfDateStr($StartDate);
						$SafeED = funSfDateStr($EndDate);
						
						if($StaffRegionID != '')
						{
							//update the table
							$StaffRegion->UpdateStaffRegion($RegionID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$StaffRegion->GetStaffRegionID(),CHANGE_UPDATE,'tbl_staffs_regions');
							
						} else {
							$StaffRegion = Business\StaffRegion::CreateStaffRegion($UserID,$RegionID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$StaffRegion->GetStaffRegionID(),CHANGE_INSERT,'tbl_staffs_regions');
						}
						
						$return_url = $lnk_view_staff_regions_secure.'&id_user='.$UserID;
						
						//redirect to view page
						header( "Location: $return_url" );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this staff region record!';
					}
				} // end if is good
			} // end if is bad group region
		}// end if not a bad group check 2
	} // bad group check 1
	
	
?>