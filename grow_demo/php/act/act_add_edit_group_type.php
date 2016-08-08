<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupTypeID = funRqScpVar('id_group_type','');
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_group_type = false;
	
	if($GroupTypeID != '')
	{
		$GroupType = Business\GroupType::LoadGroupType($GroupTypeID);
				
		//jump out if bad user id is entered
		if($GroupType == NULL)
		{
			$bad_group_type = true;
		} else {
			//change the page name if we are dealing with an edit
			$page_name = $page_edit_name;
		}
	}
	
	if( !$bad_group_type )
	{
		
		//If we are dealing with an edit and we have a good person id
		if($GroupTypeID != '' )
		{
			$GroupTypeName = funRqScpVar('GroupTypeName',$GroupType->GetGroupTypeName());
		
		} else {
			
			$GroupTypeName = funRqScpVar('GroupTypeName','');
		}
		
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateString($GroupTypeName,1,150),'Group Type Name');
				
			//if all values are good
			if($blnIsGood)
			{
				
				if($GroupTypeID != '')
				{
					//update the table
					$GroupType->UpdateGroupType($GroupTypeName);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupType->GetGroupTypeID(),CHANGE_UPDATE,'tbl_group_types');
					
				} else {
					$GroupType = Business\GroupType::CreateGroupType($GroupTypeName);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupType->GetGroupTypeID(),CHANGE_INSERT,'tbl_group_types');
				}
				
				//redirect to view page
				header( "Location: $lnk_view_group_types_secure" );
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this group type record!';
			}
		}
	}// end if not a bad region id
	
?>