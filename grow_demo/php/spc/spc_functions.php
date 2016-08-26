<?php


//use return output true to return output of command for error testing
function funExecPlatformIndependant($command,$returnOutput = false)
{
	if($returnOutput)
	{
		return exec($command);
	} else {
		
		if (substr(php_uname(), 0, 7) == "Windows"){
			pclose(popen("start ". $command, "r")); 
			//pclose(popen("start /B ". $cmd, "r")); 
		}
		else {
			exec($command . " > process.out 2> process.err < /dev/null &"); 
			
		} 
		
	}
}

function funGetMonthsBetweenDates($StartDate,$EndDate) // takes only universal formated dates
{
	//basic error handling
	if( $StartDate > $EndDate )
	{
		return NULL;
	}
	
	$Start = explode('-',$StartDate);
	$End = explode('-',$EndDate);
	
	$AdjustedStart = new DateTime($Start[0].'-'.$Start[1].'-01'); //assume first day of the week
	
	$AdjustedEnd = new DateTime($End[0].'-'.$End[1].'-28'); //safe to assume 28th
	
	$OneMonth = new DateInterval('P1M');
	
	$arrDates = array();
	
	
	//$arrDates[] = $AdjustedStart;
	//$arrDates[] = $AdjustedEnd;
	
	while( $AdjustedStart < $AdjustedEnd )
	{
		$arrDates[] = clone $AdjustedStart; //note the use of clone here
		
		$AdjustedStart->add($OneMonth);
		
	}
	
	
	return $arrDates;
}



function funInsertInArrayPos($array, $pos, $value)
{
  $result = array_merge(array_slice($array, 0 , $pos), array($value), array_slice($array,  $pos));
  return $result;
}

function funGetMonthNameByIndex($months_array,$int_month)
{
	foreach($months_array as $month)
	{
		if($month['int'] == $int_month)
		{
			return $month['name'];
		}
	}
	
	return 'Bad Month Submitted!';
}

function funIsPreSelectedRoomBookingDomain($id_room_booking_domain,$arr_pre_selected_room_booking_domains)
{
	foreach($arr_pre_selected_room_booking_domains as $domain)
	{
		if($domain['id_room_booking_domain'] == $id_room_booking_domain)
		{
			return 1;
		}
	}
	
	return 0;
}

function funIsSelectedGroup($id_group,$arr_selected_groups)
{
	foreach( $arr_selected_groups as $group )
	{
		if($id_group == $group['id_group'])
		{
			return true;
		}
	}
	
	return false;
}

function funIsSelectedDomain($id_room_booking_domain,$arr_selected_room_booking_domains)
{
	foreach( $arr_selected_room_booking_domains as $domain )
	{
		if($id_room_booking_domain == $domain['id_room_booking_domain'])
		{
			return true;
		}
	}
	
	return false;
}

function funValAvailableRoom($int_room_id,$arr_available_rooms)
{
	foreach($arr_available_rooms as $room)
	{
		if($room['id_room'] == $int_room_id)
		{
			return 'good';
		}
	}
	
	return ' select a valid room!';
}

function funValGroupID($int_group_id)
{
	$obj_group = getGroup($int_group_id);
	$arr_group = $obj_group->fetchAll();
	
	if(count($arr_group) == 0)
	{
		return ' is required!';
	}
	
	return 'good';
	
}

function funValNumber($number,$required = false)
{
	$number = trim($number);
	
	if(!$required and $number == '')
	{
		return 'good';
	}
	
	if( $required and $number == '' )
	{
		return ' is required and must be a number!';
	} 
	
	if(!is_numeric($number))
	{
		return ' must be a number!';
	}
	
	return 'good';
}

function funGetMonth($date)
{
	$arr_date = explode('-',$date);
	
	return $arr_date[1];
}

