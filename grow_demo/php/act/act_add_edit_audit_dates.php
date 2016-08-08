<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$VenueID = funRqScpVar('id_venue','');
	$AuditDateID = funRqScpVar('id_audit_date','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$BadVenue =( $VenueID == '' and $AuditDateID == '');
	$BadAuditDate = false;
	$IsEdit = false;
	
	if(!$BadVenue)
	{
		
		if( $AuditDateID != '' )
		{
			$AuditDate = \Business\VenueAuditDate::Load($AuditDateID);
			
			if( $AuditDate == NULL )
			{
				$BadAuditDate = true;
			} else {
				$Venue = \Business\Venue::LoadVenue($AuditDate->GetVenueID());
				
				$IsEdit = true;
				$page_name = $page_edit_name;
			}
		}
		
		if(!$IsEdit and !$BadAuditDate)
		{
			$BadVenue = $VenueID == '';
			
			if( !$BadVenue )
			{
				$Venue = \Business\Venue::LoadVenue($VenueID);
				
				if( $Venue == NULL )
				{
					$BadVenue = true;
				} 
			}
		
		}
		
		if(!$BadVenue and !$BadAuditDate )
		{
			
			$ReturnString = '<a href="'.$lnk_view_audit_dates_secure.'&id_venue='.$Venue->GetVenueID().'">'.$Venue->GetName().'</a>';
			
			if($IsEdit)
			{
				
				
				$DateAuditDate = funRqScpVar('DateAuditDate',funDateFormat($AuditDate->GetAuditDate(),'d/m/Y'));
				$Notes = funRqScpVar('Notes',$AuditDate->GetNotes());
				
				if($intSubmitted == 1)
				{
					$Complete = funRqScpVar('Complete',$false);
				} else {
					$Complete = $AuditDate->GetComplete();
				}
				
			} else {
				
				$DateAuditDate = funRqScpVar('DateAuditDate','');
				$Notes = funRqScpVar('Notes','');
				$Complete = funRqScpVar('Complete',0);
				
			}
			
			//if the form has been submitted
			if($intSubmitted == 1)
			{
				$blnIsGood = true;
				
				Validation\CreateErrorMessage(Validation\ValidateDate($DateAuditDate,true),'Audit Date');
				Validation\CreateErrorMessage(Validation\ValidateString($Notes,0,3000),'Notes');
				Validation\CreateErrorMessage(Validation\ValidateBoolean($Complete),'Complete');
											
				//if all values are good
				if($blnIsGood)
				{
					//create safe dates
					$SafeAD = funSfDateStr($DateAuditDate);
					
					if($IsEdit)
					{
						//update the table
						$AuditDate->Update($SafeAD,$Notes,$Complete);
						
						$ReturnVenue = $AuditDate->GetVenueID();
						
						Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$AuditDate->GetVenueAuditID(),CHANGE_UPDATE,'tbl_venue_audit_dates');
						
						
					} else {
						$newAuditDate = Business\VenueAuditDate::Create($VenueID,$SafeAD,$Notes,$Complete);
						
						$ReturnVenue = $VenueID;
						
						Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newAuditDate->GetVenueAuditID(),CHANGE_INSERT,'tbl_venue_audit_dates');
					}
					
					$url_return = $lnk_view_audit_dates_secure.'&id_venue='.$ReturnVenue;;
					
					//redirect to view page
					header( "Location: $url_return " );
					//ensure no further processing is performed
					exit;
				} else {
					$msg_general_notifier = 'There are errors in this venue audit date record!';
				}
			}//End if submitted
		} //End Second bad person check
	}//End not bad person
	
	
	
?>