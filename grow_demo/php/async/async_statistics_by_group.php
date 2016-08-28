<?php
	require_once('async_application.php');
	
	$arrErrors = array();
	
	$intSubmitted = 1;
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	$GroupID = $argv[1];
	$StartDate = $argv[2];
	$EndDate = $argv[3];
	$ReportID = $argv[4];
	
	$SesUser = $argv[5];
	
	$thisUser = Membership\User::LoadUser($SesUser,false,false);
	
	if($thisUser == NULL)
	{
		echo 'Bad User ID';
	} else {
		
		
		echo $thisUser->GetUserID();
		
		if($intSubmitted == 1)
		{
			//perform validation
			$blnIsGood = true;
			
			$ReportID = intval($ReportID);
			
			$Report = Business\UserReport::LoadUserReport($ReportID);
			
			if($Report == NULL )
			{
				$blnIsGood = false;
				Validation\CreateErrorMessage('Bad Report ID!','Report Error');
				
			} else if($Report->GetReportStatus() == STATUS_COMPLETE)
			{
				$blnIsGood = false;
				Validation\CreateErrorMessage('Report Already Complete!','Report Error');
			}
			
			Validation\CreateErrorMessage(Validation\ValidateDate($StartDate,true),'Start Date');
			Validation\CreateErrorMessage(Validation\ValidateDate($EndDate,true),'End Date');
			
			if( $blnIsGood )
			{
				$str_safe_s_date = funSfDateStr($StartDate);
				$str_safe_e_date = funSfDateStr($EndDate);
				
				Validation\CreateErrorMessage(Validation\ValidateDates($str_safe_s_date,$str_safe_e_date),'End Date');
			}
			
			if( !$thisUser->IsMyGroup($GroupID) )
			{
				//Validate Group
				Validation\CreateErrorMessage(' ID Bad!','Group');
			} 
			
			if( $blnIsGood )
			{
					$GroupObject = \Business\Group::LoadGroup($GroupID);
					//$ReportName = $GroupObject->GetGroupName().' Group Report.';
					//$DatesString = 'Between '.funAusDateFormat($str_safe_s_date).' and '.funAusDateFormat($str_safe_e_date);
					
					//Meeting Details
					Business\UserReportField::Create($ReportID,'Total Meetings Held:',$GroupObject->CountMeetingsAttendedInPeriod($str_safe_s_date,$str_safe_e_date));
					Business\UserReportField::Create($ReportID,'Total Meetings Scheduled:',$GroupObject->CountMeetingsScheduledInPeriod($str_safe_s_date,$str_safe_e_date));
					
					
					
					//New Stats
					$FTStats = $GroupObject->LoadFirstTimerStatsBetweenDates($str_safe_s_date,$str_safe_e_date);
					Business\UserReportField::Create($ReportID,'First Timers:',$FTStats['Total_Attendees']);
					
					$FTAStats = $GroupObject->CountFirstTimerMultipleAttendancesByGroupDates($str_safe_s_date,$str_safe_e_date,2);
					Business\UserReportField::Create($ReportID,'Continuing First Timers:',$FTAStats['Total_Aees']);
					
					
					$NCGStats = $GroupObject->CountNewCommittedGrowersByGroupDates($str_safe_s_date,$str_safe_e_date);
					Business\UserReportField::Create($ReportID,'New Committed Growers:',$NCGStats['Total_Aees']);
					
					$LCGSTats = $GroupObject->CountNSinceLastAttendance(3,$str_safe_s_date,$str_safe_e_date);
					Business\UserReportField::Create($ReportID,'Lapsed Committed Growers:',$LCGSTats);
					
					//No of committed growers at end of period
					$ECGSTats = $GroupObject->CountComGrowAttAtEnd($str_safe_e_date);
					Business\UserReportField::Create($ReportID,'Committed Growers At End:',$ECGSTats);
					
					Business\UserReportField::Create($ReportID,'Total Field Worker Attendances:',$GroupObject->FieldWorkerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date));
					
					//FirstTimerAttendancesByGroupDates
					/*
					//Attendees Details
					$AttMembers = $GroupObject->CountMemberAttendancesBetweenDates($str_safe_s_date,$str_safe_e_date);
					$Total += $AttMembers;
					Business\UserReportField::Create($ReportID,'Total Member Attendances:',$AttMembers);
					$AttOrganisers = $GroupObject->CountVolunteerAttendancesBetweenDates(ORGANISER,$str_safe_s_date,$str_safe_e_date);
					$Total += $AttOrganisers;
					Business\UserReportField::Create($ReportID,'Total Organiser Attendances:',$AttOrganisers);
					$AttRecorders = $GroupObject->CountVolunteerAttendancesBetweenDates(RECORDER,$str_safe_s_date,$str_safe_e_date);
					$Total += $AttRecorders;
					Business\UserReportField::Create($ReportID,'Total Recorder Attendances:',$AttRecorders);

					$AttSponsors = $GroupObject->CountVolunteerAttendancesBetweenDates(SPONSOR,$str_safe_s_date,$str_safe_e_date);
					$Total += $AttSponsors;
					Business\UserReportField::Create($ReportID,'Total Sponsor Attendances:',$AttSponsors);
					$AttCommunityObservers = $GroupObject->CountCommunityObserversInPeriod($str_safe_s_date,$str_safe_e_date);
					$Total += $AttCommunityObservers;
					Business\UserReportField::Create($ReportID,'Total Community Observer Attendances:',$AttCommunityObservers);
					Business\UserReportField::Create($ReportID,'Total Attendances:',$Total);
					*/
					/*
					Business\UserReportField::Create($ReportID,'Total Community Observers:',$GroupObject->CountCommunityObserversInPeriod($str_safe_s_date,$str_safe_e_date));
					Business\UserReportField::Create($ReportID,'Total Committed Growers:',$GroupObject->CountCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date));
					Business\UserReportField::Create($ReportID,'Total New Committed Growers:',$GroupObject->CountNewCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date));
					
					
					//Attendees Attendances
					Business\UserReportField::Create($ReportID,'Committed Growers who didn/t attend in the last eight meetings:',$GroupObject->CountNSinceLastAttended(8,$str_safe_s_date,$str_safe_e_date));
					Business\UserReportField::Create($ReportID,'Total Committed Grower Attendances:',$GroupObject->CommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date));
					//Business\UserReportField::Create($ReportID,'Total Non Committed Grower Attendances:',$GroupObject->NonCommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date));
					Business\UserReportField::Create($ReportID,'Total New Organiser Attendances:',$GroupObject->OrganiserAttendancesInPeriod($str_safe_s_date,$str_safe_e_date));
					Business\UserReportField::Create($ReportID,'Total Recorder Attendances:',$GroupObject->RecorderAttendancesInPeriod($str_safe_s_date,$str_safe_e_date));
					
					
					//Totals
					Business\UserReportField::Create($ReportID,'Total Attendees:',$GroupObject->TotalAttendeesInPeriod($str_safe_s_date,$str_safe_e_date));
					Business\UserReportField::Create($ReportID,'Total Attendances:',$GroupObject->TotalAttendancesInPeriod($str_safe_s_date,$str_safe_e_date));
					*/
							
				$Report->SetReportStatus(STATUS_COMPLETE);
				$Report->SaveMe();
				
				echo 'Report Query Good!';
				
				
			} else {
				//display error message
				
				$Report->SetReportStatus(STATUS_ERROR);
				$Report->SaveMe();
				
				echo 'Report Query Error!';
				
			}
		
		} // end submitted check
			
	} // end user check
?>
