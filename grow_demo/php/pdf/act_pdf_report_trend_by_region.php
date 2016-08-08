<?php
	$arrErrors = array();
	
	require($BaseIncludeURL.'/FPDF17/fpdf.php');
	require($BaseIncludeURL.'/FPDF17/fpdf_mk_table.php');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$RegionID = funRqScpVar('RegionID','');
	
	$StartDate = funRqScpVar('StartDate','');
	$EndDate = funRqScpVar('EndDate','');
	
	
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
			
			$RegionObject = \Business\Region::LoadRegion($RegionID);
			$ReportName = $RegionObject->GetRegionName().' Trend Report.';
			$DatesString = 'Between '.funAusDateFormat($str_safe_s_date).' and '.funAusDateFormat($str_safe_e_date);
			
			$Dates = funGetMonthsBetweenDates($str_safe_s_date,$str_safe_e_date);
			
			
		}
		
	}
	
?>