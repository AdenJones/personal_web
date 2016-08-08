<?php
namespace Validation;

function ValidateGroup($GroupID) //checks that the current user is allowed access to the given group
{
	$Group =\Business\Group::LoadGroup(intval($GroupID));
	
	if($Group == NULL)
	{
		return " failed. Bad Group ID!";
			
	}
	
	if(!$_SESSION['User']->IsMyGroup($GroupID))
	{
		return " failed. You are not allowed access to the given group!";
	} else {
		return 'good';
	}
	
}

function ValidateTimeRange($tme_start_time,$tme_end_time,$required = false) 
{
	$tme_start_time = trim($tme_start_time);
	$tme_end_time = trim($tme_end_time);
	
	if(($tme_start_time == '' or $tme_end_time == '') and !$required)
	{
		return 'good';
	}
	
	$tme_start_time = strtotime($tme_start_time);
	$tme_end_time = strtotime($tme_end_time);
	
	$str_message = "must be greater than start time";
	
	
	if( $tme_start_time > $tme_end_time )
	{
		return $str_message;
	} else {
		return 'good';
	}
	
	
}

function ValidateTime($strTime,$required = false)
{
	$strTime = trim($strTime);
	
	if($strTime == '' and !$required)
	{
		return 'good';
	}
	
	//create an array of hh : mm
	$arrTime = explode(":",$strTime);
	//create the message
	
	if( $required and $strTime == '')
	{
		$strMessage = "is required and must be in the format &quot;hh:mm&quot;";
	}
	else
	{
		$strMessage = "$strTime is not a valid time and must be in the format  &quot;hh:mm&quot;";
	}
	
		
	//ensure correct number of elements in the array
	if(count($arrTime) != 2)
	{
		return $strMessage;
	}
	
	//ensure is valid date
	if(!funCheckTime($arrTime)) 
	{
		return $strMessage;
	} 
	
	
	//else return good
	return 'good';
	
}

