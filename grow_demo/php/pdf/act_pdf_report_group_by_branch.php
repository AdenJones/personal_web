<?php
	$arrErrors = array();
	
	require($BaseIncludeURL.'/FPDF181/fpdf.php');
	require($BaseIncludeURL.'/FPDF181/fpdf_mk_table.php');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$StartDate = funRqScpVar('StartDate','');
	$EndDate = funRqScpVar('EndDate','');
	
	$Branch = funRqScpVar('Branch','');
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_branches = getBranch($UserStaff->GetBranch()->GetBranchAbbreviation())->fetchAll();
	} else {
		
		$arr_branches = getBranches()->fetchAll();
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
		
		Validation\CreateErrorMessage(Validation\ValidateBranch($Branch),'Branch');
		
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
		
		}
		
		if( $blnIsGood )
		{
			
			$BranchObject = \Business\Branch::LoadBranch($Branch);
			$ReportName = $BranchObject->GetBranchName().' Branch Report.';
			$DatesString = 'Between '.funAusDateFormat($str_safe_s_date).' and '.funAusDateFormat($str_safe_e_date);
			
			$Groups = $BranchObject->LoadGroupsByPeriod($str_safe_s_date,$str_safe_e_date);
			//collect statistics 
			
		}
		
	}
	
?>
