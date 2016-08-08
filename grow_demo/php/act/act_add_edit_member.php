<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$GroupID = funRqScpVar('id_group',''); //capture group id for return to enter attendance
	$UserID = funRqScpVar('id_user','');
	$intSubmitted = funRqScpVar('form_submitted','');
	$ReturnTo = funRqScpVarNonSafe('return_to','');
	$CreateNew = funRqScpVar('create_new','');
	
	if( $GroupID != '' )
	{
		$Date = funRqScpVar('date','');
		$Add = funRqScpVar('add','member');
	}
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	} 
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_person = false;
	
	//get lists
	$arr_genders = getGenders()->fetchAll();
	
	if($UserID != '')
	{
		$user = Membership\User::LoadUserUnSafe($UserID);
		
		if( $user == NULL )
		{
			$bad_person = true;
		} else {
			
			if( $CreateNew == 1) //if we are dealing with a create from staff scenario
			{
				if( $user->HasMemberInterface() )
				{
					$bad_person = true; //give bad user error message if staff interface already exists
				} else {
					$staff = Membership\Staff::LoadStaff($UserID);
				
					if(!$staff)
					{
						$bad_person = true;
					} 
				}
				
				
			} else {
				
				$member = Membership\Member::LoadMember($UserID);
						
				//jump out if bad user id is entered
				if(!$member)
				{
					$bad_person = true;
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					//make sure community observers can't be edited by non admins
					if( $_SESSION['User']->GetUserTypeName() != $Admin and $member->GetFirstName() == $CommunityObserver )
					{
						$bad_person = true;
					}
				}
			}//end if create new
		}//end if unsafe check is null
	}//end if is edit or create from staff
	
	if( !$bad_person )
	{
		
		if( $GroupID != '' )
		{
			$url_view_activity_dates = '';
			
		} else {
			$url_view_activity_dates = '<a href="'.$lnk_view_member_dates.'&id_user='.$UserID.'&return_to='.urlencode($ReturnTo).'">View Member Dates</a>';
			
		}
		
		if($UserID != '')
		{
			
			$AccessAllowed =  $_SESSION['User']->CheckUserPage('add_edit_staff');
			
			if( $AccessAllowed )
			{
				if( $_SESSION['User']->GetUserTypeName() == $StateUser )
					{
						
						$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
						
						if( $CreateNew == 1 )
						{
							if( $staff->GetBranch()->GetBranchAbbreviation() != $UserStaff->GetBranch()->GetBranchAbbreviation() )
							{
								$AccessAllowed = false;
							}
							
							if( !$staff->IsVolunteer() )
							{
								$AccessAllowed = false;
							}
							
						} else {
							if( $member->HasStaffInterface() )
							{
								$staff = Membership\Staff::LoadStaff($UserID);
								
								if( $staff->GetBranch()->GetBranchAbbreviation() != $UserStaff->GetBranch()->GetBranchAbbreviation() )
								{
									$AccessAllowed = false;
								}
								
								if( !$staff->IsVolunteer() )
								{
									$AccessAllowed = false;
								}
							}
						}
						
						
					}
			}
			
			if( $CreateNew == 1 )
			{
				if( $_SESSION['User']->CheckUserPage('add_edit_staff') and $AccessAllowed)
				{
					if( $staff->HasStaffInterface() )
					{
						$url_jump_to_staff = ' - <a href="'.$lnk_add_edit_staff.'&id_user='.$staff->GetUserID().'">Go To Staff Record</a>';
						
					} else {
						$url_jump_to_staff = ' - <a href="'.$lnk_add_edit_staff.'&id_user='.$staff->GetUserID().'&create_new=1'.'">Create Staff Record</a>';
					}
					
				} else {
					$url_jump_to_staff = '';
				}
			} else {
				if( $_SESSION['User']->CheckUserPage('add_edit_staff') and $AccessAllowed)
				{
					if( $member->HasStaffInterface() )
					{
						$url_jump_to_staff = ' - <a href="'.$lnk_add_edit_staff.'&id_user='.$member->GetUserID().'">Go To Staff Record</a>';
						
					} else {
						$url_jump_to_staff = ' - <a href="'.$lnk_add_edit_staff.'&id_user='.$member->GetUserID().'&create_new=1'.'">Create Staff Record</a>';
					}
					
				} else {
					$url_jump_to_staff = '';
				}
			}//end if create new
		} else {
			$url_jump_to_staff = '';
		} //end if edit
		
		
		//If we are dealing with an edit and we have a good person id
		if($UserID != '' and $CreateNew != 1)
		{
					
			$FirstName = funRqScpVar('FirstName',$member->GetFirstName());
			$LastName = funRqScpVar('LastName',$member->GetLastname());
			
			$Gender = funRqScpVar('Gender',$member->GetGenderID());
			
		} else {
			
			$FirstName = funRqScpVar('FirstName','');
			$LastName = funRqScpVar('LastName','');
			
			$Gender = funRqScpVar('Gender','');
			
			$CommittedDate = funRqScpVar('CommittedDate','');
			$StartDate = funRqScpVar('StartDate','');
			$EndDate = funRqScpVar('EndDate','');
			
		}
		
		
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateString($FirstName,1,45),'First Name');
			Validation\CreateErrorMessage(Validation\ValidateString($LastName,1,45),'Last Name');
			
			Validation\CreateErrorMessage(Validation\ValidateGender($Gender),'Gender');
			
			if($UserID == '' or $CreateNew == 1)
			{
				Validation\CreateErrorMessage(Validation\ValidateDate($CommittedDate),'Committed Date');
				
				Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
				Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
			}
				
			//if all values are good
			if($blnIsGood)
			{
				
				if($UserID == ''  or $CreateNew == 1)
				{
					
					//create safe dates
					$SafeComD = funSfDateStr($CommittedDate);
					$SafeSD = funSfDateStr($StartDate);
					$SafeED = funSfDateStr($EndDate);
				}
				
				if($UserID != '' and $CreateNew != 1)
				{
					//update the table
					$member->UpdateMember($FirstName,$LastName,$Gender);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$member->GetUserID(),CHANGE_UPDATE,'tbl_members');
					
					
				} elseif( $CreateNew == 1 ) {
				
					$newMember = Membership\Member::CreateMemberFromUser($UserID,$FirstName,$LastName,$Gender);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newMember->GetUserID(),CHANGE_INSERT,'tbl_members');
					
					if( $CommittedDate != '' )
					{
						$newMember->CreateCommittedRecord($SafeComD);
					}
					
					$newMember->CreateUserActivity($SafeSD,$SafeED);
					
				} else {
					$newMember = Membership\Member::CreateMember($FirstName,$LastName,$Gender,$SafeComD);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newMember->GetUserID(),CHANGE_INSERT,'tbl_members');
					
					if( $CommittedDate != '' )
					{
						$newMember->CreateCommittedRecord($SafeComD);
					}
					
					$newMember->CreateUserActivity($SafeSD,$SafeED);
					
				}
				
				
				if( $_SESSION['return_to'] == 'view_user' )
				{
					$return_url = $lnk_view_user.'&id_user='.$UserID;
				} else {
					if( $GroupID != '' and $CreateNew != 1 )
					{
						$return_url = $lnk_add_edit_group_attendance.'&id_group='.$GroupID.'&date='.$Date.'&add='.$Add;
						
					} else {
						$return_url = urldecode($ReturnTo);
						
					}
				}
					//redirect to view page
					header( "Location: $return_url" );
					//ensure no further processing is performed
					exit;
				
			} else {
				$msg_general_notifier = 'There are errors in this member record!';
			}
		}//end submitted check
	}//end bad person check
	
?>