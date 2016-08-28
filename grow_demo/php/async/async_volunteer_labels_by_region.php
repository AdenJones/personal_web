<?php
	require_once('async_application.php');
	
	$arrErrors = array();
	
	$intSubmitted = 1;
	
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	
	
	$RegionID = $argv[1];
	$StartDate = $argv[2];
	$EndDate = $argv[3];
	$LabelsID = $argv[4];
	
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
			
			$LabelsID = intval($LabelsID);
			
			$Labels = Business\UserLabels::LoadUserLabels($LabelsID);
			
			if($Labels == NULL )
			{
				$blnIsGood = false;
				Validation\CreateErrorMessage('Bad Labels ID!','Labels Error');
				
			} else if($Labels->GetLabelsStatus() == STATUS_COMPLETE)
			{
				$blnIsGood = false;
				Validation\CreateErrorMessage('Labels Already Complete!','Labels Error');
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
					
					$RegionLabels = $RegionObject->LoadRegionVolunteerLabels($str_safe_s_date,$str_safe_e_date);
					
					foreach( $RegionLabels as $Label )
					{
						
						$Name = $Label['fld_first_name'].' '.$Label['fld_last_name'];
						
						Business\UserLabelsField::Create($LabelsID,$Label['fld_user_id'],$Name,$Label['fld_address'],$Label['fld_suburb'],$Label['fld_postcode'],$Label['fld_state_abbreviation']);
						
					}
						
					
				if($IsGood)
				{
					$Labels->SetLabelsStatus(STATUS_COMPLETE);
					$Labels->SaveMe();
					
					echo 'Labels Query Good!';
				}
				
			} else {
				//display error message
				
				$Labels->SetLabelsStatus(STATUS_ERROR);
				$Labels->SaveMe();
				
				echo 'Labels Query Error!';
				
			}
		
		} // end submitted check
			
	} // end user check
?>
