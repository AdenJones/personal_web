<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$TeamID = funRqScpVar('id_team','');
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_team = false;
	
	if($TeamID != '')
	{
		$Team = Business\Team::LoadTeam($TeamID);
				
		//jump out if bad user id is entered
		if($Team == NULL)
		{
			$bad_team = true;
						
		} else {
			
			//State User security
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
				
				if(!$Staff->IsMyGroup($TeamID))
				{
					$bad_team = true;
				}
			}
			
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
		}
	}
	
	if( !$bad_team )
	{
		
		//If we are dealing with an edit and we have a good person id
		if($TeamID != '' )
		{
					
			$TeamName = funRqScpVar('TeamName',$Team->GetGroupName());
			$StartDate = funRqScpVar('StartDate',funDateFormat($Team->GetStartDate(),'d/m/Y'));
			$EndDate = funRqScpVar('EndDate',funDateFormat($Team->GetEndDate(),'d/m/Y'));
			
		
		} else {
			
			$TeamName = funRqScpVar('TeamName','');
			$StartDate = funRqScpVar('StartDate','');
			$EndDate = funRqScpVar('EndDate','');
		}
		
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateString($TeamName,1,150),'Team Name');
			Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
			Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
			
					
			//if all values are good
			if($blnIsGood)
			{
				//create safe dates
				$SafeSD = funSfDateStr($StartDate);
				$SafeED = funSfDateStr($EndDate);
				
				if($TeamID != '')
				{
					//update the table
					$Team->UpdateTeam($TeamName,$SafeSD,$SafeED);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Team->GetGroupID(),CHANGE_UPDATE,'tbl_groups');
					
					
				} else {
					$Team = Business\Team::CreateTeam($TeamName,$SafeSD,$SafeED);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Team->GetGroupID(),CHANGE_INSERT,'tbl_groups');
				}
				
				$go_to_url = $lnk_view_teams_secure.'#group_'.$TeamID;
				
				//redirect to view page
				header( "Location: $go_to_url" );
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this team record!';
			}
		}
	}// end if not a bad region id
	
?>