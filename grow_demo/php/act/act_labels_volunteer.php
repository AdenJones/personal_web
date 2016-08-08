<?php
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$TypeSelected = funRqScpVar('volunteer_labels_select','');
	
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
	
	
		$ReportRanges[] = array('key' => REGION, 'name' => 'Region');
		$ReportRanges[] = array('key' => BRANCH, 'name' => 'Branch');
	
		$HasRegion = true;
		$HasBranch = true;
	 
	
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
			
			if($TypeSelected == REGION and $HasRegion)
			{
				$RegionObject = \Business\Region::LoadRegion($RegionID);
				$LabelsName = $RegionObject->GetRegionName().' Region Labels.';
				
				$Labels = $_SESSION['User']->AddLabels(REGION_LABELS,$LabelsName,funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date));
			
				$LabelsID = $Labels->GetIDUserLabels();
				
				//include '../php/async/async_volunteer_labels_by_region.php';
				
				
				$cmd = "php ../php/async/async_volunteer_labels_by_region.php ".$RegionID." ".$StartDate." ".$EndDate." ".$LabelsID." ".$_SESSION['User']->GetUserID();
				
				funExecPlatformIndependant($cmd);
				header( "Location: $lnk_view_my_labels" );
				//ensure no further processing is performed
				exit;
				
			}
			elseif($TypeSelected == BRANCH and $HasBranch)
			{
				$BranchObject = \Business\Branch::LoadBranch($Branch);
				$LabelsName = $BranchObject->GetBranchName().' Branch Labels.';
				
				$Labels = $_SESSION['User']->AddLabels(BRANCH_LABELS,$LabelsName,funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date));
				
				$LabelsID = $Labels->GetIDUserLabels();
				
				//include '../php/async/async_volunteer_labels_by_branch.php';
				
				
				$cmd = "php ../php/async/async_volunteer_labels_by_branch.php ".$Branch." ".$StartDate." ".$EndDate." ".$LabelsID." ".$_SESSION['User']->GetUserID();
				
				funExecPlatformIndependant($cmd);
				header( "Location: $lnk_view_my_labels" );
				//ensure no further processing is performed
				exit;
				
			}
			
			
		}
		
	}
	
	
?>