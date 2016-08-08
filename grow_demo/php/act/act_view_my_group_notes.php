<?php
	
	
		$GroupNotes = $_SESSION['User']->LoadMyGroupsGroupNotes();
		
		//Mark notes as read by user
		foreach($GroupNotes as $GroupNote)
		{
			$GroupNote->MarkViewedByUser($_SESSION['User']->GetUserID());
		}
		
		$returnAddress = $lnk_view_my_group_notes;
	
?>