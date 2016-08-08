<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$GroupLeaderID = funRqScpVar('id_group_leader','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_group = $GroupID == '';
	$bad_group_leader = false;
	
	//get lists of job classifications
	
	$arr_roles = Business\getAllRolesForDropDown()->fetchAll();
	
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
				
				$GroupsLastRegion = $Group->LoadGroupsCurrentRegion();
				
				if( $Group->LoadGroupsCurrentRegion() != NULL)
				{
					if( !$Staff->has_region($GroupsLastRegion->GetRegionID()) )
					{
						$bad_group = true;
					}
				}
			}
		}
			
		if( !$bad_group )
		{
			
			if($GroupLeaderID != '')
			{
				$GroupLeader = Business\GroupLeader::LoadGroupLeader($GroupLeaderID);
						
				//jump out if bad user id is entered
				if($GroupLeader == NULL)
				{
					$bad_group_leader = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $GroupID !=  $GroupLeader->GetGroupID()) //check to make sure URL values haven't been screwed with.
					{
						$bad_group_leader = true;
						
					} else {
						$UserLeader = $GroupLeader->GetLeader();
					}
				}
			}
			
			if(!$bad_group_leader)
			{
			
				//If we are dealing with an edit and we have a good person id
				if($GroupLeaderID != '' )
				{
					$str_volunteer = $UserLeader->GetFirstName().' '.$UserLeader->GetLastname();
					$int_leader_hidden_input = funRqScpVar('int_leader_hidden_input',$GroupLeader->GetUserID());
					
					$RoleID = funRqScpVar('RoleID',$GroupLeader->GetGroupRoleID());
					if($intSubmitted == 1)
					{
						$Acting = funRqScpVar('Acting',0);
					} else {
						$Acting = funRqScpVar('Acting',$GroupLeader->GetActing());
					}
					
					$StartDate = funRqScpVar('StartDate',funDateFormat($GroupLeader->GetStartDate(),'d/m/Y'));
					$EndDate = funRqScpVar('EndDate',funDateFormat($GroupLeader->GetEndDate(),'d/m/Y'));
					
				
				} else {
					
					$str_volunteer = '';
					$int_leader_hidden_input = funRqScpVar('int_leader_hidden_input','');
					
					$RoleID = funRqScpVar('RoleID','');
					$Acting = funRqScpVar('Acting',0);
					
					$StartDate = funRqScpVar('StartDate','');
					$EndDate = funRqScpVar('EndDate','');
					
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					$blnIsGood = true;
					
					
					Validation\CreateErrorMessage(Validation\ValidateVolunteerStaffUser($int_leader_hidden_input),'Volunteer Selection');
					
					Validation\CreateErrorMessage(Validation\ValidateGroupRole($RoleID),'Role');
					Validation\CreateErrorMessage(Validation\ValidateBoolean($Acting),'Acting');
					
					Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
					Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
					
							
					//if all values are good
					if($blnIsGood)
					{
						//create safe dates
						$SafeSD = funSfDateStr($StartDate);
						$SafeED = funSfDateStr($EndDate);
						
						if($GroupLeaderID != '')
						{
							//update the table
							$GroupLeader->UpdateGroupLeader($int_leader_hidden_input,$RoleID,$SafeSD,$SafeED,$Acting);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupLeader->GetGroupsRolesID(),CHANGE_UPDATE,'tbl_groups_roles');
							
						} else {
							$GroupLeader = Business\GroupLeader::CreateGroupLeader($GroupID,$int_leader_hidden_input,$RoleID,$SafeSD,$SafeED,$Acting);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupLeader->GetGroupsRolesID(),CHANGE_INSERT,'tbl_groups_roles');
						}
						
						$return_url = $lnk_view_groups_leaders_secure.'&id_group='.$GroupID;
						
						//redirect to view page
						header( "Location: $return_url" );
						//ensure no further processing is performed
						exit;
					} else {
						$msg_general_notifier = 'There are errors in this group leader record!';
					}
				} // end if is good
			} // end if is bad group region
		}// end if not a bad group check 2
	} // bad group check 1
	
	
?>