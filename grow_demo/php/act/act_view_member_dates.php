<?php
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		
		$Heading = 'Secure';
		$secure = true;
	} else {
		
		$Heading = '';
		$secure = false;
	}
	
	$ReturnTo = funRqScpVarNonSafe('return_to','');
	$UserID = funRqScpVar('id_user','');
	
	
	//delete variables
	$intSubmitted = funRqScpVar('form_submitted','');
	$Delete = funRqScpVarNonSafe('delete','');
	$intCommitted = funRqScpVar('id_committed','');
	
	$bad_user = $UserID == '';
	
	if(!$bad_user)
	{
		$member = Membership\Member::LoadMember($UserID);
				
		//jump out if bad user id is entered
		if(!$member)
		{
			$bad_user = true;
		} else {
			
			//make sure community observers can't be edited by non admins
			if( $_SESSION['User']->GetUserTypeName() != $Admin and $member->GetFirstName() == $CommunityObserver )
			{
				$bad_user = true;
			}
		}
	}
	
	//all further processing restricted by bad user detection
	if(!$bad_user)
	{
		//if we are dealing with a delete scenario
		if($intSubmitted == 1)
		{
			if($Delete == 'committed' and $intCommitted != '')
			{
				if( $member->IsMyCommittedRecord($intCommitted) )
				{ 
					$thisCommittedDate = Membership\MemberCommittedDates::LoadCommittedDate($intCommitted,true); //allows for retrieval of already deleted to avoid forcing error screen
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$thisCommittedDate->GetCommittedID(),CHANGE_DELETE,'tbl_member_committed_dates');
					
					$thisCommittedDate->MarkAsDeleted();
					
				}
			}
		}
		
		$UserActivityDates = $member->GetActivityDates();
		$MemberCommittedDates = $member->GetCommittedDates();
		
		$MemberName = $member->GetFirstName().' '.$member->GetLastname();
		
		$return_url = '<a href="'.$lnk_add_edit_member.'&id_user='.$UserID.'&return_to='.urlencode($ReturnTo).'">'.$MemberName.'</a>';
		$url_add_member_activity_date = '<a href="'.$lnk_add_edit_member_dates.'&id_user='.$UserID.'&return_to='.urlencode($ReturnTo).'">+ Activity Date</a>';
		$url_add_member_committed_dates = '<a href="'.$lnk_add_edit_member_committed_dates.'&id_user='.$UserID.'&return_to='.urlencode($ReturnTo).'">+ Committed Date</a>';
	}
	
?>