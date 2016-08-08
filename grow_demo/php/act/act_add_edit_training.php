<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_team','');
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_training = false;
	
	if($GroupID != '')
	{
		$Group = Business\Training::Load($GroupID);
				
		//jump out if bad user id is entered
		if($Group == NULL)
		{
			$bad_training = true;
						
		} else {
			
			//State User security
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
				
				if(!$Staff->IsMyGroup($GroupID))
				{
					$bad_training = true;
				}
			}
			
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
		}
	}
	
	if( !$bad_training )
	{
		
		//If we are dealing with an edit and we have a good person id
		if($GroupID != '' )
		{
					
			$TeamName = funRqScpVar('TeamName',$Group->GetGroupName());
			$StartDate = funRqScpVar('StartDate',funDateFormat($Group->GetStartDate(),'d/m/Y'));
			$EndDate = funRqScpVar('EndDate',funDateFormat($Group->GetEndDate(),'d/m/Y'));
			
		
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
				
				if($GroupID != '')
				{
					//update the table
					$Group->Update($TeamName,$SafeSD,$SafeED);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Group->GetGroupID(),CHANGE_UPDATE,'tbl_groups');
					
					
				} else {
					$NewGroup = Business\Training::Create($TeamName,$SafeSD,$SafeED);
					
					$GroupID = $NewGroup->GetGroupID();
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupID,CHANGE_INSERT,'tbl_groups');
				}
				
				$go_to_url = $lnk_view_training_secure.'#group_'.$GroupID;
				
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