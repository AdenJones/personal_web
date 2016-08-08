<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$RegionID = funRqScpVar('id_region','');
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_region = false;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$thisStaff = \Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_branches = get_branches_state_user($_SESSION['User']->GetUserID())->fetchAll();
		
	} else {
		$arr_branches = getBranches()->fetchAll();
	}
	
	//get lists of branches
	
	
	if($RegionID != '')
	{
		$Region = Business\Region::LoadRegion($RegionID);
		
		
				
		//jump out if bad user id is entered
		if($Region == NULL)
		{
			$bad_region = true;
		} else {
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
			
			if( $_SESSION['User']->GetUserTypeName() == $StateUser and !$thisStaff->has_region($RegionID) )
			{
				$bad_region = true;
			}
		}
	}
	
	if( !$bad_region )
	{
		
		//If we are dealing with an edit and we have a good person id
		if($RegionID != '' )
		{
					
			$RegionName = funRqScpVar('RegionName',$Region->GetRegionName());
			$Branch = funRqScpVar('Branch',$Region->GetBranchID());
				
			$StartDate = funRqScpVar('StartDate',funDateFormat($Region->GetStartDate(),'d/m/Y'));
			$EndDate = funRqScpVar('EndDate',funDateFormat($Region->GetEndDate(),'d/m/Y'));
			
		
		} else {
			
			$RegionName = funRqScpVar('RegionName','');
			$Branch = funRqScpVar('Branch','');
				
			$StartDate = funRqScpVar('StartDate','');
			$EndDate = funRqScpVar('EndDate','');
			
		}
		
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateString($RegionName,1,150),'Region Name');
			Validation\CreateErrorMessage(Validation\ValidateBranch($Branch),'Branch');
			
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				if( !$thisStaff->has_branch_by_branch_id(intval($Branch)) )
				{
					Validation\CreateErrorMessage(' Invalid Branch Selected!','Branch');
				}
			}
			
			Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
			Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
			
					
			//if all values are good
			if($blnIsGood)
			{
				//create safe dates
				$SafeSD = funSfDateStr($StartDate);
				$SafeED = funSfDateStr($EndDate);
				
				if($RegionID != '')
				{
					//update the table
					$Region->UpdateRegion($RegionName,$Branch,$SafeSD,$SafeED);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Region->GetRegionID(),CHANGE_UPDATE,'tbl_regions');
					
				} else {
					$Region = Business\Region::CreateRegion($RegionName,$Branch,$SafeSD,$SafeED);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Region->GetRegionID(),CHANGE_INSERT,'tbl_regions');
				}
				
				//redirect to view page
				header( "Location: $lnk_view_regions_secure" );
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this region record!';
			}
		}
	}// end if not a bad region id
	
?>