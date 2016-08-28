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
					
					$TrendStats = $RegionObject->LoadTrendStats($str_safe_s_date,$str_safe_e_date);
					
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','NGM',1);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','NCM',2);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','NSM',3);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','NOM',4);
					
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TFT',5);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCO',6);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCG',7);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TNCG',8);
					
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCGLA',9);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TCGA',10);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','ACGA',11);
					
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TGO',12);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TGR',13);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TFG',14);
					Business\UserReportField::CreateWithColumns($ReportID,'Heading','TFA',15);
					
					
					foreach( $TrendStats as $Stat )
					{
						//totals will be calculated when report is displayed
						//echo $Stat['Name'].' : '.$Stat['MA'].' : '.$Stat['MESCH'].' : '.$Stat['TFT'].' : '.$Stat['ComObs'].' : '.$Stat['ComGrow'].' : '.$Stat['NewComGrow'].' : '.$Stat['CGLapsed'];
						
						
						$Name = $Stat['Name'];
						
						//$MAStat = $Stat['MA'];
						
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['MA'],1);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CL'],2);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['SPC'],3);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['OT'],4);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['FT'],5);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CO'],6);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CG'],7);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['NC'],8);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CGL'],9);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CGA'],10);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['CGAA'],11);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['GWO'],12);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['GWR'],13);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['GF'],14);
						Business\UserReportField::CreateWithColumns($ReportID,$Name,$Stat['FWA'],15);
						
						
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
