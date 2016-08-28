<?php
	require_once('async_application.php');
	
	$arrErrors = array();
	
	$intSubmitted = 1;
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	ignore_user_abort(true);
	set_time_limit(0);
	
	
	$Branch = $argv[1];
	$StartDate = $argv[2];
	$EndDate = $argv[3];
	$ReportID = $argv[4];
	
	$SesUser = $argv[5];
	
	$thisUser = Membership\User::LoadUser($SesUser,false,false);
	
	if($thisUser == NULL)
	{
		echo 'Bad User ID';
	} else {
		
		
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
			
			if( $blnIsGood )
			{
					$IsGood = true;
					
					$BranchObject = \Business\Branch::LoadBranch($Branch);
					
					$GroupsStats = $BranchObject->LoadStatisticsByBranch($str_safe_s_date,$str_safe_e_date);
					
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','Meetings Held',1);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','Meetings Scheduled',2);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','First Timer Growers',3);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','Continuing First Timers',4);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','New Committed Growers',5);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','Lapsed Committed Growers',6);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','Committed Growers At End',7);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','Field Worker Attendances',8);
					
					foreach( $GroupsStats as $Stat )
					{
						//totals will be calculated when report is displayed
						//echo $Stat['Name'].' : '.$Stat['MA'].' : '.$Stat['MESCH'].' : '.$Stat['TFT'].' : '.$Stat['ComObs'].' : '.$Stat['ComGrow'].' : '.$Stat['NewComGrow'].' : '.$Stat['CGLapsed'];
						
						
						$Name = $Stat['Name'];
						
						//$MAStat = $Stat['MA'];
						
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['MA'],1);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['MESCH'],2);
						
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['FTG'],3);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['FTCG'],4);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['NCG'],5);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['LCG'],6);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['AEPCG'],7);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['FWAtt'],8);
						
						
					}
						
					
				if($IsGood)
				{
					$Report->SetReportStatus(STATUS_COMPLETE);
					$Report->SaveMe();
					
					echo 'Report Query Good!';
				}
				
			} else {
				//display error message
				
				$Report->SetReportStatus(STATUS_ERROR);
				$Report->SaveMe();
				
				echo 'Report Query Error!';
				
			}
		
		} // end submitted check
			
	} // end user check
?>
