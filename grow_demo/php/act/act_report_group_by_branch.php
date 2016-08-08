<?php
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$StartDate = funRqScpVar('StartDate','');
	$EndDate = funRqScpVar('EndDate','');
	
	$Branch = funRqScpVar('Branch','');
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$UserStaff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arr_branches = get_branches_state_user($_SESSION['User']->GetUserID())->fetchAll();
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
		
		Validation\CreateErrorMessage(Validation\ValidateBranch($Branch),'Branch'); //slight security flaw here
		
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
			//redirect
			
			$go_to_report = $lnk_pdf_report_group_by_branch_optimised.'&Branch='.$Branch.'&StartDate='.$StartDate.'&EndDate='.$EndDate.'&form_submitted=1';;
			
			header( "Location: $go_to_report" );
			//ensure no further processing is performed
			exit;
			
		}
		
	}
	
?>