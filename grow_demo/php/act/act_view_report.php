<?php 

	$bad_report = false;
	$bad_user = false;
	
	$ReportID = funRqScpVar('ReportID','');
	
	$Report = Business\UserReport::LoadUserReport($ReportID);
	
	if( $Report == NULL )
	{
		$bad_report = true;
	} elseif( $_SESSION['User']->GetUserID() != $Report->GetIDUser()) {
		$bad_user = true;
	} else {
		//perform all other logic
		
		
		//headcing stripper is depeendant upon the correct format of heading
		
		$ReportType = $Report->GetReportType();
		$GroupName = $Report->GetReportName();
		$Dates = $Report->GetReportDates();
		
		$Heading = html_entity_decode($ReportType.' : '.$GroupName.' : '.$Dates);
		
		$HeadingStripped = preg_replace('/\s+/', '', $Heading);
		
		$ReportFields = $Report->LoadUserReportsFields();
		
		/*
		$ReportType = substr($Heading,0,stripos($Heading,'-')); //
		$GroupName = substr($Heading,stripos($Heading,'-')+2,strlen($Heading) - stripos($Heading,'-') - strripos($Heading,':'));
		$Dates = substr($Heading,strripos($Heading,':')+2,strlen($Heading) - strripos($Heading,':') );;
		*/
	}

?>