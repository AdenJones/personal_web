<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$user_id = funRqScpVar('id_user','');
	$state_activity_date_id = funRqScpVar('state_activity_date_id','');
	$submitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_person = $user_id == '';
	$bad_activity = false;
	
	//get all states
	$arr_branches = getBranches()->fetchAll();
	
	if(!$bad_person)
	{
		$staff = Membership\Staff::LoadStaff($user_id);
		
		//jump out if bad user id is entered
		if(!$staff)
		{
			$bad_person = true;
			
		}
		elseif($staff->GetUserTypeName() != $StateUser)
		{
			$bad_person = true;
		}
		
		if(!$bad_person)
		{
		
			if($state_activity_date_id != '')
			{
				$activity = Business\StateUserActivityDates::load_state_activity($state_activity_date_id);
						
				//jump out if bad user id is entered
				if($activity == NULL)
				{
					$bad_activity = true;
				} else {
					
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $staff->GetUserID() != $activity->get_user_id() ) //check to make sure URL values haven't been screwed with.
					{
						$bad_activity = true;
					}
					
				}
			}
			
			
			if( !$bad_activity )
			{
			
				if($state_activity_date_id != '' )
				{
					$start_date = funRqScpVar('start_date',funDateFormat($activity->get_start_date(),'d/m/Y'));
					$end_date = funRqScpVar('end_date',funDateFormat($activity->get_end_date(),'d/m/Y'));
					$branch = funRqScpVar('branch',$activity->get_branch_id());
					
				} else {
					
					$start_date = funRqScpVar('start_date','');
					$end_date = funRqScpVar('end_date','');
					$branch = funRqScpVar('branch','');
					
				}
				
				//if the form has been submitted
				if($submitted == 1)
				{
					$blnIsGood = true;
					
					Validation\CreateErrorMessage(Validation\ValidateDate($start_date,true),'Start Date');
					Validation\CreateErrorMessage(Validation\ValidateDate($end_date),'End Date');
					Validation\CreateErrorMessage(Validation\ValidateBranch($branch),'Branch');
					
												
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$SafeSD = funSfDateStr($start_date);
						$SafeED = funSfDateStr($end_date);
						
						if($activity != '')
						{
							//update the table
							$activity->update($SafeSD,$SafeED,$branch);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$activity->get_state_activity_date_id(),CHANGE_UPDATE,'tbl_state_users_state_activity_dates');
							
						} else {
							$new_activity = Business\StateUserActivityDates::create_state_user_activity($user_id,$SafeSD,$SafeED,$branch);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$new_activity->get_state_activity_date_id(),CHANGE_INSERT,'tbl_state_users_state_activity_dates');
						}
						
						$url_return = $lnk_view_state_user_states_secure.'&id_user='.$user_id;
						
						//redirect to view page
						header( "Location: $url_return " );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this state activity record!';
					}
				}//End if submitted
			}//End bad activity id check
		} //End Second bad person check
	}//End not bad person
	
	
	
?>