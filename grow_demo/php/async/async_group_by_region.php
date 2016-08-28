<?php
	require_once('async_application.php');
	
	$arrErrors = array();
	
	$intSubmitted = 1;
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	/*
	$RegionID = $RegionID;
	$StartDate = $StartDate;
	$EndDate = $EndDate;
	$ReportID = $ReportID;
	$SesUser = $_SESSION['User']->GetUserID();
	*/
	
	
	$RegionID = $argv[1];
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
						
					$RegionObject = \Business\Region::LoadRegion(intval($RegionID));
					
					$GroupsStats = $RegionObject->LoadGroupStats($str_safe_s_date,$str_safe_e_date);
					
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TMH',1);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TMS',2);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TFT',3);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCO',4);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCG',5);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TNCG',6);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCGLA',7);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCA',8);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TOA',9);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TRA',10);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TFA',11);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TAees',12);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TAces',13);
					
					foreach( $GroupsStats as $Stat )
					{
						//totals will be calculated when report is displayed
						//echo $Stat['Name'].' : '.$Stat['MA'].' : '.$Stat['MESCH'].' : '.$Stat['TFT'].' : '.$Stat['ComObs'].' : '.$Stat['ComGrow'].' : '.$Stat['NewComGrow'].' : '.$Stat['CGLapsed'];
						
						
						$Name = $Stat['Name'];
						
						//$MAStat = $Stat['MA'];
						
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['MA'],1);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['MESCH'],2);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['TFT'],3);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['ComObs'],4);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['ComGrow'],5);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['NewComGrow'],6);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CGLapsed'],7);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CGAttendances'],8);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['OrgAtt'],9);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['RecAtt'],10);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['FWAtt'],11);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['TotAttees'],12);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['TotAtes'],13);
						
						
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
