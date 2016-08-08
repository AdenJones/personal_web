<?php
	
	
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	$BaseIncludeURL = '../php';
	
	include $BaseIncludeURL.'/spc/spc_db_con.php';
	include $BaseIncludeURL.'/spc/spc_classes_includer.php'; // class must be called before session start
	include $BaseIncludeURL.'/spc/spc_functions.php'; //this will probably be removed once object libraries have been created
	include $BaseIncludeURL.'/spc/spc_query_functions.php'; //this will probably be removed once object libraries have been created
	include $BaseIncludeURL.'/spc/spc_generic_controls.php';

	date_default_timezone_set('Australia/Brisbane');
	if (session_id() === "") { session_name('grow_db'); session_start(); } //start the session to use session variables
	
	
	
	$true = 1;
	$false = 0;
	
	// User Type Categories 
	$UserTypeCatStaff = 'staff';
	$UserTypeCatAdmin = 'admin';
	$UserTypeCatVol = 'volunteer';
	
	define('VOLUNTEER','volunteer');
	
	// User Types  
	$Admin = 'Administrator';
	$StaffAdmin = 'Staff Administrator';
	$NationalUser = 'National User';
	$StateUser = 'State User';
	$GroupUser = 'Group User';
	$FieldWorker = 'Field Worker';
	
	$CommunityObserver = 'Community_Observer';
	
	$Organiser = 'Organiser';
	$Recorder = 'Recorder';
	
	$Committed = 'Committed';
	
	//non group types
	//Groups group types should be null
	define('NON_GROUP_TEAM', 'team');
	define('NON_GROUP_HOS_OR', 'hos_or');
	define('NON_GROUP_SOC_EV', 'soc_ev');
	define('NON_GROUP_COM_OUT', 'com_out');
	define('NON_GROUP_TRAIN', 'train');
	
	//Change Log Change Types
	define('CHANGE_INSERT', 'insert');
	define('CHANGE_UPDATE', 'update');
	define('CHANGE_DELETE', 'delete');
	
	//Branches regions seperator : specifically for ensuring difference between Branches and Regions in tbl_groups_regions
	define('GROUP_REGION_BRANCH', 'branch');
	define('GROUP_REGION_REGION', 'region');
	define('GROUP_REGION_UNKNOWN', 'unknown');
	
	//Decision Types
	$Pending = 'Pending';
	$Approved = 'Approved';
	$Declined = 'Declined';
	
	//Statuses
	define('STATUS_IN_PROCESS', 'In Process');
	define('STATUS_COMPLETE', 'Complete');
	define('STATUS_ERROR', 'Error');
	
	$InProcess = 'In Process';
	$Complete = 'Complete';
	$Error = 'Error';
	
	define('ORGANISER','Organiser');
	define('RECORDER','Recorder');
	define('SPONSOR','Sponsor');
	
	$StaffVolunteer  = 'staff_volunteer';
	$MemberString = 'member';
	define('MEMBER_STRING', 'member');
	define('STAFF_VOL_STRING', 'staff_volunteer');
	
	
	ignore_user_abort(true);
	set_time_limit(0);
	
	$arrErrors = array();
	
	$intSubmitted = 1;
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	
	$RegionID = $argv[1];
	$StartDate = $argv[2];
	$EndDate = $argv[3];
	$LabelsID = $argv[4];
	
	$SesUser = $argv[5];
	
	$thisUser = Membership\User::LoadUser($SesUser,false,false);
	
	if($thisUser == NULL)
	{
		echo 'Bad User ID';
	} else {
		
		
		if($intSubmitted == 1)
		{
			//perform validation
			$blnIsGood = true;
			
			$LabelsID = intval($LabelsID);
			
			$Labels = Business\UserLabels::LoadUserLabels($LabelsID);
			
			if($Labels == NULL )
			{
				$blnIsGood = false;
				Validation\CreateErrorMessage('Bad Labels ID!','Labels Error');
				
			} else if($Labels->GetLabelsStatus() == STATUS_COMPLETE)
			{
				$blnIsGood = false;
				Validation\CreateErrorMessage('Labels Already Complete!','Labels Error');
			}
			
			Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
			Validation\CreateErrorMessage(Validation\ValidateDate($EndDate,true),'End Date');
			
			if( $blnIsGood )
			{
				$str_safe_s_date = funSfDateStr($StartDate);
				$str_safe_e_date = funSfDateStr($EndDate);
				
				Validation\CreateErrorMessage(Validation\ValidateDates($str_safe_s_date,$str_safe_e_date),'End Date');
			}
			
			if( $blnIsGood )
			{
					$IsGood = true;
						
					$RegionObject = \Business\Region::LoadRegion(intval($RegionID));
					
					$RegionLabels = $RegionObject->LoadRegionVolunteerLabels($str_safe_s_date,$str_safe_e_date);
					
					foreach( $RegionLabels as $Label )
					{
						
						$Name = $Label['fld_first_name'].' '.$Label['fld_last_name'];
						
						Business\UserLabelsField::Create($LabelsID,$Label['fld_user_id'],$Name,$Label['fld_address'],$Label['fld_suburb'],$Label['fld_postcode'],$Label['fld_state_abbreviation']);
						
					}
						
					
				if($IsGood)
				{
					$Labels->SetLabelsStatus(STATUS_COMPLETE);
					$Labels->SaveMe();
					
					echo 'Labels Query Good!';
				}
				
			} else {
				//display error message
				
				$Labels->SetLabelsStatus(STATUS_ERROR);
				$Labels->SaveMe();
				
				echo 'Labels Query Error!';
				
			}
		
		} // end submitted check
			
	} // end user check
?>