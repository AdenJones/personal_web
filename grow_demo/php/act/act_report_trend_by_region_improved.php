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
			
			$RegionObject = \Business\Region::LoadRegion($RegionID);
			$ReportName = $RegionObject->GetRegionName().' Trend Report.';
			
			$Report = $_SESSION['User']->AddReport($page_name,$ReportName,funAusDateFormat($str_safe_s_date).' - '.funAusDateFormat($str_safe_e_date),$_SESSION['User']->GetUserID());
			
			$ReportID = $Report->GetIDUserReport();
			
			//include $BaseIncludeURL.'/async/async_trend_by_region.php';;
			
			
			$cmd = "php ".$BaseIncludeURL."/async/async_trend_by_region.php ".$RegionID." ".$StartDate." ".$EndDate." ".$ReportID." ".$_SESSION['User']->GetUserID();
			
			funExecPlatformIndependant($cmd);
			
			header( "Location: $lnk_view_my_reports" );
			//ensure no further processing is performed
			exit;
			
		}
		
	}
	
?>
