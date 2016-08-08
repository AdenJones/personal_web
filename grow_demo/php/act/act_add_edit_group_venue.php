<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$GroupVenueID = funRqScpVar('id_group_venue','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_group = $GroupID == '';
	$bad_group_venue = false;
	
	//get lists of job classifications
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_venues = Business\get_all_venues_for_drop_down_by_state($Staff->GetUserID());
	} else {
		$arr_venues = Business\getAllVenuesForDropDown()->fetchAll();
	}
	
	
	
	if( !$bad_group )
	{
		
		$Group = Business\Group::LoadGroup($GroupID);
		
		//jump out if bad user id is entered
		if($Group == NULL)
		{
			$bad_group = true;
		} else {
			
			$NonGroupType = $Group->GetNonGroupType();
			
			if( $NonGroupType == NON_GROUP_TEAM )
			{
				$page_name = 'Add Team Venue';
				$page_edit_name = 'Edit Team Venue';
				$this_type = 'Team';
			}
			else
			{
				$this_type = 'Group';
			}
			
			//State User security
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				
				if( $_SESSION['User']->GetUserTypeName() == $StateUser )
				{
					if(!$Staff->IsMyGroup($GroupID))
					{
						$bad_group = true;
					}
				}
			}
		}
			
		if( !$bad_group )
		{
			
			if($GroupVenueID != '')
			{
				$GroupVenue = Business\GroupVenue::LoadGroupVenue($GroupVenueID);
						
				//jump out if bad user id is entered
				if($GroupVenue == NULL)
				{
					$bad_group_venue = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $Group->IsMyGroupVenue($GroupVenueID) ) //check to make sure URL values haven't been screwed with.
					{
						$bad_group_venue = true;
					}
				}
			}
			
			if(!$bad_group_venue)
			{
				
			
				//If we are dealing with an edit and we have a good person id
				if($GroupVenueID != '' )
				{
					$VenueID = funRqScpVar('VenueID',$GroupVenue->GetVenueID());
						
					$StartDate = funRqScpVar('StartDate',funDateFormat($GroupVenue->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($GroupVenue->GetEndDate(),'d/m/Y'));
					
				
				} else {
					
					$VenueID = funRqScpVar('VenueID','');
						
					$StartDate = funRqScpVar('StartDate','');
					$EndDate = funRqScpVar('EndDate','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					Validation\CreateErrorMessage(Validation\ValidateVenue($VenueID),'Venue');
						
					Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
					Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
					
							
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$SafeSD = funSfDateStr($StartDate);
						$SafeED = funSfDateStr($EndDate);
						
						if($GroupVenueID != '')
						{
							//update the table
							$GroupVenue->UpdateGroupVenue($VenueID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupVenue->GetGroupVenueID(),CHANGE_UPDATE,'tbl_groups_venues');
							
							
						} else {
							$GroupVenue = Business\GroupVenue::CreateGroupVenue($GroupID,$VenueID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupVenue->GetGroupVenueID(),CHANGE_INSERT,'tbl_groups_venues');
						}
						
						$return_url = $lnk_view_groups_venues_secure.'&id_group='.$GroupID;
						
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