//returns an array of all the dates on which a group occurred
function funGetGroupDates($int_group_id)
{
	$obj_group = getGroup($int_group_id);
	$arr_group = $obj_group->fetch();
	
	$str_start_date = $arr_group['fld_start_date'];
	$str_end_date = $arr_group['fld_end_date'];
	
	$dte_start_date = date_create($str_start_date);
	$dte_end_date = date_create($str_end_date);
	
	$dte_today = date_create(date('Y-m-d H:i:s'));
	
	//get the interval
	$period_code = funConvertPeriodToCode($arr_group['fld_recurrency_string']);
	$int_interval = funPeriodInterval($arr_group['fld_recurrency_string'],$arr_group['fld_recurrency_int']);
	$ivl_interval = new DateInterval('P'.$int_interval.$period_code);
	
	$arr_dates = array();
	
	//choose the end date
	if( $str_end_date == '')
	{
		$dte_end_date = $dte_today;
	}
	
	while($dte_start_date < $dte_end_date)
	{
		$arr_dates[] = $dte_start_date->format('Y-m-d');; //append the value in a form that is safe for entry into the database
		$dte_start_date = date_add($dte_start_date,$ivl_interval); //increment the date
	}
	arsort($arr_dates); //reverse the order of the array
	return $arr_dates;
	
}

function funFindDateKeyInGroupReflectionArray($dte_date,$arr_array)
{
	
	foreach($arr_array as $item)
	{
		if($item['fld_dated'] == $dte_date)
		{
			return $item['id_group_reflection'];
		}
	}
	
	return -1;
}

/* Global Functions */
function funGetGroupsAtt($int_service_id,$dte_att_date,$tme_start_time,$tme_end_time) //gets groups that occur during the period
{
	$dte_safe_att_date = date_create($dte_att_date);
	$tme_start = strtotime($tme_start_time);
	$tme_end = strtotime($tme_end_time);
	
	$groups = getGroupsById($int_service_id,'AND fld_deleted = 0'); //get the groups for the selected service
	$arr_groups = array();
	
	foreach( $groups as $group )
	{
		//check end date to save 
		$str_end_date = $group['fld_end_date'];
		$dte_this_end = date_create($str_end_date);
		$dte_this_start = date_create($group['fld_start_date']);
		
		if( $dte_this_start > $dte_safe_att_date ) //if the event started after the attendance date
		{
			continue;
		}
				
		if($str_end_date != '')
		{
			
			if( $dte_this_end < $dte_safe_att_date ) //break out if the end date is less than the attendance date
			{
				continue;
			}
		}
		
		//grab the interval
		$period_code = funConvertPeriodToCode($group['fld_recurrency_string']);
		$int_interval = funPeriodInterval($group['fld_recurrency_string'],$group['fld_recurrency_int']);
		$ivl_interval = new DateInterval('P'.$int_interval.$period_code);
		
		//loop through the intervals for the given date
		while( $dte_this_start < $dte_safe_att_date )
		{
			$dte_this_start = date_add($dte_this_start,$ivl_interval);
			
		}
		
		//if the given event doesn't occur on the attendance date
		if( $dte_this_start != $dte_safe_att_date )
		{
			continue;
		} else {
			//collect times
			$tme_this_start = strtotime($group['fld_start_time']);
			$tme_this_end = strtotime($group['fld_end_time']);
			
			//compare times
			if( $tme_start <= $tme_this_end and $tme_end >= $tme_this_start )
			{
				$this_item = array('id'=>$group['id_group'],'name'=>$group['fld_group_name']);
				$arr_groups[] = $this_item; //push the item onto the end of the array
				//$arr_groups[] = $group['fld_group_name'];
			} else {
				continue;	
			}
			
		}
		
	} //end greater loop
	
	return $arr_groups;
}



function funCreateTabs($intNo)
{
	$strTabs = '';
	for($i = 0; $i < $intNo; $i++)
	{
		$strTabs .= "\t";
	}
	
	return $strTabs;
}

function funConvertPeriodToCode($period)
{
	switch($period)
	{
		case 'DAY':
			return 'D';
			break;
		case 'WEEK':
			return 'W';
			break;
		case 'MONTH':
			return 'M';
			break;
		case 'QUARTER':
			return 'M'; //months * 3
			break;
		case 'YEAR':
			return 'Y';
			break;
		
	}
}

