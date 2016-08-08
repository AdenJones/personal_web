<?php
	
	
	$GroupID = funRqScpVar('id_group','');
		
	$BadGroup = false;
	
	if( $_SESSION['User']->IsMyGroup(intval($GroupID)) )
	{
		
		$Group = \Business\Group::LoadGroup($GroupID);
		
		$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
	
		if( $allow_deletes )
		{
			$DelGroupID = funRqScpVar('del_group',0);
			
			if( $DelGroupID == 1 )
			{
				if( $Group != NULL )
				{
					$Notification = $Group->GetGroupName().' was successfully deleted!';
					
					$IsDeleted = $Group->Delete();
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupID,CHANGE_DELETE,'tbl_groups');
					
					if($IsDeleted)
					{
						$go_to_url = $lnk_default_page.'&msg_general_notifier='.urlencode($Notification);;
						
						echo $go_to_url;
						
						//redirect to view page
						header( "Location: $go_to_url" );
						//ensure no further processing is performed
						exit;
					}else{
						$msg_general_notifier = 'Delete User failed!';
					}
				}
			}
		}
		
		$secure = (  $_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STATE_USER );
		
		
		
	} else {
		$BadGroup = true;
	}
	
?>