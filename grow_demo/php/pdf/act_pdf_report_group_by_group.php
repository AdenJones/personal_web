<?php
	$arrErrors = array();
	
	require($BaseIncludeURL.'/FPDF17/fpdf.php');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$GroupID = funRqScpVar('GroupID','');
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
		
		if( !$_SESSION['User']->IsMyGroup($GroupID) )
		{
			//Validate Group
			Validation\CreateErrorMessage(' ID Bad!','Group');
		} 
		
		if( $blnIsGood )
		{
			
			$GroupObject = \Business\Group::LoadGroup($GroupID);
			$ReportName = $GroupObject->GetGroupName().' Group Report.';
			$DatesString = 'Between '.funAusDateFormat($str_safe_s_date).' and '.funAusDateFormat($str_safe_e_date);
			
			//Meeting Details
			$MeetingsAttended = $GroupObject->CountMeetingsAttendedInPeriod($str_safe_s_date,$str_safe_e_date);
			$MeetingsScheduled = $GroupObject->CountMeetingsScheduledInPeriod($str_safe_s_date,$str_safe_e_date);
			
			//Attendees Details
			$FirstTimers = count($GroupObject->LoadFirstTimersBetweenDates($str_safe_s_date,$str_safe_e_date));
			$TotalCommunityObservers = $GroupObject->CountCommunityObserversInPeriod($str_safe_s_date,$str_safe_e_date);
			$TotalCommittedGrowers = $GroupObject->CountCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date);
			$TotalNewCommittedGrowers = $GroupObject->CountNewCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date);
			
			//Attendees Attendances
			$TotalCommittedGrowersWhoHaventAttendedForLastEightMeetings = $GroupObject->CountNSinceLastAttended(8,$str_safe_s_date,$str_safe_e_date);
			$TotalNOOfCommittedGrowerAttendances = $GroupObject->CommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
			//$TotalNOOfNonCommittedGrowerAttendances = $GroupObject->NonCommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
			$TotalOrganiserAttendances = $GroupObject->OrganiserAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
			$TotalRecorderAttendances = $GroupObject->RecorderAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
			$TotalFieldWorkerAttendances = $GroupObject->FieldWorkerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
			
			//Totals
			$TotalAttendees = $GroupObject->TotalAttendeesInPeriod($str_safe_s_date,$str_safe_e_date);
			$TotalAttendances = $GroupObject->TotalAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
			
			
		}
		
	}
	
?>