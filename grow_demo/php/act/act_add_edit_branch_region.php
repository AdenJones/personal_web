<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$GroupRegionID = funRqScpVar('id_group_region','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_group = $GroupID == '';
	$bad_branch_region = false;
	
	
	//get lists of job classifications
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_regions = Business\get_all_regions_for_drop_down_by_state($Staff->GetUserID())->fetchAll();
		$arr_branches = Business\getAllBranchesForDropDownByState($Staff->GetUserID())->fetchAll();
	} else {
		$arr_regions = Business\getAllRegionsForDropDown()->fetchAll();
		$arr_branches = getBranches()->fetchAll();
	}
	
	if( !$bad_group )
	{
		
		$Group = Business\Group::LoadGroup($GroupID);
				
		//jump out if bad user id is entered
		if($Group == NULL)
		{
			$bad_group = true;
		} else {
			//State User security
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				if(!$Staff->IsMyGroup($GroupID))
				{
					$bad_group = true;
				}
			}
		}
			
		if( !$bad_group )
		{
			
			if($GroupRegionID != '')
			{
				$BranchRegion = Business\GroupRegion::LoadGroupRegion($GroupRegionID);
						
				//jump out if bad user id is entered
				if($BranchRegion == NULL)
				{
					$bad_branch_region = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $Group->IsMyGroupRegion($GroupRegionID) ) //check to make sure URL values haven't been screwed with.
					{
						$bad_branch_region = true;
					}
				}
			}
			
			if(!$bad_branch_region)
			{
			
				//If we are dealing with an edit and we have a good person id
				if($GroupRegionID != '' )
				{
					
					$RegionID = funRqScpVar('RegionID',$BranchRegion->GetRegionID());
					$BranchID = funRqScpVar('BranchID',$BranchRegion->GetBranchID());
					
					if( $RegionID != '' )
					{
						$BranchRegionDefault = 'by_region';
					}
					else
					{
						$BranchRegionDefault = 'by_branch';
					}
					
					$str_by_branch_or_region = funRqScpVar('str_by_branch_or_region',$BranchRegionDefault);
					
					
					//exclude irrelevant submitted variables
					if( $str_by_branch_or_region == 'by_region' )
					{
						$RegionID = $RegionID;
						$BranchID = '';
					}
					else
					{
						$RegionID = '';
						$BranchID = $BranchID;
					}
					
					$StartDate = funRqScpVar('StartDate',funDateFormat($BranchRegion->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($BranchRegion->GetEndDate(),'d/m/Y'));
					
				
				} else {
					
					$str_by_branch_or_region = funRqScpVar('str_by_branch_or_region','by_region');
					
					if($str_by_branch_or_region == 'by_region')
					{
						$RegionID = funRqScpVar('RegionID','');
						$BranchID = '';
					}
					else
					{
						$RegionID = '';
						$BranchID = funRqScpVar('BranchID','');
					}
					
					$StartDate = funRqScpVar('StartDate','');
					$EndDate = funRqScpVar('EndDate','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					if($str_by_branch_or_region == 'by_region' )
					{
						Validation\CreateErrorMessage(Validation\ValidateRegion($RegionID),'Region Selection');
					}
					else
					{
						Validation\CreateErrorMessage(Validation\ValidateBranch($BranchID),'Branch Selection');
					}
					
					Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
					Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
					
							
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$SafeSD = funSfDateStr($StartDate);
						$SafeED = funSfDateStr($EndDate);
						 
						if($GroupRegionID != '')
						{
							//update the table
							$BranchRegion->UpdateBranchRegion($BranchID,$RegionID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$BranchRegion->GetGroupRegionID(),CHANGE_UPDATE,'tbl_groups_regions');
							
							
						} else {
							$BranchRegion = Business\GroupRegion::CreateBranchRegion($GroupID,$RegionID,$BranchID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$BranchRegion->GetGroupRegionID(),CHANGE_INSERT,'tbl_groups_regions');
						}
						
						$return_url = $_SESSION['ReturnAddress'];
						
						//redirect to view page
						header( "Location: $return_url" );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this branch region record!';
					}
				} // end if is good
			} // end if is bad group region
		}// end if not a bad group check 2
	} // bad group check 1
	
	
?>