function funConvertPeriodToString($period)
{
	switch($period)
	{
		case 'DAY':
			return 'Days';
			break;
		case 'WEEK':
			return 'Weeks';
			break;
		case 'MONTH':
			return 'Months';
			break;
		case 'QUARTER':
			return 'Quarters'; //months * 3
			break;
		case 'YEAR':
			return 'Years';
			break;
		default:
			return 'Not Set';
	}
}

function funPeriodInterval($period,$interval)
{
	switch($period)
	{
		case 'DAY':
			return $interval;
			break;
		case 'WEEK':
			return $interval;
			break;
		case 'MONTH':
			return $interval;
			break;
		case 'QUARTER':
			return $interval * 3; //months * 3
			break;
		case 'YEAR':
			return $interval;
			break;
		
	}
}

function funGroupsCalcNextOccurrence($str_period,$int_interval,$str_start_date,$str_end_date,$dte_today)
{
	
	$dte_start_date = date_create($str_start_date);
							
	if( $str_end_date == '' )
	{
		$dte_end_date = 'not set';
	} else {
		$dte_end_date = date_create($str_end_date);
	}
	
	$period_code = funConvertPeriodToCode($str_period);
	$int_interval = funPeriodInterval($str_period,$int_interval);
	$ivl_interval = new DateInterval('P'.$int_interval.$period_code);
	
	$dte_this = $dte_start_date;
		
	while( $dte_this < $dte_today )
	{
		$dte_this = date_add($dte_this,$ivl_interval);
		
	}
	
	if( $dte_this < $dte_end_date or $dte_end_date == 'not set' )
	{
		$str_next_occurence = $dte_this->format('Y-m-d');
	} else {
		
		$str_next_occurence = 'Event expired';
	}
	
	return $str_next_occurence;
}

/* function for outputing $_REQUEST scope variables */
function funRqScpVar($strRequest,$varDefVal)
{
	if (isset($_REQUEST[$strRequest]))
	{
		//html entities is used here to protect
		//against html insertion attacks
		return trim(htmlentities($_REQUEST[$strRequest]));
	} else {
		return $varDefVal;
	}
}

function funRqScpVarNonSafe($strRequest,$varDefVal)
{
	if (isset($_REQUEST[$strRequest]))
	{
		
		return trim($_REQUEST[$strRequest]);
	} else {
		return $varDefVal;
	}
}

function funValBoolean($bl_boolean)
{
	global $true;
	global $false;
	
	if( $bl_boolean != $true and $bl_boolean != $false )
	{
		return " contains an invalid value";
	}
	
	return 'good';
}


