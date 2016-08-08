<?php
	
	$GroupID = funRqScpVar('id_group','');
	$Date = funRqScpVar('Date','');
	
	$bad_group = $GroupID == '';
	$bad_date = $Date == '';
	
	if(!$bad_group)
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
	}
	
	if(!$bad_date)
	{
		if(Validation\ValidateDate($Date,true) != 'good')
		{
			$bad_date == true;
		}
	}
	
	$returnAddress = urlencode($lnk_view_group_notes_by_date.'&Date='.$Date.'&id_group='.$GroupID);
	
	//all further processing restricted by bad group detection
	if(!$bad_group and !$bad_date)
	{
		$SafeDate = funSfDateStr($Date);
		
		$GroupNotes = $Group->LoadNotesByDateAndSecurityLevel($_SESSION['User']->GetUserID(),$_SESSION['User']->GetSecurityLevel(),$SafeDate);
		
		//Mark notes as read by user
		foreach($GroupNotes as $GroupNote)
		{
			$GroupNote->MarkViewedByUser($_SESSION['User']->GetUserID());
		}
		
		$url_return_to_group_notes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$GroupID.'">'.$Group->GetGroupName().'</a>';
		
		$url_add_group_note = '<a href="'.$lnk_add_edit_group_note.'&Date='.$Date.'&id_group='.$GroupID.'&return_address='.$returnAddress.'">Add Group Note</a>';
		
	}
	
?>