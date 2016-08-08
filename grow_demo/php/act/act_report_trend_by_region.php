<?php
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$StartDate = funRqScpVar('StartDate','');
	$EndDate = funRqScpVar('EndDate','');
	
	$Regions = Business\getRegionsObjectToArray($_SESSION['User']->UniversalLoadRegions())->fetchAll();
	
	$RegionID = funRqScpVar('RegionID','');
	
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
		
		if( !$_SESSION['User']->IsMyRegion($RegionID) )
		{
			//Validate Group
			Validation\CreateErrorMessage(' ID Bad!','Region');
		} 
		
		if( $blnIsGood )
		{
			//redirect
			
			$go_to_report = $lnk_pdf_report_trend_by_region.'&RegionID='.$RegionID.'&StartDate='.$StartDate.'&EndDate='.$EndDate.'&form_submitted=1';;
			
			header( "Location: $go_to_report" );
			//ensure no further processing is performed
			exit;
			
		}
		
	}
	
?>