/* Main error reporting functions */
function funValUserType($intID,$strTypes)
{
	$UserType = chkValUserType($intID,$strTypes);
	
	if($UserType->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funValDCCode($int_id)
{
	$obj_code = chkValDCCode($int_id);
	
	if($obj_code->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funValRoomBookingStatus($intID)
{
	$Status = chkValStatus($intID);
	
	if($Status->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funValGender($intID,$strTypes)
{
	$Gender = chkValGender($intID,$strTypes);
	
	if($Gender->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funValStaffReminderType($strID)
{
	$StfRemType = chkValStfRemType($strID);
	
	if($StfRemType->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funValReferralSource($intID)
{
	$obj_ref_src = chkValReferralSource($intID);
	
	if($obj_ref_src->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funValDiagnosis($intID)
{
	$obj_diag = chkValDiagnosis($intID);
	
	if($obj_diag->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funValServiceName($int_id)
{
	$Service = chkValService($int_id);
	
	if($Service->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function funChkStaffEndDate($user_id)
{
	global $dbh;
	
	$objEndDate = chkIsStaff($user_id);
	
	if( $objEndDate->rowCount() != 1)
	{
		return true;
	} else {
		
		$arrEndDate = $objEndDate->fetch();
		
		if( is_null($arrEndDate['fld_end_date']))
		{
			return true;
		} else {
			
			$dteEndDate = date_create($arrEndDate['fld_end_date']);
			$dteToday = date_create(date('Y-m-d H:i:s'));
			
			//$strED = $dteEndDate->format('d-M-y');
			//$strTD = $dteToday->format('d-M-y');
			
			//echo 'End Date: '.$strED.'; Today: '.$strTD;
			
			if( $dteEndDate > $dteToday )
			{
				return true;
			} else {
				return false;
			}
			
			
		}
		
	}
}

/* function for highlighting erroneous fields */
function funHighlightBlkErrors($arrErrors,$arrKeys)
{
	foreach($arrKeys as $key)
	{
		if( array_key_exists($key,$arrErrors) )
		{
			return 'highlight_blk_error';
		}
	}
	
}

/* function for highlighting erroneous fields */
function funHighlightErrors($arrErrors,$arrKeys)
{
	foreach($arrKeys as $key)
	{
		if( array_key_exists($key,$arrErrors) )
		{
			return 'highlight_error';
		}
	}
	
}


/* function for choosing Javascript message target */
function funMsgTarget($arrErrors,$arrKey,$strDefault,$strNewTarget)
{
	if( array_key_exists($arrKey,$arrErrors) )
	{
		return $strNewTarget;
	} else {
		return $strDefault;
	}
	
	
}

/* function for displaying error messages */
function funDspErrorMsg($arrErrors,$strKey,$strID = '')
{
	//$strID allows me to dynamically insert an id value
	if( array_key_exists($strKey,$arrErrors) )
	{
		if($strID == '')
		{
			$strIDString = '';
		} else {
			$strIDString = "id=\"$strID\"";
		}
		
		echo "<img class=\"form_error_message\" src=\"/images/red_question.gif\" title=\"$strKey $arrErrors[$strKey]!\" />\n";
		//echo "<p $strIDString class=\"form_error_message\">$strKey $arrErrors[$strKey]!</p>\n"; //old error display
	}
}

/* function for displaying block error messages */
function funDspBlkErrorMsg($arrErrors,$strKey)
{
	
	if( array_key_exists($strKey,$arrErrors) )
	{
		return "$strKey $arrErrors[$strKey]!";
		
	}
}

/* function for validating MemberIds */
function funValMemberID($int_member_id)
{
	$obj_member = getMember($int_member_id);
	
	if( $obj_member->rowCount() != 1)
	{
		return ' is required!';
	} else {
		return 'good';
	}
}

function funValStaffID($int_staff_id)
{
	$obj_staff = getStaffMember($int_staff_id);
	
	if( $obj_staff->rowCount() != 1)
	{
		return ' is required!';
	} else {
		return 'good';
	}
}

/* function for validating dates */

function funCheckTime($arrTime)
{
	$int_hours = $arrTime[0];
	$int_minutes = $arrTime[1];
	
	$bln_hours = ( $int_hours < 0 or $int_hours > 23 );
	$bln_minutes = ( $int_minutes < 0 or $int_minutes > 59 );
	
	if($bln_hours or $bln_minutes)
	{
		return false;
	} else {
	return true;	
	}
}

function funValDateRange($str_s_date,$str_e_date,$int_no_days)
{
	$arr_s_date = explode("/",$str_s_date);
	$arr_e_date = explode("/",$str_e_date);
	
	$dte_s_date = date_create($arr_s_date[2].'-'.$arr_s_date[1].'-'.$arr_s_date[0]);
	$dte_e_date = date_create($arr_e_date[2].'-'.$arr_e_date[1].'-'.$arr_e_date[0]);
	
	$inv_diff = date_diff($dte_s_date,$dte_e_date);
	
	$int_diff = $inv_diff->format('%a');
	
	if($int_diff > $int_no_days)
	{
		return 'cannot be more than '.$int_no_days.' days greater than start date';
	}
	
	return 'good';
}

/* creates a safe date string */
function funSfDateStr($strDate)
{
	if( Validation\ValidateDate($strDate) == 'good')
	{
		if($strDate == '')
		{
			return 'null';
		} else {
			$arrDate = explode("/",$strDate);
			return "$arrDate[2]-$arrDate[1]-$arrDate[0]";
		}
		
	} else {
		return false;
	}
}

/* create random string function */
function funRandomString($length, $validChars)
{
	$strRandom = '';
	$intNumChars = strlen($validChars);
	
	for ($i = 0; $i < $length; $i++)
	{
		$intRandNum = mt_rand(1,$intNumChars);
		
		$chrRand = $validChars[$intRandNum-1];
		
		$strRandom .= $chrRand;
	}
	
	return $strRandom;
}

//formats a date to the specified string
function funDateFormat($strDate,$strFormat)
{
	if( $strDate == '' )
	{
		return '';
	} else {
		return date_format(date_create($strDate),$strFormat);
	}
}

function funTimeFormat($str_time)
{
	//strips out seconds from time values
	$arr_time = explode(":",$str_time);
	
	return $arr_time[0].':'.$arr_time[1];
}

/* format dates to dd/mm/yyyy */
function funAusDateFormat($strDate)
{
	if( $strDate == '' )
	{
		return 'not set';
	} else {
		return date_format(date_create($strDate), 'd M Y');
	}
}

function funMonthYearDateFormat($strDate)
{
	if( $strDate == '' )
	{
		return 'not set';
	} else {
		return date_format(date_create($strDate), 'F Y');
	}
}

/* format dates and times to dd/mm/yyyy hh:mm:ss */
function funAusDateTimeFormat($strDate)
{
	return date_format(date_create($strDate), 'D d M Y g:ia');
}

function funActingtoString($intBool)
{
	global $true;
	global $false;
	
	if($intBool == $true)
	{
		return 'Acting';
	}else if($intBool == $false){
		return '';
	}else{
		return 'Bad Value:'.$intBool;
	}
}

function funBoolToString($intBool)
{
	global $true;
	global $false;
	
	if($intBool == $true)
	{
		return 'true';
	}else if($intBool == $false){
		return 'false';
	}else{
		return 'Bad Value:'.$intBool;
	}
}


/* 	function for ensuring user id is an integer and 
	that the person exists in the database returns 0
	for fail and user id cast to an integer on success. */
	
function funIsValUserId($intUserId)
{
	global $dbh;
	
	//force to integer
	$intUserId = intval($intUserId);
	
	
	//check to see if UserId is in the database and 
	if($intUserId > 0)
	{
		try {
			$qryUserId = $dbh->prepare("SELECT id_member FROM tbl_members WHERE id_member = :idMember
									  ");
			$qryUserId->execute(array(':idMember' => $intUserId
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
									 
		if($qryUserId->rowCount() != 1)
		{
			return 0;
		} else {
			return $intUserId;
		}
		
	} else {
		return 0;
	}
	
	
}

function funAddStaffFacilitators($int_this_group,$csv_staff_ids)
{
	$arr_staff_ids = explode(",",$csv_staff_ids);
	
	foreach($arr_staff_ids as $staff_id)
	{
		$staff_id = intval($staff_id); //returns 0 on failure
		
		if($staff_id != 0)
		{
			if(!chkGroupStaff($int_this_group,$staff_id))
			{
				addGroupStaffFacilitator($int_this_group,$staff_id);
			}
		}
		
	}
}

function funAddMemberFacilitators($int_this_group,$csv_member_ids)
{
	$arr_member_ids = explode(",",$csv_member_ids);
	
	foreach($arr_member_ids as $member_id)
	{
		$member_id = intval($member_id); //returns 0 on failure
		
		if($member_id != 0)
		{
			if(!chkGroupMember($int_this_group,$member_id))
			{
				addGroupMemberFacilitator($int_this_group,$member_id);
			}
		}
		
	}
}

?>