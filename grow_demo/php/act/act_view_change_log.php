<?php
	$arrErrors = array();
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$date = new DateTime(date('Y-m-d'));
	$date->sub(new DateInterval('P1M'));
	
	$EndDate = new DateTime(date('Y-m-d')); 
	$EndDate->add(new DateInterval('P1D'));
	
	$StartDate = funRqScpVar('StartDate',$date->format('d/m/Y'));
	$EndDate = funRqScpVar('EndDate',$EndDate->format('d/m/Y')); //adding a day to compensate for todays records being greater than the base date.
	
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
	
	if( $blnIsGood )
	{
		$thisLog = Auditing\Changelog::LoadLogByDates($str_safe_s_date,$str_safe_e_date);
		
	}
	
	
?>