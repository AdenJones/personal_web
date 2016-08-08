<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$UserID = funRqScpVar('id_user','');
	$CommittedID = funRqScpVar('id_committed','');
	$intSubmitted = funRqScpVar('form_submitted','');
	$ReturnTo = funRqScpVarNonSafe('return_to','');
	$ReturnImmediate = funRqScpVarNonSafe('return_immediate','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_person = $UserID == '';
	$bad_committed = false;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
	} 
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
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
		
			if($CommittedID != '')
			{
				$Committed = Membership\MemberCommittedDates::LoadCommittedDate($CommittedID);
						
				//jump out if bad user id is entered
				if($Committed == NULL)
				{
					$bad_committed = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $member->GetUserID() != $Committed->GetUserID() ) //check to make sure URL values haven't been screwed with.
					{
						$bad_committed = true;
					}
					
				}
			}
			
			
			if( !$bad_committed )
			{
			
				if($CommittedID != '' )
				{
					$StartDate = funRqScpVar('StartDate',funDateFormat($Committed->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($Committed->GetEndDate(),'d/m/Y'));
					
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
						
						if($CommittedID != '')
						{
							//update the table
							$Committed->UpdateMemberCommitted($SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Committed->GetCommittedID(),CHANGE_UPDATE,'tbl_member_committed_dates');
							
						} else {
							$newCommitteed = Membership\MemberCommittedDates::CreateCommittedDateComplete($UserID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newCommitteed->GetCommittedID(),CHANGE_INSERT,'tbl_member_committed_dates');
						}
						
						$url_return = $lnk_view_member_dates.'&id_user='.$UserID.'&return_to='.urlencode($ReturnTo);
						
						if($_SESSION['return_to'] == 'view_user')
						{
							$url_return = $lnk_view_user.'&id_user='.$UserID;
							
							header( "Location: $url_return " );
							
						} else {
							if( $ReturnImmediate == 1 )
							{
								header( "Location: $ReturnTo " );
							} else {
								header( "Location: $url_return " );
							}
						}
						
						
						
						//redirect to view page
						
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