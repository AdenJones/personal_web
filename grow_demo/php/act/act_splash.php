<?php

/* Action file for logging in */

//capture variables if form has been submitted

$strUName = funRqScpVar('strUName','');
$strPWord = funRqScpVar('strPWord','');

if(!isset($_SESSION['failed_times']))
{
	$_SESSION['failed_times'] = array();
}

//end capture variables

//initialise errors array outside of val to avoid warnings
$arrErrors = array();

//begin validation routines
if (isset($_REQUEST['form_submitted']))
{
	
	$blnIsGood = true;
	
	//Validate User Name
	Validation\CreateErrorMessage(Validation\ValidateString($strUName,6,45),'User Name');
		
	//Validate Password
	Validation\CreateErrorMessage(Validation\ValidateString($strPWord,6,45),'Password');
	
	if(count($_SESSION['failed_times'])  >= 10)
	{
		$arr_failed_times = array_values($_SESSION['failed_times']);
		
		$latest_failed_attempt = new DateTime($arr_failed_times[count($_SESSION['failed_times']) -1]);
		
		$ten_minutes = new DateInterval('PT10M');
		
		$ten_minutes_ago = new DateTime(date("Y-m-d H:i:s"));
		
		$ten_minutes_ago->sub($ten_minutes);
		
		//echo $arr_failed_times[9];
		
		if($latest_failed_attempt >= $ten_minutes_ago)
		{
			//echo 'Ten Minutes:'.$ten_minutes->format('%y-%m-%d %h:%i:%s').'; Latest failed before conversion:'.$arr_failed_times[9].'; Latest failed:'.$latest_failed_attempt->format('Y-m-d H:i:s').'; ten minutes from now:'.$ten_minutes_ago->format('Y-m-d H:i:s');
			
			$can_try_again = $latest_failed_attempt->diff($ten_minutes_ago);
			
			$interval = $can_try_again->format('%i minutes and %s seconds');
			
			Validation\CreateErrorMessage(" number of failed login attempts. You will be able to try again in $interval!",'Bad');
		}
	}
	
	

//begin check user password
	if($blnIsGood)
	{	
		
		$thisUser = Membership\ValidateUser($strUName,$strPWord,$arrErrors);
		
		if(is_null($thisUser))
		{
			
			$_SESSION['failed_times'][] = date("Y-m-d H:i:s");
			
		} else {
			Membership\SetLoggedInUser($thisUser);
			//redirect to default page
			header( "Location: $lnk_default_page" );
			//ensure no further processing is performed
			exit;
		}
		
		
		
	}
	
}//end if form has been submitted


?>