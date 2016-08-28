<?php
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$TypeSelected = funRqScpVar('attendees_report_select','');
	
	$Group = funRqScpVar('Group','');
	$GroupID = funRqScpVar('GroupID','');
	
	$Regions = Business\getRegionsObjectToArray($_SESSION['User']->UniversalLoadRegions())->fetchAll();
	
	$RegionID = funRqScpVar('RegionID','');
	
	$Branch = funRqScpVar('Branch','');
	
	$HasGroup = false;
	$HasRegion = false;
	$HasBranch = false;
		
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_branches = get_branches_state_user($_SESSION['User']->GetUserID())->fetchAll();
	} elseif($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == NATIONAL_USER) {
		
		$arr_branches = getBranches()->fetchAll();
	} else {
		$arr_branches = array();
	}
	
	$StartDate = funRqScpVar('StartDate','');
	$EndDate = funRqScpVar('EndDate','');
	
	$path = '';
	
	$ReportRanges = array();
	
	if( $_SESSION['User']->GetUserTypeName() == GROUP_USER )
	{
		$ReportRanges[] = array('key' => GROUP, 'name' => 'Group');
		$HasGroup = true;
	}elseif( $_SESSION['User']->GetUserTypeName() == FIELD_WORKER )
	{
		$ReportRanges[] = array('key' => GROUP, 'name' => 'Group');
		$ReportRanges[] = array('key' => REGION, 'name' => 'Region');
		$HasGroup = true;
		$HasRegion = true;
	}elseif($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == NATIONAL_USER or $_SESSION['User']->GetUserTypeName() == STATE_USER)
	{
		$ReportRanges[] = array('key' => GROUP, 'name' => 'Group');
		$ReportRanges[] = array('key' => REGION, 'name' => 'Region');
		$ReportRanges[] = array('key' => BRANCH, 'name' => 'Branch');
		$HasGroup = true;
		$HasRegion = true;
		$HasBranch = true;
	} 
	
	if($intSubmitted == 1)
	{
		//perform validation
		$blnIsGood = true;
		
		Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
		Validation\CreateErrorMessage(Validation\ValidateDate($EndDate,true),'End Date');
		
		if( $blnIsGood )
		{
			$str_safe_s_date = funSfDateStr($StartDate);
			$str_safe_e_date = funSfDateStr($EndDate);
			
			Validation\CreateErrorMessage(Validation\ValidateDates($str_safe_s_date,$str_safe_e_date),'End Date');
		}
		
		if($TypeSelected == GROUP and $HasGroup)
		{
			if( !$_SESSION['User']->IsMyGroup($GroupID) )
			{
				//Validate Group
				Validation\CreateErrorMessage(' ID Bad!','Group');
			} 
		}elseif($TypeSelected == REGION and $HasRegion)
		{
			if( !$_SESSION['User']->IsMyRegion($RegionID) )
			{
				//Validate Group
				Validation\CreateErrorMessage(' ID Bad!','Region');
			} 
			
		}elseif($TypeSelected == BRANCH and $HasBranch)
		{
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				
				$bad_branch = true;
				
				foreach( $arr_branches as $thisBranch )
				{
					if( $thisBranch['id_branch'] == $Branch )
					{
						$bad_branch = false;
					}
				}
				
				if( $bad_branch )
				{
					//Validate Branch
					Validation\CreateErrorMessage(' ID Bad!','Branch');
				}
			
			} else {
				Validation\CreateErrorMessage(Validation\ValidateBranch($Branch),'Branch');
		
			}
		}
		
		if( $blnIsGood )
		{
			
			if($TypeSelected == GROUP and $HasGroup)
			{
				$thisGroup = Business\Group::LoadGroup($GroupID);
				
				$Report = $_SESSION['User']->AddReport(ATTENDANCES_BY_GROUP,$thisGroup->GetGroupName(),funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date),$_SESSION['User']->GetUserID());
				
				$ReportID = $Report->GetIDUserReport();
				
				//include '../php/async/async_attendances_by_group.php';
				
				$cmd = "php ".$BaseIncludeURL."/async/async_attendances_by_group.php ".$GroupID." ".$StartDate." ".$EndDate." ".$ReportID." ".$_SESSION['User']->GetUserID();
				
				funExecPlatformIndependant($cmd);
				header( "Location: $lnk_view_my_reports" );
				//ensure no further processing is performed
				exit;
				
			} 
			elseif($TypeSelected == REGION and $HasRegion)
			{
				$RegionObject = \Business\Region::LoadRegion($RegionID);
				$ReportName = $RegionObject->GetRegionName().' Region Report.';
				
				$Report = $_SESSION['User']->AddReport(ATTENDANCES_BY_REGION,$ReportName,funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date),$_SESSION['User']->GetUserID());
			
				$ReportID = $Report->GetIDUserReport();
				
				//include '../php/async/async_attendances_by_region.php';
				
				$cmd = "php ".$BaseIncludeURL."/async/async_attendances_by_region.php ".$RegionID." ".$StartDate." ".$EndDate." ".$ReportID." ".$_SESSION['User']->GetUserID();
				
				funExecPlatformIndependant($cmd);
				header( "Location: $lnk_view_my_reports" );
				//ensure no further processing is performed
				exit;
				
			}
			elseif($TypeSelected == BRANCH and $HasBranch)
			{
				$BranchObject = \Business\Branch::LoadBranch($Branch);
				$ReportName = $BranchObject->GetBranchName().' Branch Report.';
				
				$Report = $_SESSION['User']->AddReport(ATTENDANCES_BY_BRANCH,$ReportName,funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date),$_SESSION['User']->GetUserID());
				
				$ReportID = $Report->GetIDUserReport();
				
				//include '../php/async/async_attendances_by_branch.php';
				
				$cmd = "php ".$BaseIncludeURL."/async/async_attendances_by_branch.php ".$Branch." ".$StartDate." ".$EndDate." ".$ReportID." ".$_SESSION['User']->GetUserID();
				
				funExecPlatformIndependant($cmd);
				header( "Location: $lnk_view_my_reports" );
				//ensure no further processing is performed
				exit;
				
			}
			
			//redirect
			
			//$thisGroup = Business\Group::LoadGroup($GroupID);
			
			//$Report = $_SESSION['User']->AddReport($page_name,$thisGroup->GetGroupName(),funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date),$UserID);
			
			
			
			//$ReportID = $Report->GetIDUserReport();
			
			
			//use echo exec to test for bugs in asynchronous scripts (remember it shouldn't be async for testing
			//echo exec("php ../php/async/test.php argument");
			
			
			//can use echo to grab results, will stop async
			/* echo */ 
			//$cmd = "php ../php/async/async_group_by_group.php ".$GroupID." ".$StartDate." ".$EndDate." ".$ReportID." ".$_SESSION['User']->GetUserID();
			
			//funExecPlatformIndependant($cmd);
			//header( "Location: $lnk_view_my_reports" );
			//ensure no further processing is performed
			//exit;
			
		}
		
	}
	
	
?>
