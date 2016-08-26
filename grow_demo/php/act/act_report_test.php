<?php
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	
	$Group = funRqScpVar('Group','');
	$GroupID = funRqScpVar('GroupID','');
	
	
	$StartDate = funRqScpVar('StartDate','');
	$EndDate = funRqScpVar('EndDate','');
	
	$path = '';
	
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
		
		if( !$_SESSION['User']->IsMyGroup($GroupID) )
		{
			//Validate Group
			Validation\CreateErrorMessage(' ID Bad!','Group');
		} 
		
		if( $blnIsGood )
		{
			//redirect
			
			$thisGroup = Business\Group::LoadGroup($GroupID);
			
			//$Report = $_SESSION['User']->AddReport($page_name.' - '.$thisGroup->GetGroupName().': '.funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date));
			
			//$ReportID = $Report->GetIDUserReport();
			
			
			//use echo exec to test for bugs in asynchronous scripts (remember it shouldn't be async for testing
			//echo exec("php ../php/async/test.php argument");
			
			//echo exec('cd ../php/async && ls -al');
			
			exec('php ../php/async/test.php 2>&1',$output,$return_var);
			
			echo $return_var;
			var_dump($output);
			
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
