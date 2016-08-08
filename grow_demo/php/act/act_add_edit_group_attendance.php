<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group','');
	$Date = funRqScpVar('date','');
	$Add = funRqScpVar('add','');
	$AttendanceID = funRqScpVar('id_attendance','');
	$intSubmitted = funRqScpVar('form_submitted','');
	$WithUser = funRqScpVar('with_user','');
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$HasExternalAttendance = false;
	
	$bad_group = $GroupID == '';
	$bad_date = $Date == '';
		
	if( !$bad_group and !$bad_date )
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
			
			$bad_date = !$Group->IsGroupDate($Date);
		}
			
		if(!$bad_group and !$bad_date)
		{
			
			//logic for external attendance types
			$non_group_type = $Group->GetNonGroupType();
			
			if( $non_group_type == NON_GROUP_HOS_OR 
				or $non_group_type == NON_GROUP_SOC_EV 
				or $non_group_type == NON_GROUP_COM_OUT
				or $non_group_type == NON_GROUP_TRAIN
				or $non_group_type == '' )
			{
				$HasExternalAttendance = true;
			}
			
			$url_return_to_group = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$GroupID.'">'.$Group->GetGroupName().'</a>';
			
			if( $Group->HasAttendanceReasonRecordOnDate($Date) )
			{
				$style_class = 'reason';
			} else {
				
				if( $Group->HasAttendanceOnDate($Date) )
				{
					$style_class = 'add';
				} else {
					$style_class = 'edit';
				}
				
			}
			
			$url_no_meeting_reason = '<a class="'.$style_class.'" href="'.$lnk_add_edit_no_meeting_reason.'&id_group='.$GroupID.'&date='.$Date.'"><img style="vertical-align:bottom; outline:none; border: 0;" src="/images/no_meeting.png" width="90" height="27" alt="No Meeting Reason!" /></a>';
					
			$addUrl = $lnk_add_edit_group_attendance.'&id_group='.$GroupID.'&date='.$Date;
			$urlAddMember = $addUrl.'&add=member';
			$urlAddStaff = $addUrl.'&add=staff';
			
			$lnkAddMember = '<a href="'.$urlAddMember.'">Add Member</a>';
			
			$lnkAddStaff = '<a href="'.$urlAddStaff.'">Add Staff / Volunteer</a>';
			
			$urlCreateMember = $lnk_add_edit_member.'&id_group='.$GroupID.'&date='.$Date.'&add=member';
			$lnkCreateMember = '<a href="'.$urlCreateMember.'">Create Member</a>';
			
			if( $HasExternalAttendance )
			{
				$int_attendees = funRqScpVar('int_attendees',$Group->GetExternalAttendees($Date));
				
				if( $Add == 'externalAttendance' )
				{
					$Group->SetExternalAttendees($Date,$int_attendees);
				}
				
			} else {
				$int_attendees = 0;
			}
			
			
			
			if( $Add == 'member' )
			{
				$str_member = funRqScpVar('str_member','');
				
				if($WithUser != '')
				{
					
					$int_member_hidden_input = funRqScpVar('int_member_hidden_input'.$WithUser,'');
				} else {
					$int_member_hidden_input = funRqScpVar('int_member_hidden_input','');
				}
				
				$committed = funRqScpVar('committed',0);
			}
			
			if( $Add == 'staff' )
			{
				
				$str_staff = funRqScpVar('str_staff','');
				//deal with duplicate staff hidden inputs
				if($WithUser != '')
				{
					
					$int_staff_hidden_input = funRqScpVar('int_staff_hidden_input_'.$WithUser,'');
				} else {
					$int_staff_hidden_input = funRqScpVar('int_staff_hidden_input','');
				}
			}
			
			//if the form has been submitted
			if($intSubmitted == 1)
			{
				
				$blnIsGood = true;
				
				if( $Add == 'member' )
				{
					Validation\CreateErrorMessage(Validation\ValidateMemberUserForAttendance($int_member_hidden_input,$GroupID,$Date),'Member Selection');
					
				}
				
				if( $Add == 'staff' )
				{
					Validation\CreateErrorMessage(Validation\ValidateStaffUserForAttendance($int_staff_hidden_input,$GroupID,$Date),'Staff Selection');
				}
				
				//Detect delete
				if($AttendanceID != '')
				{
					$Attendance = \Business\Attendance::LoadAttendance($AttendanceID);
					
					if( $Attendance != NULL )
					{
						$Attendance->Delete();
						
						Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$AttendanceID,CHANGE_DELETE,'tbl_group_attendance');
					}
				}
						
				//if all values are good
				if($blnIsGood)
				{
					
					if( $Add == 'member' )
					{
						$this_attendance = Business\Attendance::CreateAttendance($GroupID,$int_member_hidden_input,$Date);
						
						if( $committed == $true )
						{
							$Member = \Membership\Member::LoadMember($int_member_hidden_input);
							$Member->MakeCommitted($Date);
						}
						
						Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$this_attendance->GetAttendanceID(),CHANGE_INSERT,'tbl_group_attendance');
					} 
					
					if( $Add == 'staff' )
					{
											
						$this_attendance = Business\Attendance::CreateAttendance($GroupID,$int_staff_hidden_input,$Date);
						
						Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$this_attendance->GetAttendanceID(),CHANGE_INSERT,'tbl_group_attendance');
					} 
					
				} else {
					$msg_general_notifier = 'There are errors in this attendance record!';
				}
			} // end if is good
		} // end if is bad group or date
		
		$GroupLeaders = $Group->LoadGroupsLeadersByDate($Date);
		$StaffRegions = $Group->LoadStaffRegionsByDate($Date);
		
		$LastGroupAttendances = $Group->LoadLastGroupAttendances($Date);
		
		$AutoInserters = array();
		
		$MemberAutoInserters = array();
		
		foreach( $GroupLeaders as $GroupLeader )
		{
			if(!$GroupLeader->HasAttendanceByDate($Date))
			{
				$AutoInserters[] = \Membership\Staff::LoadStaff($GroupLeader->GetUserID());
			}
		}
		
		foreach( $StaffRegions as $StaffRegion )
		{
			if(!$StaffRegion->HasAttendanceByDate($GroupID,$Date))
			{
				$AutoInserters[] = \Membership\Staff::LoadStaff($StaffRegion->GetUserID());
			}
			
		}
		
		$MembersList = array();
		
		if( $LastGroupAttendances != false )
		{
			foreach( $LastGroupAttendances as $Attendance )
			{ 
				$AlreadyAdded = false;
				foreach($MembersList as $Member)
				{
					if($Member == $Attendance->GetUserID())
					{
						$AlreadyAdded = true;
						break;
					}
				}
				
				$User = \Membership\User::UniversalMemberLoaderByDate($Attendance->GetUserID(),$Date); //checks if they are a member on the date of attendance being input
				
				if( !$AlreadyAdded and $User instanceof Membership\Member and !$User->HasAttendanceByGroupDate($GroupID,$Date) )
				{
					$MemberAutoInserters[] = clone $User;
					$MembersList[] = $Attendance->GetUserID();
					
				}
			}
		}
		
		$OtherAttendances = Business\Attendance::LoadOtherAttendancesByGroupAndDate($GroupID,$Date);
		$MemberAttendances = Business\Attendance::LoadMemberAttendancesByGroupAndDate($GroupID,$Date);
		
		$TotalAttendances = count($OtherAttendances) + count($MemberAttendances) + intval($int_attendees);
		
	} // bad group check 1
	
	
?>