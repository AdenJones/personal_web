<?php 
/* Action file for signing up */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$VenueID = funRqScpVar('id_venue','');
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_venue = false;	
	$arr_states = getStates()->fetchAll();
	$is_edit = false;
	
	if($VenueID != '')
	{
		$Venue = Business\Venue::LoadVenue($VenueID);
				
		//jump out if bad user id is entered
		if($Venue == NULL)
		{
			$bad_venue = true;
		} else {
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
			$is_edit = true;
		}
	}
	
	if(!$bad_venue)
	{
		if($VenueID != '')
		{
			$Name = funRqScpVar('Name',$Venue->GetName());
			$Address = funRqScpVar('Address',$Venue->GetAddress());
			$Suburb = funRqScpVar('Suburb',$Venue->GetSuburb());
			$State = funRqScpVar('State',$Venue->GetState());
			$PostCode = funRqScpVar('PostCode',$Venue->GetPostCode());
			$Comments = funRqScpVar('Comments',$Venue->GetComments());
			if($intSubmitted == 1)
			{
				$Contract = funRqScpVar('Contract',0);
			} else {
				$Contract = funRqScpVar('Contract',$Venue->GetContract());
			}
			$StartDate = funRqScpVar('StartDate',funDateFormat($Venue->GetStartDate(),'d/m/Y'));
			$EndDate = funRqScpVar('EndDate',funDateFormat($Venue->GetEndDate(),'d/m/Y'));
			
		} else {
			$Name = funRqScpVar('Name','');
			$Address = funRqScpVar('Address','');
			$Suburb = funRqScpVar('Suburb','');
			$State = funRqScpVar('State','');
			$PostCode = funRqScpVar('PostCode','');
			$Comments = funRqScpVar('Comments','');
			$Contract = funRqScpVar('Contract',0);
			$StartDate = funRqScpVar('StartDate','');
			$EndDate = funRqScpVar('EndDate','');
		}
		
	
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			
			$blnIsGood = true;
			
			Business\CreateErrorMessageVenueName($VenueID,Validation\ValidateString($Name,2,150),$Name,'Name',$is_edit);
			
			Validation\CreateErrorMessage(Validation\ValidateString($Address,0,150),'Address');
			Validation\CreateErrorMessage(Validation\ValidateString($Suburb,0,45),'Suburb');
			Validation\CreateErrorMessage(Validation\ValidateState($State),'State');
			Validation\CreateErrorMessage(Validation\ValidateString($PostCode,0,8),'PostCode');
			
			Validation\CreateErrorMessage(Validation\ValidateString($Comments,0,2000),'Comments');
			Validation\CreateErrorMessage(Validation\ValidateBoolean($Contract),'Contract');
			
			Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
			Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
			
			//if all values are good
			if($blnIsGood)
			{
				$SafeSD = funSfDateStr($StartDate);
				$SafeED = funSfDateStr($EndDate);
				
				$SafeContract = (int) $Contract;
				
				if($VenueID != '')
				{
					$Venue->UpdateVenue(
										$Name,
										$Address,
										$Suburb,
										$State,
										$PostCode,
										$Comments,
										$SafeContract,
										$SafeSD,
										$SafeED
										);
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Venue->GetVenueID(),CHANGE_UPDATE,'tbl_venues');
				} else {
					//create the venue
					$NewVenue = Business\Venue::CreateVenue(
															$Name,
															$Address,
															$Suburb,
															$State,
															$PostCode,
															$Comments,
															$SafeContract,
															$SafeSD,
															$SafeED
															);
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$NewVenue->GetVenueID(),CHANGE_INSERT,'tbl_venues');
				}
				
				//redirect to view page
				header( "Location: $lnk_view_all_venues_secure" );
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this record!';
			}
		} //End if submitted
	} // End if bad user
	
?>