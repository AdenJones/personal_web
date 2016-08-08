<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$UserID = funRqScpVar('id_user','');
	$intSubmitted = funRqScpVar('form_submitted','');
	$CreateNew = funRqScpVar('create_new','');
	$ReturnTo =  funRqScpVar('return_to','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_person = false;
	
	//get lists
	$arr_states = getStates()->fetchAll();
	$arr_genders = getGenders()->fetchAll();
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_roles = getVolunteerRoles()->fetchAll();
		$arr_branches = get_branches_state_user($_SESSION['User']->GetUserID())->fetchAll();
		
	} elseif($CreateNew == 1)
	{
		$arr_roles = getStaffRoles()->fetchAll();
		$arr_branches = getBranches()->fetchAll();
		
	} else {
		$arr_roles = getStaffRoles()->fetchAll();
		$arr_branches = getBranches()->fetchAll();
	}
	
	if($UserID != '')
	{
		$user = Membership\User::LoadUserUnSafe($UserID);
		
		if( $user == NULL )
		{
			$bad_person = true;
		} else {
			if( $CreateNew == 1) //if we are dealing with a create from member scenario
			{
				if( $user->HasStaffInterface() )
				{
					$bad_person = true; //give bad user error message if staff interface already exists
				} else {
					$member = Membership\Member::LoadMember($UserID);
				
					if(!$member)
					{
						$bad_person = true;
					} 
				}
				
				
			} else {
				$staff = Membership\Staff::LoadStaff($UserID);
				
				//jump out if bad user id is entered
				if(!$staff)
				{
					$bad_person = true;
					
				} else {
					//change the page name if we are dealing with an edit
					$page_name = $page_edit_name;
					
					if( $_SESSION['User']->GetUserTypeName() == $StateUser )
					{
						
						if( !$UserStaff->has_branch_by_branch_id($staff->GetBranchID()) )
						{
							$bad_person = true;
						}
						
						if( !$staff->IsVolunteer() )
						{
							$bad_person = true;
						}
					}
				}
			}//end create new check
		}//end base user check
	}//is edit check
	
	if( !$bad_person )
	{
		
		if($UserID != '')
		{
			if( $CreateNew == 1 )
			{
				if( $_SESSION['User']->CheckUserPage('add_edit_member') )
				{
					$return_to = '&return_to='.urlencode($lnk_add_edit_staff.'&id_user='.$UserID);
					
					if( $member->HasMemberInterface() )
					{
						$url_jump_to_member = ' - <a href="'.$lnk_add_edit_member.'&id_user='.$member->GetUserID().$return_to.'">Go To Member Record</a>';
						
					} else {
						$url_jump_to_member = ' - <a href="'.$lnk_add_edit_member.'&id_user='.$member->GetUserID().'&create_new=1'.$return_to.'">Create Member Record</a>';
					}
					
				} else {
					$url_jump_to_member = '';
				}
			} else {
				if( $_SESSION['User']->CheckUserPage('add_edit_member') )
				{
					$return_to = '&return_to='.urlencode($lnk_add_edit_staff.'&id_user='.$UserID);
					
					if( $staff->HasMemberInterface() )
					{
						$url_jump_to_member = ' - <a href="'.$lnk_add_edit_member.'&id_user='.$staff->GetUserID().$return_to.'">Go To Member Record</a>';
						
					} else {
						$url_jump_to_member = ' - <a href="'.$lnk_add_edit_member.'&id_user='.$staff->GetUserID().'&create_new=1'.$return_to.'">Create Member Record</a>';
					}
					
				} else {
					$url_jump_to_member = '';
				}
			}
		} else {
			$url_jump_to_member = '';
		}
		
		//If we are dealing with an edit and we have a good person id
		if($UserID != '' and $CreateNew != 1)
		{
					
			$FirstName = funRqScpVar('FirstName',$staff->GetFirstName());
			$LastName = funRqScpVar('LastName',$staff->GetLastname());
			
			$BirthDate = funRqScpVar('BirthDate',funDateFormat($staff->GetBirthDate(),'d/m/Y'));
			$Gender = funRqScpVar('Gender',$staff->GetGenderID());
			
			$Branch = funRqScpVar('Branch',$staff->GetBranchID());
			$WorkPhone = funRqScpVar('WorkPhone',$staff->GetWorkMobile());
			$WorkEmail = funRqScpVar('WorkEmail',$staff->GetWorkEmail());
						
			$Email = funRqScpVar('Email',$staff->GetPersonalEmail());
			$Mobile = funRqScpVar('Mobile',$staff->GetPersonalMobile());
			$HomePhone = funRqScpVar('HomePhone',$staff->GetHomePhone());
			$Address = funRqScpVar('Address',$staff->GetAddress());
			$Suburb = funRqScpVar('Suburb',$staff->GetSuburb());
			$State = funRqScpVar('State',$staff->GetStateID());
			$PostCode = funRqScpVar('PostCode',$staff->GetPostCode());
			
			$OtherEmploymentDetails = funRqScpVar('OtherEmploymentDetails',$staff->GetNotes());
			
			$EmConFName = funRqScpVar('EmConFName',$staff->GetEmConFName());
			$EmConLName = funRqScpVar('EmConLName',$staff->GetEmConLName());
			$EmConAddress = funRqScpVar('EmConAddress',$staff->GetEmConAddress());
			$EmConSuburb = funRqScpVar('EmConSuburb',$staff->GetEmConSuburb());
			$EmConState = funRqScpVar('EmConState',$staff->GetEmConStateID());
			$EmConPostCode = funRqScpVar('EmConPostCode',$staff->GetEmConPostCode());
			$EmConMobile = funRqScpVar('EmConMobile',$staff->GetEmConMobile());
			$EmConHomePhone = funRqScpVar('EmConHomePhone',$staff->GetEmConHomePhone());
			
		
		} else {
			
			$FirstName = funRqScpVar('FirstName','');
			$LastName = funRqScpVar('LastName','');
			
			$BirthDate = funRqScpVar('BirthDate','');
			$Gender = funRqScpVar('Gender','');
				
			$StartDate = funRqScpVar('StartDate','');
			$EndDate = funRqScpVar('EndDate','');
			
			$JobClassification = funRqScpVar('JobClassification','');
			$Branch = funRqScpVar('Branch','');
			$WorkPhone = funRqScpVar('WorkPhone','');
			$WorkEmail = funRqScpVar('WorkEmail','');
			
			$PoliceCheckDate = funRqScpVar('PoliceCheckDate','');
			
			$Email = funRqScpVar('Email','');
			$Mobile = funRqScpVar('Mobile','');
			$HomePhone = funRqScpVar('HomePhone','');
			$Address = funRqScpVar('Address','');
			$Suburb = funRqScpVar('Suburb','');
			$State = funRqScpVar('State','');
			$PostCode = funRqScpVar('PostCode','');
			
			$OtherEmploymentDetails = funRqScpVar('OtherEmploymentDetails','');
			
			$EmConFName = funRqScpVar('EmConFName','');
			$EmConLName = funRqScpVar('EmConLName','');
			$EmConAddress = funRqScpVar('EmConAddress','');
			$EmConSuburb = funRqScpVar('EmConSuburb','');
			$EmConState = funRqScpVar('EmConState','');
			$EmConPostCode = funRqScpVar('EmConPostCode','');
			$EmConMobile = funRqScpVar('EmConMobile','');
			$EmConHomePhone = funRqScpVar('EmConHomePhone','');
			
		}
		
		//if the form has been submitted
		if($intSubmitted == 1)
		{
			$blnIsGood = true;
			
			Validation\CreateErrorMessage(Validation\ValidateString($FirstName,1,45),'First Name');
			Validation\CreateErrorMessage(Validation\ValidateString($LastName,1,45),'Last Name');
			
			Validation\CreateErrorMessage(Validation\ValidateDate($BirthDate,false),'Date of Birth');
			Validation\CreateErrorMessage(Validation\ValidateGender($Gender),'Gender');
			
			if($UserID == '' or $CreateNew == 1 )
			{
				Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
				Validation\CreateErrorMessage(Validation\ValidateDate($EndDate),'End Date');
			}
			
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				
				if($UserID == '')
				{
					Validation\CreateErrorMessage(Validation\ValidateStaffJobClassVolunteer($JobClassification),'Job Classification');
				}
				
				if( !$UserStaff->has_branch_by_branch_id($Branch) )
				{
					//Validate Branch
					Validation\CreateErrorMessage(' ID Bad!','Branch');
				}
			} elseif( $CreateNew == 1 )
			{
				Validation\CreateErrorMessage(Validation\ValidateStaffJobClass($JobClassification),'Job Classification');
				Validation\CreateErrorMessage(Validation\ValidateBranch($Branch),'Branch');
			} else {
				if($UserID == '')
				{
					Validation\CreateErrorMessage(Validation\ValidateStaffJobClass($JobClassification),'Job Classification');
				}
				Validation\CreateErrorMessage(Validation\ValidateBranch($Branch),'Branch');
			}
			
			Validation\CreateErrorMessage(Validation\ValidateString($WorkPhone,0,45),'Work Phone');
			Validation\CreateErrorMessageEmail(Validation\ValidateString($WorkEmail,0,60),Validation\ValidateEmail($WorkEmail),'Work Email');
			
			if($UserID == '' or $CreateNew == 1)
			{
				Validation\CreateErrorMessage(Validation\ValidateDate($PoliceCheckDate),'Police Check');
			};
			
			//String match takes care of valid email being required, just set a minimum string length for this
			Validation\CreateErrorMessageEmail(Validation\ValidateString($Email,0,60),Validation\ValidateEmail($Email),'Email');
			Validation\CreateErrorMessage(Validation\ValidateString($Mobile,0,45),'Mobile');
			Validation\CreateErrorMessage(Validation\ValidateString($HomePhone,0,45),'Home Phone');
			Validation\CreateErrorMessage(Validation\ValidateString($Address,0,200),'Address');
			Validation\CreateErrorMessage(Validation\ValidateString($Suburb,0,45),'Suburb');
			Validation\CreateErrorMessage(Validation\ValidateState($State),'State');
			Validation\CreateErrorMessage(Validation\ValidateString($PostCode,0,8),'Postcode');
			
			Validation\CreateErrorMessage(Validation\ValidateString($OtherEmploymentDetails,0,3000),'Other Employment Details');
			
			Validation\CreateErrorMessage(Validation\ValidateString($EmConFName,0,100),'Emergency Contact First Name');
			Validation\CreateErrorMessage(Validation\ValidateString($EmConLName,0,100),'Emergency Contact Last Name');
			
			Validation\CreateErrorMessage(Validation\ValidateString($EmConAddress,0,200),'Emergency Contact Address');
			Validation\CreateErrorMessage(Validation\ValidateString($EmConSuburb,0,45),'Emergency Contact Suburb');
			Validation\CreateErrorMessage(Validation\ValidateState($EmConState),'Emergency Contact State');
			Validation\CreateErrorMessage(Validation\ValidateString($EmConPostCode,0,8),'Emergency Contact Postcode');
			
			Validation\CreateErrorMessage(Validation\ValidateString($EmConMobile,0,45),'Emergency Contact Mobile');
			Validation\CreateErrorMessage(Validation\ValidateString($EmConHomePhone,0,45),'Emergency Contact Home Phone');
			
					
			//if all values are good
			if($blnIsGood)
			{
				//create safe dates
				$SafeDOB = funSfDateStr($BirthDate);
				if($UserID == '' or $CreateNew == 1)
				{
					$SafeSD = funSfDateStr($StartDate);
					$SafeED = funSfDateStr($EndDate);
					$SafePoliceCheck = funSfDateStr($PoliceCheckDate);
				}
				
				if($UserID != '' and $CreateNew != 1)
				{
					//update the table
					$staff->UpdateStaff($FirstName,$LastName,$Gender,$SafeDOB,$Address,$Suburb,$PostCode,
					$State,$Branch,$WorkEmail,$Email,$WorkPhone,$Mobile,
					$HomePhone,$OtherEmploymentDetails,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConState,$EmConPostCode,
					$EmConMobile,$EmConHomePhone);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$staff->GetUserID(),CHANGE_UPDATE,'tbl_staff');
					
					
				} elseif( $CreateNew == 1 )
				{
					$newStaff = Membership\Staff::CreateStaffFromUser($UserID,$FirstName,$LastName,$Gender,$SafeDOB,$Address,$Suburb,$PostCode,
					$State,$Branch,$WorkEmail,$Email,$WorkPhone,$Mobile,
					$HomePhone,$OtherEmploymentDetails,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConState,$EmConPostCode,
					$EmConMobile,$EmConHomePhone);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newStaff->GetUserID(),CHANGE_INSERT,'tbl_staff');
					
					$newStaff->CreatePoliceCheck($SafePoliceCheck);
					
					$newStaff->CreateUserActivity($SafeSD,$SafeED,$JobClassification);
					
				} else {
					$newStaff = Membership\Staff::CreateStaff($FirstName,$LastName,$Gender,$SafeDOB,$Address,$Suburb,$PostCode,
					$State,$Branch,$WorkEmail,$Email,$WorkPhone,$Mobile,
					$HomePhone,$OtherEmploymentDetails,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConState,$EmConPostCode,
					$EmConMobile,$EmConHomePhone);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$newStaff->GetUserID(),CHANGE_INSERT,'tbl_staff');
					
					$newStaff->CreatePoliceCheck($SafePoliceCheck);
					
					$newStaff->CreateUserActivity($SafeSD,$SafeED,$JobClassification);
				}
				
				if($_SESSION['return_to'] != 'not_set')
				{
					if($_SESSION['return_to'] == 'view_user')
					{
						$return_url = $lnk_view_user.'&id_user='.$UserID;
						header( "Location: $return_url" );
					}
					elseif($_SESSION['return_to'] == 'find_staff')
					{
						header( "Location: $lnk_find_staff" );
					}elseif($_SESSION['return_to'] == 'find_vol')
					{
						header( "Location: $lnk_find_vol" );
					}
					elseif($_SESSION['return_to'] == 'view_staff_new')
					{
						header( "Location: $lnk_view_staff_new".'&int_staff_hidden_input='.$UserID );
					}elseif($_SESSION['return_to'] == 'view_vol_new' )
					{
						
						header( "Location: $lnk_view_vol_new".'&int_vol_hidden_input='.$UserID );
					} else {
						header( "Location: $lnk_view_staff_secure" );
					}
				}
				
				exit;
			} else {
				$msg_general_notifier = 'There are errors in this staff record!';
			}
		}//end if is good
	}//end bad person check
	
?>