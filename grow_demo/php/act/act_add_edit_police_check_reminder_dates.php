<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$UserID = funRqScpVar('id_user','');
	$PCRMID = funRqScpVar('id_pcrmid','');
	$intSubmitted = funRqScpVar('form_submitted','');
	$ReturnTo = funRqScpVarNonSafe('return_to','');
	$ReturnImmediate = funRqScpVarNonSafe('return_immediate','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_person = $UserID == '';
	$bad_pcrmid = false;
	
	if( !$bad_person )
	{
		
		$thisStaff = \Membership\Staff::LoadStaff($UserID);
	
		$bad_person = ($thisStaff == false);
		
		if( !$bad_person )
		{
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
				
				$arrActiveVolunteers = Membership\Staff::LoadVolunteersByBranch($Staff->GetBranch()->GetBranchAbbreviation());
				$arrArchivedVolunteers = Membership\Staff::LoadArchivedVolunteersByBranch($Staff->GetBranch()->GetBranchAbbreviation());
				
			} else {
				$arrActiveStaff = Membership\Staff::LoadActiveStaff();
				$arrArchivedStaff = Membership\Staff::LoadArchivedStaff();
				$arrActiveVolunteers = Membership\Staff::LoadActiveVolunteers();
				$arrArchivedVolunteers =  Membership\Staff::LoadArchivedVolunteers();
			}
			
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$arrMerged = array_merge($arrActiveVolunteers,$arrArchivedVolunteers);
			} else {
				$arrMerged = array_merge($arrActiveStaff,$arrArchivedStaff,$arrActiveVolunteers,$arrArchivedVolunteers);
			}
			
			$found = false;
			
			foreach($arrMerged as $Person)
			{
				if($thisStaff->GetUserID() == $Person->GetUserID())
				{
					$found = true;
					break;
				}
			}
			
			$bad_person = !$found;
		}
		
	}
	
	if(!$bad_person)
	{
		$staff = \Membership\Staff::LoadStaff($UserID);
		
		//jump out if bad user id is entered
		if(!$staff)
		{
			$bad_person = true;
			
		}
		
		if(!$bad_person)
		{
		
			if($PCRMID != '')
			{
				$PCRM = Membership\ReminderDate::LoadReminder($PCRMID);
						
				//jump out if bad user id is entered
				if($PCRM == NULL)
				{
					$bad_pcrmid = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $staff->GetUserID() != $PCRM->GetUserID() ) //check to make sure URL values haven't been screwed with.
					{
						$bad_pcrmid = true;
						
						if( $PCRM->GetTypeID() != $PoliceCheck )
						{
							$bad_pcrmid = true;
						}
					}
					
				}
			}
			
			
			if( !$bad_pcrmid )
			{
			
				if($PCRMID != '' )
				{
					$thisDate = funRqScpVar('thisDate',funDateFormat($PCRM->GetDate(),'d/m/Y'));
					
				} else {
					
					$thisDate = funRqScpVar('thisDate','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					Validation\CreateErrorMessage(Validation\ValidateDate($thisDate,true),'Date');
												
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$thisSafeDate = funSfDateStr($thisDate);
						
						if($PCRMID != '')
						{
							//update the table
							$PCRM->UpdateReminderDate($thisSafeDate);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$PCRM->GetReminderID(),CHANGE_UPDATE,'tbl_reminder_dates');
							
						} else {
							
							$newPCRM = Membership\ReminderDate::CreatePoliceCheckRecord($UserID,$thisSafeDate);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newPCRM->GetReminderID(),CHANGE_INSERT,'tbl_reminder_dates');
						}
						
						$url_return = $lnk_view_staff_reminder_dates.'&id_user='.$UserID;
						
						//redirect to view page
						header( "Location: $url_return " );
						
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this police check record!';
					}
				}//End if submitted
			}//End bad activity id check
		} //End Second bad person check
	}//End not bad person
	
	
	
?>