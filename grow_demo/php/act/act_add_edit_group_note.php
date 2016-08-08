<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$GroupNoteID = funRqScpVar('id_group_note','');
	$Date = funRqScpVar('Date','');
	$intSubmitted = funRqScpVar('form_submitted','');
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_group_note = false;
	$bad_group = false;
	//Validate Group Note if submitted
	$is_edit = false;
	
	if($GroupNoteID != '')
	{
		$thisGroupNote = Business\Note::LoadNote($GroupNoteID);
		
		if( $thisGroupNote == NULL )
		{
			$bad_group_note = true;
		} else {
			//check that current user is only editing their note
			if($thisGroupNote->GetCreator() != $_SESSION['User']->GetUserID())
			{
				$bad_group_note = true;
			} else {
				$is_edit = true;
				$page_name = $page_edit_name;
			}
		}
	}
	
	if(!$bad_group_note)
	{
		
		if(!$is_edit)
		{
			$bad_group = $GroupID == '';
		}
		
		$userSecurityLevel = $_SESSION['User']->GetSecurityLevel(); //will be used for edit verification functionality
		
		$arrImportances = getImportances()->fetchAll();
		
		$arrSecurityLevels = getSecurityLevels()->fetchAll();
		
		
		if( $is_edit or !$bad_group )
		{
			if(!$is_edit)
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
			} else {
				$Group = Business\Group::LoadGroup($thisGroupNote->GetGroupID());
			}
			
			if( $is_edit or !$bad_group)
			{
				//detect edit
				
				if($is_edit)
				{
					//valid access to group note
					//at present users should only have access to their own group notes
					//param variables 
					$Note = funRqScpVar('Note',$thisGroupNote->GetNote());
					
					if($intSubmitted == 1 )
					{
						$IncidentReport = funRqScpVar('IncidentReport',0);
					} else {
						$IncidentReport = $thisGroupNote->GetIncidentReport();
					}
					$Importance = funRqScpVar('Importance',$thisGroupNote->GetImportance());
					$SecurityLevel = funRqScpVar('SecurityLevel',$thisGroupNote->GetSecurityLevel());
					$Date = funRqScpVar('Date',funDateFormat($thisGroupNote->GetDated(),'d/m/Y'));
					
				} else {
					//Is Add
					
					//param variables 
					$Note = funRqScpVar('Note','');
					$IncidentReport = funRqScpVar('IncidentReport',0);
					$Importance = funRqScpVar('Importance','');
					$SecurityLevel = funRqScpVar('SecurityLevel',$userSecurityLevel);
					$Date = funRqScpVar('Date','');
				}
				
				//if the form has been submitted
				if($intSubmitted == 1)
				{
					
					$blnIsGood = true;
					
					//Validate
					Validation\CreateErrorMessage(Validation\ValidateString($Note,1,3000),'Note');
					Validation\CreateErrorMessage(Validation\ValidateDate($Date,true),'Note Date');
					Validation\CreateErrorMessage(Validation\ValidateBoolean($IncidentReport),'Incident Report');
					Validation\CreateErrorMessage(Validation\ValidateImportance($Importance),'Importance');
					Validation\CreateErrorMessage(Validation\ValidateSecurityLevel($SecurityLevel),'Security Level');
					
					if($blnIsGood)
					{
						//create safe dates
						$SafeDate = funSfDateStr($Date);
						
						//Create the note
						if($is_edit)
						{
							//valid access to group note
							//at present users should only have access to their own group notes
							$thisGroupNote->Update($SafeDate,$Note,$IncidentReport,$Importance,$SecurityLevel);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$thisGroupNote->GetNoteID(),CHANGE_UPDATE,'tbl_notes');
							
						} else {
							//Is Add
							$thisGroupNote = Business\Note::CreateGroupNote($GroupID,$SafeDate,$Note,$IncidentReport,$Importance,$_SESSION['User']->GetUserID(),$SecurityLevel);
							
							Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$thisGroupNote->GetNoteID(),CHANGE_INSERT,'tbl_notes');
							
						}
						
						$returnURL = $_SESSION['ReturnAddress'];
						
						//redirect to view page
						header( "Location: $returnURL" );
						//ensure no further processing is performed
						exit;
						
					} else {
					$msg_general_notifier = 'There are errors in this Group Note record!';
					}
					
					
				} //End if submitted
				
			} // end if is bad group
			
			
		} // bad group check 1
	}//end bad edit check
	
?>