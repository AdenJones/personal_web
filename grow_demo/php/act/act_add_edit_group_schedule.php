<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$GroupScheduleID = funRqScpVar('id_group_schedule','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_group = $GroupID == '';
	$bad_group_schedule = false;
	
	//get lists 
		
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
				$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
			
				if(!$Staff->IsMyGroup($GroupID))
				{
					$bad_group = true;
				}
			
			}
		}
			
		if( !$bad_group )
		{
			
			if($GroupScheduleID != '')
			{
				$GroupSchedule = Business\GroupSchedule::LoadGroupSchedule($GroupScheduleID);
						
				//jump out if bad user id is entered
				if($GroupSchedule == NULL)
				{
					$bad_group_schedule = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $GroupID != $GroupSchedule->GetGroupID() ) //check to make sure URL values haven't been screwed with.
					{
						$bad_group_schedule = true;
					}
				}
			}
			
			if(!$bad_group_schedule)
			{
			
				//If we are dealing with an edit and we have a good person id
				if($GroupScheduleID != '' )
				{
					
					$mxd_recurrencestr_period_div = funRqScpVar('mxd_recurrencestr_period_div',$GroupSchedule->GetRecurrencyString());
					$mxd_recurrenceint_period_div = funRqScpVar('mxd_recurrenceint_period_div',$GroupSchedule->GetRecurrencyInt());	
					
					$StartDate = funRqScpVar('StartDate',funDateFormat($GroupSchedule->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($GroupSchedule->GetEndDate(),'d/m/Y'));
					
					$StartTime = funRqScpVar('StartTime',funTimeFormat($GroupSchedule->GetStartTime()));
					$EndTime = funRqScpVar('EndTime',funTimeFormat($GroupSchedule->GetEndTime()));
				
				} else {
					
					$mxd_recurrencestr_period_div = funRqScpVar('mxd_recurrencestr_period_div','');
					$mxd_recurrenceint_period_div = funRqScpVar('mxd_recurrenceint_period_div',1);	
					
					$StartDate = funRqScpVar('StartDate','');
					$EndDate = funRqScpVar('EndDate','');
					
					$StartTime = funRqScpVar('StartTime','');
					$EndTime = funRqScpVar('EndTime','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					
					Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
					Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
					
					Validation\CreateErrorMessage(Validation\ValidatePeriodSelect($mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div),'Recurrence');
						
					Validation\CreateErrorMessage(Validation\ValidateTime($StartTime,true),'Start Time');
					Validation\CreateErrorMessage(Validation\ValidateTime($EndTime,true),'End Time');
					
					if($blnIsGood) //only validate times against each other if times are good
					{
						Validation\CreateErrorMessage(Validation\ValidateTimeRange($StartTime,$EndTime,true),'End Time');
					}
					
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$SafeSD = funSfDateStr($StartDate);
						$SafeED = funSfDateStr($EndDate);
						
						if($GroupScheduleID != '')
						{
							//update the table
							$GroupSchedule->UpdateGroupSchedule($SafeSD,$SafeED,$mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div,$StartTime,$EndTime);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupSchedule->GetGroupScheduleID(),CHANGE_UPDATE,'tbl_groups_schedules');
							
							
						} else {
							$GroupSchedule = Business\GroupSchedule::CreateGroupSchedule($GroupID,$SafeSD,$SafeED,$mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div,$StartTime,$EndTime);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupSchedule->GetGroupScheduleID(),CHANGE_INSERT,'tbl_groups_schedules');
						}
						
						$return_url = $lnk_view_group_schedule_secure.'&id_group='.$GroupID;
						
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