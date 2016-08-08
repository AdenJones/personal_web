<?php 
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$GroupRecessID = funRqScpVar('id_group_recess','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-100).':'.($this_year+3);
	$bad_group = $GroupID == '';
	$bad_group_recess = false;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
	}
	
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
				
				$GroupsLastRegion = $Group->LoadGroupsCurrentRegion();
				
				if( $Group->LoadGroupsCurrentRegion() != NULL)
				{
					if( $Staff->GetBranch()->GetBranchAbbreviation() != $GroupsLastRegion->GetRegion()->GetBranch()->GetBranchAbbreviation() )
					{
						$bad_group = true;
					}
				}
			}
		}
			
		if( !$bad_group )
		{
			
			if($GroupRecessID != '')
			{
				$GroupRecess = Business\GroupRecess::LoadGroupRecess($GroupRecessID);
						
				//jump out if bad user id is entered
				if($GroupRecess == NULL)
				{
					$bad_group_recess = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $GroupID != $GroupRecess->GetGroupID() )
					{
						$bad_group_recess = true;
					}
				}
			}
			
			if(!$bad_group_recess)
			{
			
				//If we are dealing with an edit and we have a good person id
				if($GroupRecessID != '' )
				{
						
					$StartDate = funRqScpVar('StartDate',funDateFormat($GroupRecess->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($GroupRecess->GetEndDate(),'d/m/Y'));
					
				
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
						
						if($GroupRecessID != '')
						{
							//update the table
							$GroupRecess->UpdateGroupRecess($SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupRecess->GetGroupRecessID(),CHANGE_UPDATE,'tbl_group_recess');
							
							
						} else {
							$GroupRecess = Business\GroupRecess::CreateGroupRecess($GroupID,$SafeSD,$SafeED);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupRecess->GetGroupRecessID(),CHANGE_INSERT,'tbl_group_recess');
						}
						
						$return_url = $lnk_view_group_schedule_secure.'&id_group='.$GroupID;
						
						//redirect to view page
						header( "Location: $return_url" );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this group recess record!';
					}
				} // end if is good
			} // end if is bad group region
		}// end if not a bad group check 2
	} // bad group check 1
	
	
?>