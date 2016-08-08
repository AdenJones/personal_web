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
	$bad_group_region = false;
	
	//get lists of job classifications
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_regions = Business\get_all_regions_for_drop_down_by_state($Staff->GetUserID())->fetchAll();
	} else {
		$arr_regions = Business\getAllRegionsForDropDown()->fetchAll();
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
				
				$GroupsLastRegion = $Group->LoadGroupsCurrentRegion();
				
				if( $Group->LoadGroupsCurrentRegion() != NULL)
				{
					if( !$Staff->has_region($GroupsLastRegion->GetRegionID()) )
					{
						$bad_group = true;
					}
				}
			}
		}
			
		if( !$bad_group )
		{
			
			if($GroupRegionID != '')
			{
				$GroupRegion = Business\GroupRegion::LoadGroupRegion($GroupRegionID);
						
				//jump out if bad user id is entered
				if($GroupRegion == NULL)
				{
					$bad_group_region = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $Group->IsMyGroupRegion($GroupRegionID) ) //check to make sure URL values haven't been screwed with.
					{
						$bad_group_region = true;
					}
				}
			}
			
			if(!$bad_group_region)
			{
			
				//If we are dealing with an edit and we have a good person id
				if($GroupRegionID != '' )
				{
					$RegionID = funRqScpVar('RegionID',$GroupRegion->GetRegionID());
						
					$StartDate = funRqScpVar('StartDate',funDateFormat($GroupRegion->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($GroupRegion->GetEndDate(),'d/m/Y'));
					
				
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
						
						if($GroupRegionID != '')
						{
							//update the table
							$GroupRegion->UpdateGroupRegion($RegionID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupRegion->GetGroupRegionID(),CHANGE_UPDATE,'tbl_groups_regions');
							
							
						} else {
							$GroupRegion = Business\GroupRegion::CreateGroupRegion($GroupID,$RegionID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupRegion->GetGroupRegionID(),CHANGE_INSERT,'tbl_groups_regions');
						}
						
						$return_url = $lnk_view_groups_regions_secure.'&id_group='.$GroupID;
						
						//redirect to view page
						header( "Location: $return_url" );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this group region record!';
					}
				} // end if is good
			} // end if is bad group region
		}// end if not a bad group check 2
	} // bad group check 1
	
	
?>