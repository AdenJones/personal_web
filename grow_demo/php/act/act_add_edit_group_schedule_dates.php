<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$GroupScheduleDateID = funRqScpVar('id_grp_sch_date','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.($this_year+1);
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
			
			if($GroupScheduleDateID != '')
			{
				$GroupScheduleDate = Business\GroupScheduledDate::LoadGroupScheduledDate($GroupScheduleDateID);
						
				//jump out if bad user id is entered
				if($GroupScheduleDate == NULL)
				{
					$bad_group_schedule = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $GroupID != $GroupScheduleDate->GetGroupID() ) //check to make sure URL values haven't been screwed with.
					{
						$bad_group_schedule = true;
					}
				}
			}
			
			if(!$bad_group_schedule)
			{
			
				//If we are dealing with an edit and we have a good person id
				if($GroupScheduleDateID != '' )
				{
					
					$Date = funRqScpVar('Date',funDateFormat($GroupScheduleDate->GetDate(),'d/m/Y'));
					
					$StartTime = funRqScpVar('StartTime',funTimeFormat($GroupScheduleDate->GetStartTime()));
					$EndTime = funRqScpVar('EndTime',funTimeFormat($GroupScheduleDate->GetEndTime()));
				
				} else {
					
					$Date = funRqScpVar('Date','');
					
					$StartTime = funRqScpVar('StartTime','');
					$EndTime = funRqScpVar('EndTime','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					
					Validation\CreateErrorMessage(Validation\ValidateDate($Date,true),'Date');
					
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
						$SafeDate = funSfDateStr($Date);
						
						if($GroupScheduleDateID != '')
						{
							//update the table
							$GroupScheduleDate->UpdateGroupScheduledDate($SafeDate,$StartTime,$EndTime);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupScheduleDate->GetGroupScheduleDateID(),CHANGE_UPDATE,'tbl_groups_scheduled_dates');
							
							
						} else {
							
							$GroupScheduleDate = Business\GroupScheduledDate::CreateGroupScheduledDate($GroupID,$SafeDate,$StartTime,$EndTime);
							
							//var_dump( $GroupScheduleDate );
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupScheduleDate->GetGroupScheduleDateID(),CHANGE_INSERT,'tbl_groups_scheduled_dates');
						}
						
						$return_url = $lnk_view_group_schedule_secure.'&id_group='.$GroupID;
						
						//redirect to view page
						header( "Location: $return_url" );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this group date schedule record!';
					}
				} // end if is good
			} // end if is bad group region
		}// end if not a bad group check 2
	} // bad group check 1
	
	
?>