function ValidatePeriodSelect($str_recurrence_id,$int_recurrence)
{
	$Period = chkValPeriod($str_recurrence_id,$int_recurrence);
	
	if($Period->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateStaffUserType($UserTypeID)
{
	$UserTypeID = intval($UserTypeID);
	
	$UserStaffUserType = chkValStaffUserType($UserTypeID);
	
	if($UserStaffUserType->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateVolunteerUserType($UserTypeID)
{
	$UserTypeID = intval($UserTypeID);
	
	$UserStaffUserType = chkValVolunteerUserType($UserTypeID);
	
	if($UserStaffUserType->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateNoMeetingReason($ReasonID)
{
	$ReasonID = intval($ReasonID);
	
	$Reason = chkValReason($ReasonID);
	
	if($Reason->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateSecurityLevel($SecurityLevelID)
{
	$SecurityLevelID = intval($SecurityLevelID);
	
	$SecurityLevel = chkValSecurityLevel($SecurityLevelID);
	
	if($SecurityLevel->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateImportance($ImportanceID)
{
	$ImportanceID = intval($ImportanceID);
	
	$Importance = chkValImportance($ImportanceID);
	
	if($Importance->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateGender($GenderID)
{
	$GenderID = intval($GenderID);
	
	$Gender = chkValGender($GenderID);
	
	if($Gender->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateAllUserTypes($UserTypeID)
{
	$UserTypeID = intval($UserTypeID);
	
	$UserAllUserType = chkValAllUserType($UserTypeID);
	
	if($UserAllUserType->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
	
}

function ValidateGroupType($intID)
{
	$intID = intval($intID);
	
	$GroupType = chkValGroupType($intID);
	
	if($GroupType->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateVolunteerStaffUser($intID)
{
	$intID = intval($intID);
	
	$VolunteerStaffUser = chkValVolStaffUser($intID);
	
	if($VolunteerStaffUser->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateStaffUser($intID)
{
	$intID = intval($intID);
	
	$StaffUser = chkValStaffUser($intID);
	
	if($StaffUser->rowCount() == 0)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateStaffUserForAttendance($intID,$GroupID,$Date)
{
	$FirstCheck = ValidateStaffUser($intID);
	
	if($FirstCheck ==  'good')
	{
		if(\Business\Attendance::LoadAttendanceByGroupUserDate($GroupID,$intID,$Date) != NULL)
		{
			return " has already been entered!";
		} else {
			return $FirstCheck;
		}
	} else {
		return $FirstCheck;
	}
}

function ValidateMemberUserForAttendance($intID,$GroupID,$Date)
{
	$FirstCheck = ValidateMemberUser($intID);
	
	if($FirstCheck ==  'good')
	{
		if(\Business\Attendance::LoadAttendanceByGroupUserDate($GroupID,$intID,$Date) != NULL)
		{
			return " has already been entered!";
		} else {
			return $FirstCheck;
		}
	} else {
		return $FirstCheck;
	}
}

function ValidateMemberUser($intID)
{
	$intID = intval($intID);
	
	$MemberUser = chkValMemberUser($intID);
	
	if($MemberUser->rowCount() == 0)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateVenue($intID)
{
	$intID = intval($intID);
	
	$Venue = chkValVenue($intID);
	
	if($Venue->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateGroupRole($intID)
{
	$intID = intval($intID);
	
	$GroupRole = chkValGroupRole($intID);
	
	if($GroupRole->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateRegion($intID)
{
	$intID = intval($intID);
	
	$Region = chkValRegion($intID);
	
	if($Region->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateBranch($intID)
{
	$intID = intval($intID);
	
	$Branch = chkValBranch($intID);
	
	if($Branch->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateState($intID)
{
	$intID = intval($intID);
	
	$State = chkValState($intID);
	
	if($State->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateStaffJobClass($intID)
{
	$intID = intval($intID);
	
	$JobClass = chkValStaffJobClass($intID);
	
	if($JobClass->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateStaffJobClassVolunteer($intID)
{
	$intID = intval($intID);
	
	$JobClass = chkValStaffJobClassVolunteer($intID);
	
	if($JobClass->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function ValidateDates($str_start,$str_end)
{
	if( $str_start > $str_end )
	{
		return ' must be less than end date';
	}
	
	return 'good';
}



//validate dates
function ValidateDate($strDate,$required = false)
{
	
	if(trim($strDate) == '' and !$required)
	{
		return 'good';
	}
	
	$strDate = trim($strDate);
	//create an array of dd / mm / yyyy
	$arrDate = explode("/",$strDate);
	//create the message
	
	if( $required and trim($strDate) == '')
	{
		$strMessage = "$strDate is required and must be in the format &quot;dd/mm/yyyy&quot;";
	}
	else
	{
		$strMessage = "$strDate is not a valid date and must be in the format &quot;dd/mm/yyyy&quot;";
	}
	
	$intYrUpper = 2100;
	$intYrLower = 1800;
	
	//ensure correct number of elements in the array
	if(count($arrDate) != 3)
	{
		return $strMessage;
	}
	
	//ensure is valid date
	if(!checkdate($arrDate[1],$arrDate[0],$arrDate[2])) 
	{
		return $strMessage;
	} elseif($arrDate[2] < 1800 || $arrDate[2] > $intYrUpper) //ensure year is reasonable
	{
		return "year must be between $intYrLower and $intYrUpper";
	}
	
	
	//else return good
	return 'good';
	
}

/* Main error reporting function */
function CreateErrorMessage($strMsg,$strIndex)
{
	global $arrErrors;
	global $blnIsGood;
	
	if( $strMsg != 'good')
	{
		$arrErrors[$strIndex] = $strMsg;
		$blnIsGood = false;
	}
}

/* function for validating string inputs */
function ValidateString($strString,$intMinLength,$intMaxLength)
{
	//remember to add trim functions to get rid of white space in strings
	$strString = trim($strString);
	$intStrLength = strlen($strString);
	
	if($intStrLength < $intMinLength)
	{
		return " is required and must be at least $intMinLength characters long";
	}
	
	if( $intStrLength > $intMaxLength )
	{
		return " must be less than $intMaxLength characters long";
	}
	
	//else case
	return 'good';
}

/* Function for comparing strings and outputting errors */
function CreateErrorMessageStringMatch($strOne,$strTwo,$strIndex)
{
	
	if(strcmp($strOne,$strTwo) != 0)
	{
		CreateErrorMessage(' don\'t match',$strIndex);
	}
	
	
}

function ValidateBoolean($bl_boolean)
{
	global $true;
	global $false;
	
	if( $bl_boolean != $true and $bl_boolean != $false )
	{
		return " contains an invalid value";
	}
	
	return 'good';
}

/* function for validating emails */
function ValidateEmail($strEmail,$required = false)
{
	$pattern = '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
	
	$strLength = strlen(trim($strEmail));
	
	$match = preg_match($pattern,$strEmail);
	
	if($required)
	{
		if($match)
		{
			return true;
		} else {
			return false;
		}
	} else {
		if($strLength == 0)
		{
			return true;
		} else {
			if($match)
			{
				return true;
			} else {
				return false;
			}
		} 
	}
}

function ValidateNumber($number,$required = false,$min_value = 0,$max_value = 10000)
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
	
	if( $number < $min_value )
	{
		return ' cannot be less than '.$min_value.'!';
	}
	
	if( $number > $max_value )
	{
		return ' cannot be greater than '.$max_value.'!';
	}
	
	return 'good';
}

/* Email specific error reporting function */
function CreateErrorMessageEmail($strMsg,$blValEmail,$strIndex)
{
	
	//check if it is an acceptable string and a valid email
	if( $strMsg != 'good' || !$blValEmail)
	{
		
		if ($strMsg == 'good')
		{
			$strMsg = ' is invalid';
		
		} 
		
		CreateErrorMessage($strMsg,$strIndex);
	}

}


?>