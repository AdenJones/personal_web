<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$ReturnTo = funRqScpVarNonSafe('return_to','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_group = false;
	
	//get lists of job classifications
	
	$arr_group_types = Business\getAllGroupTypes()->fetchAll();
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}
	
	if($GroupID != '')
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
			
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
		}
	}
	
	if( !$bad_group )
	{
		
		//If we are dealing with an edit and we have a good person id
		if($GroupID != '' )
		{
					
			$GroupName = funRqScpVar('GroupName',$Group->GetGroupName());
			
			$GroupTypeID = funRqScpVar('GroupTypeID',$Group->GetGroupTypeID());
				
			$StartDate = funRqScpVar('StartDate',funDateFormat($Group->GetStartDate(),'d/m/Y'));
			$EndDate = funRqScpVar('EndDate',funDateFormat($Group->GetEndDate(),'d/m/Y'));
			
		
		} else {
			
			$GroupName = funRqScpVar('GroupName','');
			
			$GroupTypeID = funRqScpVar('GroupTypeID','');
				
			$StartDate = funRqScpVar('StartDate','');
			$EndDate = funRqScpVar('EndDate','');
			
		}
		
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateString($GroupName,1,150),'Group Name');
			
			Validation\CreateErrorMessage(Validation\ValidateGroupType($GroupTypeID),'Group Type');
				
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
					$Group->UpdateGroup($GroupName,$GroupTypeID,$SafeSD,$SafeED);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Group->GetGroupID(),CHANGE_UPDATE,'tbl_groups');
					
					
				} else {
					$Group = Business\Group::CreateGroup($GroupName,$GroupTypeID,$SafeSD,$SafeED);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Group->GetGroupID(),CHANGE_INSERT,'tbl_groups');
				}
				
				if($_SESSION['return_to'] == 'view_group' )
				{
					$go_to_url = $lnk_view_group.'&id_group='.$Group->GetGroupID();
				} else {
					$go_to_url = $lnk_view_groups_secure.'#group_'.$Group->GetGroupID();
				}
				
				//redirect to view page
				header( "Location: $go_to_url" );
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this group record!';
			}
		}
	}// end if not a bad region id
	
?>