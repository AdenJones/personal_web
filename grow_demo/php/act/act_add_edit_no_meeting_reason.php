<?php 
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$Date = funRqScpVar('date','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-100).':'.($this_year+3);
	
	$bad_group = $GroupID == '';
	$bad_date = $Date == '';
	$bad_no_meeting_record = false;
	
	if( !$bad_group and !$bad_date )
	{
		
		//preliminary validation
		$Group = Business\Group::LoadGroup($GroupID);
				
		//jump out if bad user id is entered
		if($Group == NULL)
		{
			$bad_group = true;
		} else {
			$bad_date = !$Group->IsGroupDate($Date);
		}
	}
	
	if( !$bad_group and !$bad_date )
	{
		$url_return_to_group = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$GroupID.'">'.$Group->GetGroupName().'</a>';
		$lnk_back = $lnk_add_edit_group_attendance.'&date='.$Date.'&id_group='.$GroupID;
		$lnk_back_to_attendance = '<a href="'.$lnk_back.'">'.funAusDateFormat($Date).'</a>';
		
		
		$arr_reasons = getNoMeetingReasons()->fetchAll();
		
		if( $Group->HasAttendanceReasonRecordOnDate($Date) )
		{
			//edit
			//load record
			$NoMeetingReason =  $Group->LoadNoMeetingReasonRecordOnDate($Date);
			
			if( $NoMeetingReason == NULL )
			{
				$bad_no_meeting_record = true;
			} else {
				$Reason = funRqScpVar('Reason',$NoMeetingReason->GetNoMeetingReasonID());
				$Notes = funRqScpVar('Notes',$NoMeetingReason->GetNotes());
			}
			
		} else {
			//add
			$Reason = funRqScpVar('Reason','');
			$Notes = funRqScpVar('Notes','');
		}
		
		if( $intSubmitted == 1 and !$bad_no_meeting_record )
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateNoMeetingReason($Reason),'Reason');
			Validation\CreateErrorMessage(Validation\ValidateString($Notes,0,3000),'Notes');
			
			if( $blnIsGood )
			{
				if( $Group->HasAttendanceReasonRecordOnDate($Date) )
				{
					$NoMeetingReason->UpdateNoMeetingReason($Reason,$Notes);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$NoMeetingReason->GetGroupNoMeetingID(),CHANGE_UPDATE,'tbl_no_meetings_info');
				} else {
					
					$NoMeetingReason = Business\GroupNoMeeting::CreateGroupNoMeeting($GroupID,$Date,$Reason,$Notes);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$NoMeetingReason->GetGroupNoMeetingID(),CHANGE_INSERT,'tbl_no_meetings_info');
				}
				
				//redirection
				//redirect to view page
				header( "Location: $lnk_back" );
				//ensure no further processing is performed
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this record!';
			}
		}
		
		
	} // bad group check 1
	
	
?>