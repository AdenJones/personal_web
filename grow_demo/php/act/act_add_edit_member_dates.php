<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$UserID = funRqScpVar('id_user','');
	$ActivityID = funRqScpVar('id_user_activity','');
	$intSubmitted = funRqScpVar('form_submitted','');
	$ReturnTo = funRqScpVarNonSafe('return_to','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_person = $UserID == '';
	$bad_activity = false;
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}
	
	global $MemberString;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
	} 
	
	if(!$bad_person)
	{
		$member = Membership\Member::LoadMember($UserID);
		
		//jump out if bad user id is entered
		if(!$member)
		{
			$bad_person = true;
			
		}
		
		if(!$bad_person)
		{
		
			if($ActivityID != '')
			{
				$Activity = Membership\UserActivityDates::LoadUserActivity($ActivityID);
						
				//jump out if bad user id is entered
				if($Activity == NULL)
				{
					$bad_activity = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $member->GetUserID() != $Activity->GetUserID() ) //check to make sure URL values haven't been screwed with.
					{
						$bad_activity = true;
					}
					
					if( $MemberString != $Activity->GetUserTypeString() )  //make sure we are dealing with a staff volunteer activity not a member
					{
						$bad_activity = true;
					}
				}
			}
			
			
			if( !$bad_activity )
			{
			
				if($ActivityID != '' )
				{
					$StartDate = funRqScpVar('StartDate',funDateFormat($Activity->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($Activity->GetEndDate(),'d/m/Y'));
					
				} else {
					
					$StartDate = funRqScpVar('StartDate','');
					$EndDate = funRqScpVar('EndDate','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
					Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
												
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$SafeSD = funSfDateStr($StartDate);
						$SafeED = funSfDateStr($EndDate);
						
						if($ActivityID != '')
						{
							//update the table
							$Activity->UpdateUserActivity($SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Activity->GetUserActivityID(),CHANGE_UPDATE,'tbl_user_activity_dates');
							
						} else {
							$newActivity = Membership\UserActivityDates::CreateUserActivity($UserID,$MemberString,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newActivity->GetUserActivityID(),CHANGE_INSERT,'tbl_user_activity_dates');
						}
						
						if( $_SESSION['return_to'] == 'view_user' )
						{
							$url_return = $lnk_view_user.'&id_user='.$UserID;
						} else {
						$url_return = $lnk_view_member_dates.'&id_user='.$UserID.'&return_to='.urlencode($ReturnTo);
						}
						//redirect to view page
						header( "Location: $url_return " );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this activity record!';
					}
				}//End if submitted
			}//End bad activity id check
		} //End Second bad person check
	}//End not bad person
	
	
	
?>