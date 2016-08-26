<?php

namespace Membership; // entire contents of document are contained within this namespace

class User {
	protected $UserID;
	private $UserTypeID;
	private $UserName;
	private $HashedPassword;
	private $Salt;
	private $ScreenName;
	private $EmailAddress;
	private $Deleted;
	private $UserTypeName;
	private $DefaultPage;
	private $Pages = array(); //array of pages
	private $NavPages = array();
	private $UserTypeCategory; //distinction between admin / staff etc user types
	
	function __construct() {
		//empty constructor that may need to be altered later
	}
	
	public function SetUserID($UserID)
	{
		$this->UserID = $UserID;
	}
	
	public function GetUserID()
	{
		return $this->UserID;
	}
	
	public function SetUserTypeID($UserTypeID)
	{
		$this->UserTypeID = $UserTypeID;
	}
	
	public function GetUserTypeID()
	{
		return $this->UserTypeID;
	}
	
	public function SetUserName($UserName)
	{
		$this->UserName = $UserName;
	}
	
	public function GetUserName()
	{
		return $this->UserName;
	}
	
	public function SetHashedPassword($HashedPassword)
	{
		$this->HashedPassword = $HashedPassword;
	}
	
	public function GetHashedPassword()
	{
		return $this->HashedPassword;
	}
	
	public function SetSalt($Salt)
	{
		$this->Salt = $Salt;
	}
	
	public function GetSalt()
	{
		return $this->Salt;
	}
	
	public function SetScreenName($ScreenName)
	{
		$this->ScreenName = $ScreenName;
	}
	
	public function GetScreenName()
	{
		return $this->ScreenName;
	}
	
	public function SetEmailAddress($EmailAddress)
	{
		$this->EmailAddress = $EmailAddress;
	}
	
	public function GetEmailAddress()
	{
		return $this->EmailAddress;
	}
	
	public function SetDeleted($Deleted)
	{
		$this->Deleted = $Deleted;
	}
	
	public function SetUserTypeName($UserTypeName)
	{
		$this->UserTypeName = $UserTypeName;
	}
	
	public function GetUserTypeName()
	{
		return $this->UserTypeName;
	}
	
	public function SetUserTypeCategory($UserTypeCategory)
	{
		$this->UserTypeCategory = $UserTypeCategory;
	}
	
	public function GetUserTypeCategory()
	{
		return $this->UserTypeCategory;
	}
	
	public function SetDefaultPage($DefaultPage)
	{
		$this->DefaultPage = $DefaultPage;
	}
	
	public function GetDefaultPage()
	{
		return $this->DefaultPage;
	}
	
	public function GetNavPages()
	{
		return $this->NavPages;
	}
	
	public function HasAttendanceByGroupDate($GroupID,$Date)
	{
		return hasAttendanceByGroupDate($this->UserID,$GroupID,$Date);
	}
	
	public function GetSecurityLevel()
	{
		return getUserSecurityLevel($this->UserID);
	}
	
	public function get_state_activity_dates()
	{
		return \Business\StateUserActivityDates::load_user_state_activity_dates($this->UserID);
	}
	
	public function has_region($region_id)//only checks current regions
	{
		return check_has_region($this->UserID,$region_id);
	}
	
	public function LoadAllUserActivityDates()
	{
		return UserActivityDates::LoadAllUserActivityDates($this->UserID);
	}
	
	//this function can get a meaningful username for any kind of user
	public static function GetFunctionalUserName($UserID)
	{
		$UserID = intval($UserID);
		
		$thisUser = \Membership\User::LoadUserUnSafe($UserID);
		
		if( $thisUser == NULL )
		{
			return 'Deleted or archived user';
		} else {
			if( $thisUser->HasStaffInterface() )
			{
				$thisStaff = \Membership\Staff::LoadStaff($thisUser->GetUserID());
				
				return $thisStaff->GetFirstName().' '.$thisStaff->GetLastname();
			} elseif ($thisUser->HasMemberInterface()) {
				$thisMember = \Membership\Member::LoadMember($thisUser->GetUserID());
				
				return $thisMember->GetFirstName().' '.$thisMember->GetLastname();
			} else {
				return $thisUser->GetScreenName();
			}
		}
	}
	
	public function UniversalLoadRegions()
	{
		//Group Users shouldn't have access to this
		global $FieldWorker;
		global $StateUser;
		//national user and staff admin have access to all regions'
		if( $this->GetUserTypeName() == $FieldWorker )
		{
			$thisStaff = Staff::LoadStaff($this->UserID);
				
			return $thisStaff->LoadRegions();
			
		} elseif($this->GetUserTypeName() == $StateUser ) {
			
			$thisStaff = Staff::LoadStaff($this->UserID);
			
			return \Business\Region::load_regions_by_branches($this->UserID);
		
		} else {
			//Load all regions
			return \Business\Region::LoadRegions();
		}
	}
	
	public function IsMyRegion($RegionID)
	{
		$MyRegions = $this->UniversalLoadRegions();
		
		foreach($MyRegions as $Region)
		{
			if($RegionID == $Region->GetRegionID() )
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function LoadMyGroupsGroupNotes($show_deleted = false)
	{
		global $StateUser;
		global $FieldWorker;
		global $GroupUser;
		
		if( $this->GetUserTypeName() == $StateUser )
		{
			$Staff = Staff::LoadStaff($this->GetUserID());
			
			$Groups = \Business\Group::LoadGroupsAndNonGroupsByState($Staff->GetUserID());
			
		}
		elseif($this->GetUserTypeName() == $FieldWorker)
		{
			$Groups = \Business\Group::LoadGroupsAndNonGroupsByRegions($this->GetUserID());
			
		}
		elseif($this->GetUserTypeName() == $GroupUser)
		{
			$Groups = \Business\Group::LoadGroupsByRoles($this->GetUserID());
			
		}
		else {
			$Groups = \Business\Group::LoadAllGroups();
		}
		
		return \Business\Note::LoadNotesByGroupsArrayAndSecurityLevel($Groups,$this->GetSecurityLevel(),$show_deleted);
	}
	
	public function IsMyGroup($GroupID)
	{
		
		if( $this->GetUserTypeName() == STATE_USER )
		{
			$Staff = Staff::LoadStaff($this->GetUserID());
			
			$Groups = \Business\Group::LoadGroupsAndNonGroupsByState($Staff->GetUserID());
			
		}
		elseif($this->GetUserTypeName() == FIELD_WORKER )
		{
			$Groups = \Business\Group::LoadGroupsAndNonGroupsByRegions($this->GetUserID());
			
		}
		elseif($this->GetUserTypeName() == GROUP_USER )
		{
			$Groups = \Business\Group::LoadGroupsByRoles($this->GetUserID());
			
		}
		else {
			$Groups = \Business\Group::LoadAllGroups();
		}
		
		return \Business\Group::IsGroupInGroups($GroupID, $Groups );
	}
	
	public function LoadNavPages()
	{
		$NavPages = getUserTypeNavPages($this->UserTypeID)->fetchAll();
		
		//clear the array
		$this->NavPages = array();
		
		foreach( $NavPages as $NavPage)
		{
			$ThisPage = new \Pages\NavPage();
			$ThisPage->setPageID($NavPage['id_page']);
			$ThisPage->setPageName($NavPage['fld_page_name']);
			$ThisPage->setMenuCategoryName($NavPage['fld_menu_category_name']);
			$ThisPage->setSubMenuCategoryName($NavPage['fld_sub_menu_cat_name']);
			
			$this->NavPages[] = $ThisPage;
		}
		
		
	}
	
	//can't overload constructors in php so using function arguments
	public static function LoadUserUnSafe($UserID)
	{
		$UserID = intval($UserID);
		
		$pdoUser = getUserByIDNonSafe($UserID);
		
		if($pdoUser->rowCount() != 1)
		{
			return NULL;
		}
		
		$arrUser = $pdoUser->fetch();
		
		$thisUser = new User();
				
		$thisUser->SetUserID($arrUser['id_user']);
		$thisUser->SetUserTypeID($arrUser['fld_user_type_id']);
		$thisUser->SetUserName($arrUser['fld_username']);
		$thisUser->SetScreenName($arrUser['fld_screen_name']);
		$thisUser->SetEmailAddress($arrUser['fld_email_address']);
		$thisUser->SetDeleted($arrUser['fld_deleted']);
		
		return $thisUser;
		
		
	}
	
	public static function LoadUser($UserID,$LoadUserPages = true,$hide_aden = true)
	{
		$UserID = intval($UserID);
		
		$pdoUser = getUserByID($UserID,$hide_aden);
		
		if($pdoUser->rowCount() != 1)
		{
			return NULL;
		}
		
		$arrUser = $pdoUser->fetch();
		
		$thisUser = new User();
				
		$thisUser->SetUserID($arrUser['id_user']);
		$thisUser->SetUserTypeID($arrUser['fld_user_type_id']);
		$thisUser->SetUserName($arrUser['fld_username']);
		$thisUser->SetHashedPassword($arrUser['fld_password']);
		$thisUser->SetSalt($arrUser['fld_salt']);
		$thisUser->SetScreenName($arrUser['fld_screen_name']);
		$thisUser->SetEmailAddress($arrUser['fld_email_address']);
		$thisUser->SetDeleted($arrUser['fld_deleted']);
		$thisUser->SetUserTypeName($arrUser['fld_user_type']);
		$thisUser->SetUserTypeCategory($arrUser['fld_type_category']);
		$thisUser->SetDefaultPage($arrUser['fld_default_page']);
		
		//load the allowed pages for this user
		if($LoadUserPages)
		{
			$thisUser->LoadUserPages();
		}
		// returns object to be assigned
		
		return $thisUser;
		
		
	}
	
	public function AddReport($ReportType,$ReportName,$ReportDates)
	{
		 
		return \Business\UserReport::Create($ReportType,$ReportName,$ReportDates,$this->UserID);
	}
	
	public function AddLabels($LabelsType,$LabelsName,$LabelDates)
	{
		 
		return \Business\UserLabels::Create($LabelsType,$LabelsName,$LabelDates,$this->UserID);
	}
	
	public function LoadMyReports()
	{
		 return \Business\UserReport::LoadUserReportsByUserID($this->UserID);
	}
	
	public function LoadMyLabels()
	{
		 return \Business\UserLabels::LoadUserLabelsByUserID($this->UserID);
	}
	
	public function LoadMyGroupNotes()
	{
		return \Business\Group::LoadGroupNotesByUserID($this->UserID);
	}
	
	public function AddStaffLogin($UserName,$Password,$ScreenName,$UserTypeID)
	{
		global $strValChar;
		
		//Create Salt
		$Salt = funRandomString(32, $strValChar);
		//Create Hashed Password
		$HashedPassword = hash('sha256',"$Salt$Password");
		
		addStaffLogin($this->UserID,$UserName,$HashedPassword,$Salt,$ScreenName,$UserTypeID);
		
	}
	
	public static function CreateUser($UserName,$Password,$ScreenName,$Email,$UserTypeName)
	{
		global $strValChar;
		
		//Get User Type ID
		$UserType = getUserTypeByUserTypeName($UserTypeName)->fetch();
		//Create Salt
		$Salt = funRandomString(32, $strValChar);
		//Create Hashed Password
		$HashedPassword = hash('sha256',"$Salt$Password");
		
		$NewUserID = addUser($UserName,$HashedPassword,$Salt,$ScreenName,$Email,$UserType['id_user_type']);
		
		return User::LoadUser($NewUserID);
	}
	
	public static function CreateUserSafe($UserName,$Password,$ScreenName,$Email,$UserTypeID)
	{
		global $strValChar;
		
		$Salt = funRandomString(32, $strValChar);
		//Create Hashed Password
		$HashedPassword = hash('sha256',"$Salt$Password");
		
		$NewUserID = addUser($UserName,$HashedPassword,$Salt,$ScreenName,$Email,$UserTypeID);
		
		return User::LoadUser($NewUserID);
	}
	
	public function UpdateUserType($UserTypeID)
	{
		$arrUserType = getUserTypeByUserTypeID($UserTypeID)->fetch();
		$this->UserTypeID = $UserTypeID;
		$this->UserTypeName = $arrUserType['fld_user_type'];
		
		updUserType($this->UserID,$this->UserTypeID);
	}
	
	public function UpdateUser($ScreenName,$Email)
	{
		$this->ScreenName = $ScreenName;
		$this->EmailAddress = $Email;
		
		updUser($this->UserID,$this->ScreenName,$this->EmailAddress);
		
		
	}
	
	public function UpdateUserSafe($UserName,$Password,$ScreenName,$Email,$UserTypeID)
	{
				
		$Salt = $this->Salt;
		$HashedPassword = hash('sha256',"$Salt$Password");
		
		$this->UserName = $UserName;
		$this->HashedPassword = $HashedPassword;
		$this->ScreenName = $ScreenName;
		$this->EmailAddress = $EmailAddress;
		$this->UserTypeID = $UserTypeID;
		
		updUserSafe($this->UserID,$this->UserName,$this->HashedPassword,$this->ScreenName,$this->EmailAddress,$this->UserTypeID);
	}
	
	public function UpdateUserLogin($NewUserName,$NewPassword)
	{
		$this->UserName = $NewUserName;
		$Salt = $this->Salt;
		
		$this->HashedPassword = hash('sha256',"$Salt$NewPassword");
		
		
		updUserLogin($this->UserID,$this->UserName,$this->HashedPassword);
		
	}
	
	public function LoadUserPages()
	{
		//need to clear the array of existing values first
		$this->Pages = array();
		
		$Pages = getUserPages($this->UserID)->fetchAll();
		
		foreach($Pages as $Page)
		{
			$ThisPage = new \Pages\Page();
			
			$ThisPage->setPageID($Page['id_page']);
			$ThisPage->setPageName($Page['fld_page_name']);
			$ThisPage->setPageEditName($Page['fld_page_edit_name']);
			$ThisPage->setPageHelp($Page['fld_page_help']);
			
			$this->Pages[] = $ThisPage;
		}
		
	}
	
	public function PrintUserPages()
	{
		global $full_uri;
		
		echo '<ol>';
		foreach( $this->Pages as $Page)
		{
			echo '<li><a href="'.$full_uri.'?page_id='.$Page->getPageID().'">'.$Page->getPageName().'</a></li>';
		}
		echo '</ol>';
	}
	
	public function CheckUserPage($PageID)
	{
		foreach( $this->Pages as $Page)
		{
			if($Page->getPageID() == $PageID)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function CheckUserGroup($GroupID)
	{
		global $StateUser;
		global $FieldWorker;
		
		$Group = \Business\Group::LoadGroup($GroupID);
		
		if( $Group == NULL )
		{
			return false;
		}
		
		if( $this->GetUserTypeName() == $StateUser or $this->GetUserTypeName() == $FieldWorker)
		{
			$Staff = \Membership\Staff::LoadStaff($this->GetUserID());
			
			if(!$Staff->IsMyGroup($GroupID))
			{
				return false;
			}
		}
				
		//assume true
		return true;
	}
	
	public function HasLogin()
	{
		
		return ($this->UserName != '');
	}
	
	public static function UniversalMemberLoaderByDate($UserID,$Date) //Super Awesome method, just check using instanceof to get type
	{
		$UserID = intval($UserID);
		
		if( wasMember($UserID,$Date) )
		{
			return Member::LoadMember($UserID);
		}
		
		if( wasStaff($UserID,$Date) )
		{
			return Staff::LoadStaff($UserID);
		}
		
		//else
		return User::LoadUser($UserID,false,false);
	}
	
	public static function UniversalMemberLoader($UserID)  //only works for current user status
	{
		$UserID = intval($UserID);
		
		if( isMember($UserID) )
		{
			return Member::LoadMember($UserID);
		}
		
		if( isStaff($UserID) )
		{
			return Staff::LoadStaff($UserID);
		}
		
		//else
		return User::LoadUser($UserID,false,false);
		
	}
	
	public function HasMemberInterface()
	{
		 return hasMemberInterface($this->UserID);
	}
	
	public function HasStaffInterface()
	{
		 return hasStaffInterface($this->UserID);
	}
	
	public static function MergeUsers($to_keep,$to_destroy)
	{
		\Business\Attendance::TransferAttendance($to_destroy,$to_keep);
		deleteUser($to_destroy);
		///deleteActivityDates($to_destroy);
		TransferActivityDates($to_keep,$to_destroy);
	}
	
	public static function TransferActivityDates($to_keep,$to_destroy)
	{
		transferActivityDates($to_keep,$to_destroy);
	}
	
	public function GetUserLastGroupAttended()
	{
		return \Business\Group::LoadMemberLastGroupAttended($this->UserID);
		
	}
	
	public function Delete()
	{
		if($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR)
		{
			
			if($this->GetUserTypeCategory() != ADMIN)
			{
				return delUser($this->UserID);
			}
			
			//will need to add logic for deleting admins when that becomes necessary
			
		} else {
			return false;
		}
	}
	
} // End User Class

function delUser($UserID)
{
	global $dbh;
	
	try {
		
		$User = $dbh->prepare('	
								DELETE FROM tbl_users
								WHERE id_user = :UserID;
								');
		$User->execute(array(	
								':UserID' => $UserID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return true;
}

function transferActivityDates($to_keep,$to_destroy)
{
	
	global $dbh;
	
	try {
		
		$User = $dbh->prepare('	
								UPDATE tbl_user_activity_dates
								SET fld_user_id = :ToKeep
								WHERE fld_user_id = :ToDestroy
								');
		$User->execute(array(	
								':ToKeep' => $to_keep,
								':ToDestroy' => $to_destroy
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount();
}

function checkHasBranch($UserID,$BranchID)
{
	global $dbh;
	
	try {
		
		$User = $dbh->prepare('	
								SELECT *
								FROM tbl_state_users_state_activity_dates
								WHERE fld_user_id = :UserID
								AND fld_start_date <= DATE(NOW())
								AND (fld_end_date IS NULL 
								OR fld_end_date >= DATE(NOW()) )
								AND fld_branch_id = :BranchID
								');
		$User->execute(array(	
								':UserID' => $UserID,
								':BranchID' => $BranchID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

function check_has_region($UserID,$region_id,$show_deleted = false)
{
	global $dbh;
	
	$show_regions = ($show_deleted ? '' : 'AND tbl_regions.fld_deleted = 0');
	$show_states = ($show_deleted ? '' : 'AND tbl_state_users_state_activity_dates.fld_deleted = 0');
	
	try {
		
		$regions = $dbh->prepare('	
								SELECT *
								FROM tbl_state_users_state_activity_dates
								JOIN tbl_regions
								ON tbl_state_users_state_activity_dates.fld_branch_id = tbl_regions.fld_branch_id
								WHERE tbl_regions.id_region = :region_id
								AND tbl_state_users_state_activity_dates.fld_user_id = :UserID
								AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
								AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
									OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
									)
								
								'.$show_regions.'
								'.$show_states.'
								');
		$regions->execute(array(	
								':region_id' => $region_id,
								':UserID' => $UserID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $regions->rowCount() > 0;
}

function getUserSecurityLevel($UserID, $show_deleted = false)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND tbl_users.fld_deleted = 0');
	
	try {
		
		$User = $dbh->prepare('	
								SELECT tbl_user_types.fld_security_level as security_level
								FROM tbl_user_types
								JOIN tbl_users
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.id_user = :UserID
								'.$show.'
								');
		$User->execute(array(	
								':UserID' => $UserID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	if( $User->rowCount() == 0 )
	{
		return NULL; 
	}
	
	$arr_sec = $User->fetch();
	
	return $arr_sec['security_level'];
}

function deleteActivityDates($UserID)
{
	global $dbh;
	
	try {
		
		$User = $dbh->prepare('	
								DELETE FROM tbl_user_activity_dates
								WHERE fld_user_id = :UserID
								');
		$User->execute(array(	
								':UserID' => $UserID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User;
}

function deleteUser($UserID)
{
	global $dbh;
	
	try {
		
		$User = $dbh->prepare('	
								DELETE FROM tbl_users
								WHERE id_user = :UserID
								');
		$User->execute(array(	
								':UserID' => $UserID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

function hasMemberInterface($UserID)
{
	global $dbh;
	
	try {
		
		$User = $dbh->prepare('	
								SELECT *
								FROM tbl_members
								WHERE fld_user_id = :UserID;
								');
		$User->execute(array(	
								':UserID' => $UserID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

function hasAttendanceByGroupDate($UserID,$GroupID,$Date)
{
	global $dbh;
	
	try {
		
		$Attendance = $dbh->prepare('	
								SELECT *
								FROM tbl_group_attendance
								WHERE fld_user_id = :UserID
								AND fld_group_id = :GroupID
								AND fld_date = :Date
								AND fld_deleted = 0
								');
		$Attendance->execute(array(	
								':UserID' => $UserID,
								':GroupID' => $GroupID,
								':Date' => $Date
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Attendance->rowCount() != 0;
}

function hasStaffInterface($UserID)
{
	global $dbh;
	
	try {
		
		$User = $dbh->prepare('	
								SELECT *
								FROM tbl_staff
								WHERE fld_user_id = :UserID;
								');
		$User->execute(array(	
								':UserID' => $UserID
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

function isMember($UserID) //these methods only check if the user is currently a member
{
	global $dbh;
	global $MemberString;
	
	try {
		
		$User = $dbh->prepare('	
								SELECT *
								FROM tbl_members
								WHERE fld_user_id = :UserID
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											WHERE tbl_user_activity_dates.fld_user_id = tbl_members.fld_user_id
											AND fld_user_type_string = :Member
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
											)
								');
		$User->execute(array(	
								':UserID' => $UserID,
								':Member' => $MemberString
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

function wasMember($UserID,$Date) 
{
	global $dbh;
	global $MemberString;
	
	try {
		
		$User = $dbh->prepare('	
								SELECT *
								FROM tbl_members
								WHERE fld_user_id = :UserID
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											WHERE tbl_user_activity_dates.fld_user_id = tbl_members.fld_user_id
											AND fld_user_type_string = :Member
											AND (tbl_user_activity_dates.fld_start_date <= :Date
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= :Date2) 
													
												)
											)
								');
		$User->execute(array(	
								':UserID' => $UserID,
								':Member' => $MemberString,
								':Date' => $Date,
								':Date2' => $Date
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

function isStaff($UserID) //this only detects if is in staff table so volunteers appear here
{
	global $dbh;
	global $StaffVolunteer;
	
	try {
		
		$User = $dbh->prepare('	
								SELECT *
								FROM tbl_staff
								WHERE fld_user_id = :UserID
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
											)
								');
		$User->execute(array(
								':UserID' => $UserID,
								':StaffVolunteer' => $StaffVolunteer
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

function wasStaff($UserID,$Date) //this only detects if is in staff table so volunteers appear here
{
	global $dbh;
	global $StaffVolunteer;
	
	try {
		
		$User = $dbh->prepare('	
								SELECT *
								FROM tbl_staff
								WHERE fld_user_id = :UserID
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND (tbl_user_activity_dates.fld_start_date <= :Date
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= :Date2 ) 
													
												)
											)
								');
		$User->execute(array(
								':UserID' => $UserID,
								':StaffVolunteer' => $StaffVolunteer,
								':Date' => $Date,
								':Date2' => $Date
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User->rowCount() != 0;
}

class State {
	private $StateID;
	private $StateAbbreviation;
	private $StateName;
	
	function __construct() {
		//empty constructor that may need to be altered later
	}
	
	public function SetStateID($StateID)
	{
		$this->StateID = $StateID;
	}
	
	public function GetStateID()
	{
		return $this->StateID;
	}
	
	public function SetStateAbbreviation($StateAbbreviation)
	{
		$this->StateAbbreviation = $StateAbbreviation;
	}
	
	public function GetStateAbbreviation()
	{
		return $this->StateAbbreviation;
	}
	
	public function SetStateName($StateName)
	{
		$this->StateName = $StateName;
	}
	
	public function GetStateName()
	{
		return $this->StateName;
	}
	
	public static function ArrayItemToState($item)
	{
		$thisState = new State();
		
		// User Details
		$thisState->SetStateID($item['id_state']);
		$thisState->SetStateAbbreviation($item['fld_state_abbreviation']);
		$thisState->SetStateName($item['fld_state_name']);
		
		return $thisState;
	}
	
	public static function LoadState($StateID)
	{
		$StateID = intval($StateID);
		
		$pdoState = getStateByID($StateID);
		
		if($pdoState->rowCount() != 1)
		{
			return NULL;
		}
		
		return State::ArrayItemToState($pdoState->fetch());
		
	}
	
} // End State Class

function getStateByID($StateID)
{
	global $dbh;

	try {
			$State = $dbh->prepare('SELECT * 
									FROM tbl_states
									WHERE id_state = :StateID');
			$State->execute(array(':StateID' => $StateID ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $State;
}

class Staff extends User
{
	private $FirstName;
	private $Lastname;
	private $GenderID;
	private $BirthDate;
	private $Address;
	private $Suburb;
	private $PostCode;
	private $StateID; // State Object here
	private $BranchID;
	private $WorkEmail;
	private $PersonalEmail;
	private $WorkMobile;
	private $PersonalMobile;
	private $HomePhone;
	private $Notes;
	
	private $EmConFName;
	private $EmConLName;
	private $EmConAddress;
	private $EmConSuburb;
	private $EmConStateID;
	private $EmConPostCode;
	private $EmConMobile;
	private $EmConHomePhone;
	
	private $Deleted;
	
	public function SetFirstName($FirstName)
	{
		$this->FirstName = $FirstName;
	}
	
	public function GetFirstName()
	{
		return $this->FirstName;
	}
	
	public function SetLastname($Lastname)
	{
		$this->Lastname = $Lastname;
	}
	
	public function GetLastname()
	{
		return $this->Lastname;
	}
	
	public function SetGenderID($GenderID)
	{
		$this->GenderID = $GenderID;
	}
	
	public function GetGenderID()
	{
		return $this->GenderID;
	}
	
	public function SetBirthDate($BirthDate)
	{
		$this->BirthDate = $BirthDate;
	}
	
	public function GetBirthDate()
	{
		return $this->BirthDate;
	}
	
	public function SetAddress($Address)
	{
		$this->Address = $Address;
	}
	
	public function GetAddress()
	{
		return $this->Address;
	}
	
	public function SetSuburb($Suburb)
	{
		$this->Suburb = $Suburb;
	}
	
	public function GetSuburb()
	{
		return $this->Suburb;
	}
	
	public function SetPostCode($PostCode)
	{
		$this->PostCode = $PostCode;
	}
	
	public function GetPostCode()
	{
		return $this->PostCode;
	}
	
	public function SetStateID($StateID)
	{
		$this->StateID = $StateID;
	}
	
	public function GetStateID()
	{
		return $this->StateID;
	}
	
	public function GetState()
	{
		return State::LoadState($this->StateID);
	}
	
	public function GetBranch()
	{
		return \Business\Branch::LoadBranch($this->BranchID);
	}
	
	public function SetBranchID($BranchID)
	{
		$this->BranchID = $BranchID;
	}
	
	public function GetBranchID()
	{
		return $this->BranchID;
	}
	
	public function GetLastPoliceCheck()
	{
		return ReminderDate::LoadLastPoliceCheck($this->UserID);
	}
	
	public function SetWorkEmail($WorkEmail)
	{
		$this->WorkEmail = $WorkEmail;
	}
	
	public function GetWorkEmail()
	{
		return $this->WorkEmail;
	}
	
	public function SetPersonalEmail($PersonalEmail)
	{
		$this->PersonalEmail = $PersonalEmail;
	}
	
	public function GetPersonalEmail()
	{
		return $this->PersonalEmail;
	}
	
	public function SetWorkMobile($WorkMobile)
	{
		$this->WorkMobile = $WorkMobile;
	}
	
	public function GetWorkMobile()
	{
		return $this->WorkMobile;
	}
	
	public function SetPersonalMobile($PersonalMobile)
	{
		$this->PersonalMobile = $PersonalMobile;
	}
	
	public function GetPersonalMobile()
	{
		return $this->PersonalMobile;
	}
	
	public function SetHomePhone($HomePhone)
	{
		$this->HomePhone = $HomePhone;
	}
	
	public function GetHomePhone()
	{
		return $this->HomePhone;
	}
	
	public function SetNotes($Notes)
	{
		$this->Notes = $Notes;
	}
	
	public function GetNotes()
	{
		return $this->Notes;
	}
	
	public function SetEmConFName($EmConFName)
	{
		$this->EmConFName = $EmConFName;
	}
	
	public function GetEmConFName()
	{
		return $this->EmConFName;
	}
	
	public function SetEmConLName($EmConLName)
	{
		$this->EmConLName = $EmConLName;
	}
	
	public function GetEmConLName()
	{
		return $this->EmConLName;
	}
	
	public function SetEmConAddress($EmConAddress)
	{
		$this->EmConAddress = $EmConAddress;
	}
	
	public function GetEmConAddress()
	{
		return $this->EmConAddress;
	}
	
	public function SetEmConSuburb($EmConSuburb)
	{
		$this->EmConSuburb = $EmConSuburb;
	}
	
	public function GetEmConSuburb()
	{
		return $this->EmConSuburb;
	}
	
	public function SetEmConStateID($EmConStateID)
	{
		$this->EmConStateID = $EmConStateID;
	}
	
	public function GetEmConStateID()
	{
		return $this->EmConStateID;
	}
	
	public function SetEmConPostCode($EmConPostCode)
	{
		$this->EmConPostCode = $EmConPostCode;
	}
	
	public function GetEmConPostCode()
	{
		return $this->EmConPostCode;
	}
	
	public function SetEmConMobile($EmConMobile)
	{
		$this->EmConMobile = $EmConMobile;
	}
	
	public function GetEmConMobile()
	{
		return $this->EmConMobile;
	}
	
	public function SetEmConHomePhone($EmConHomePhone)
	{
		$this->EmConHomePhone = $EmConHomePhone;
	}
	
	public function GetEmConHomePhone()
	{
		return $this->EmConHomePhone;
	}
	
	public function SetDeleted($Deleted)
	{
		$this->Deleted = $Deleted;
	}
	
	public function GetDeleted()
	{
		return $this->Deleted;
	}
	
	public function HasBranch($BranchID)
	{
		return checkHasBranch($this->GetUserID(),$BranchID);
	}
	
	public function GetCurrentActivity()
	{
		global $StaffVolunteer;
		
		return UserActivityDates::LoadCurrentUserActivity($this->UserID,$StaffVolunteer);
	}
	
	public function GetLastUserActivity()
	{
		global $StaffVolunteer;
		
		return UserActivityDates::LoadLastUserActivity($this->UserID,$StaffVolunteer);
	}
	
	public function IsVolunteer()
	{
		return isVolunteer($this->UserID);
		
		//$RoleID = $this->GetCurrentActivity()->GetStaffRole()->GetStaffRoleID();
		//return getIsVolunteerJobClass($RoleID)->rowCount() > 0;
	}
	
	public function GetActivityDates()
	{
		global $StaffVolunteer;
		
		return UserActivityDates::LoadUserActivityDates($this->UserID,$StaffVolunteer);
	}
	
	public function GetGenderName()
	{
		$pdoGender = getGender($this->GenderID);
		
		if($pdoGender->rowCount() != 1)
		{
			return 'bad gender';
		} else {
			$arrGender = $pdoGender->fetch();
			return $arrGender['fld_gender'];
		}
		
	}
	
	public static function LoadVolunteerByGroupAttendance($VolRole,$GroupID,$StartDate,$EndDate)
	{
		$arrVol = getVolunteerByGroupAttendance($VolRole,$GroupID,$StartDate,$EndDate)->fetchAll();
		
		$Volunteers = array();
		
		foreach( $arrVol as $thisVol )
		{		
			$Volunteers[] = Staff::ArrayItemToStaff($thisVol);
		}
		
		return $Volunteers;
	}
		
	public function CreatePoliceCheck($Date)
	{
		
		return ReminderDate::CreatePoliceCheckRecord($this->UserID,$Date);
	}
	
	public function LoadPoliceCheckDates()
	{
		return ReminderDate::LoadPoliceCheckRecordsByUserID($this->UserID);
	}
	
	public function CreateUserActivity($StartDate,$EndDate,$JobClassification)
	{
		global $StaffVolunteer;
		
		UserActivityDates::CreateUserActivity($this->UserID,$StaffVolunteer,$StartDate,$EndDate,$JobClassification);
	}
	
	public static function LoadGroupLeadersByDates($GroupRole,$GroupID,$StartDate,$EndDate)
	{
			
		$arrStaff = getGroupLeadersByRoleGroupAndDates($GroupRole,$GroupID,$StartDate,$EndDate)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
	}
	
	function LoadRegions() //Only works for Staff with regional records - Currently only field workers have region Records
	{
		return \Business\StaffRegion::LoadStaffsRegions($this->UserID);
	}
	
	function __construct() {
       parent::__construct();
       //yet another empty constructor
   }
   
   public static function CreateStaff($FirstName,$Lastname,$Gender,$BirthDate,$Address,$Suburb,$PostCode,
				$StateID,$BranchID,$WorkEmail,$PersonalEmail,$WorkMobile,$PersonalMobile,
				$HomePhone,$Notes,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConStateID,$EmConPostCode,
				$EmConMobile,$EmConHomePhone)
	{
		$NewUserID = addEmptyUser();
					
		addStaff(
				$NewUserID,$FirstName,$Lastname,$Gender,$BirthDate,$Address,$Suburb,$PostCode,
				$StateID,$BranchID,$WorkEmail,$PersonalEmail,$WorkMobile,$PersonalMobile,
				$HomePhone,$Notes,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConStateID,$EmConPostCode,
				$EmConMobile,$EmConHomePhone
				);
		
		return Staff::LoadStaff($NewUserID);
	}
	
	public static function CreateStaffFromUser($UserID,$FirstName,$Lastname,$Gender,$BirthDate,$Address,$Suburb,$PostCode,
				$StateID,$BranchID,$WorkEmail,$PersonalEmail,$WorkMobile,$PersonalMobile,
				$HomePhone,$Notes,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConStateID,$EmConPostCode,
				$EmConMobile,$EmConHomePhone)
	{
		addStaff(
				$UserID,$FirstName,$Lastname,$Gender,$BirthDate,$Address,$Suburb,$PostCode,
				$StateID,$BranchID,$WorkEmail,$PersonalEmail,$WorkMobile,$PersonalMobile,
				$HomePhone,$Notes,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConStateID,$EmConPostCode,
				$EmConMobile,$EmConHomePhone
				);
				
		return Staff::LoadStaff($UserID);
	}
   
   public static function LoadStaff($UserID)
	{
		$UserID = intval($UserID);
		
		// Insert code to retrieve staff member here
		$pdoStaff = getStaffByID($UserID);
		
		if($pdoStaff->rowCount() == 0)
		{
			return false;
		} else {
			$arrStaff = $pdoStaff->fetch();
			return Staff::ArrayItemToStaff($arrStaff);
		}
		
	}
	
	public static function LoadVolunteersBySearchStringOptimised($SearchString,$record_limit,$extra)
	{
		$SearchString = trim($SearchString);
		
		$arrVolunteers = getVolunteersBySearchStringOptimised($SearchString,$record_limit,$extra)->fetchAll();
		
		$Volunteers = array();
		
		foreach( $arrVolunteers as $Volunteer )
		{		
			$Volunteers[] = Staff::ArrayItemToStaff($Volunteer);
		}
		
		return $Volunteers;
		
		
	}
	
	public static function LoadStaffBySearchStringOptimised($str_text,$record_limit,$extra)
	{
		// Insert code to retrieve staff member here
		$arrStaff = getStaffBySearchStringOptimised($str_text,$record_limit,$extra)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
	}
	
	public static function LoadStaffVolDuePoliceChecks($months_till_due,$user_type_cat)
   	{
	   	$months_till_due = intval($months_till_due);
		
		$arrStaff = getStaffVolDuePoliceChecks($months_till_due,$user_type_cat)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
  	}
   
   	public static function LoadVolunteerDuePoliceChecks($months_till_due)
   	{
	   	$months_till_due = intval($months_till_due);
		
		$arrStaff = getVolunteerDuePoliceChecks($months_till_due)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
  	}
   
	public static function LoadStaffByRegionAttendanceBetweenDates($GroupRole,$RegionID,$StartDate,$EndDate)
	{
		$RegionID = intval($RegionID);
		
		$arrStaff = getStaffByRegionAttendanceBetweenDates($GroupRole,$RegionID,$StartDate,$EndDate)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
	}
	
	public static function LoadStaffByGroupAttendanceBetweenDates($GroupRole,$GroupID,$StartDate,$EndDate)
	{
		$GroupID = intval($GroupID);
		
		$arrStaff = getStaffByGroupAttendanceBetweenDates($GroupRole,$GroupID,$StartDate,$EndDate)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
	}
	
	public static function LoadStaffBySearchStringForAttendance($SearchString,$GroupID,$GroupDate)
	{
		$SearchString = trim($SearchString);
		
		$arrStaff = getStaffBySearchStringForAttendance($SearchString,$GroupID,$GroupDate)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
		
	}
	
	public static function LoadStaffBySearchString($SearchString)
	{
		$SearchString = trim($SearchString);
		
		$arrStaff = getStaffBySearchString($SearchString)->fetchAll();
		
		$Staff = array();
		
		foreach( $arrStaff as $thisStaff )
		{		
			$Staff[] = Staff::ArrayItemToStaff($thisStaff);
		}
		
		return $Staff;
		
	}
	
	public static function LoadVolunteersBySearchString($SearchString)
	{
		$SearchString = trim($SearchString);
		
		$arrVolunteers = getVolunteersBySearchString($SearchString)->fetchAll();
		
		$Volunteers = array();
		
		foreach( $arrVolunteers as $Volunteer )
		{		
			$Volunteers[] = Staff::ArrayItemToStaff($Volunteer);
		}
		
		return $Volunteers;
		
		
	}
	
	public static function ArrayItemToStaff($item)
	{
		$thisStaff = new Staff();
		
		// User Details
		$thisStaff->SetUserID($item['id_user']);
		$thisStaff->SetUserTypeID($item['fld_user_type_id']);
		$thisStaff->SetUserName($item['fld_username']);
		$thisStaff->SetHashedPassword($item['fld_password']);
		$thisStaff->SetSalt($item['fld_salt']);
		$thisStaff->SetScreenName($item['fld_screen_name']);
		$thisStaff->SetEmailAddress($item['fld_email_address']);
		$thisStaff->SetDeleted($item['fld_deleted']);
		$thisStaff->SetUserTypeName($item['fld_user_type']);
		$thisStaff->SetUserTypeCategory($item['fld_type_category']);
		$thisStaff->SetDefaultPage($item['fld_default_page']);
		// Staff Details
		$thisStaff->SetFirstName($item['fld_first_name']);
		$thisStaff->SetLastname($item['fld_last_name']);
		$thisStaff->SetGenderID($item['fld_gender']);
		$thisStaff->SetBirthDate($item['fld_birth_date']);
		$thisStaff->SetAddress($item['fld_address']);
		$thisStaff->SetSuburb($item['fld_suburb']);
		$thisStaff->SetPostCode($item['fld_postcode']);
		$thisStaff->SetStateID($item['fld_state_id']);
		$thisStaff->SetBranchID($item['fld_branch_id']);
		$thisStaff->SetWorkEmail($item['fld_work_email']);
		$thisStaff->SetPersonalEmail($item['fld_personal_email']);
		$thisStaff->SetWorkMobile($item['fld_work_mobile']);
		$thisStaff->SetPersonalMobile($item['fld_personal_mobile']);
		$thisStaff->SetHomePhone($item['fld_home_phone']);
		$thisStaff->SetNotes($item['fld_notes']);
		
		$thisStaff->SetEmConFName($item['fld_em_con_first_name']);
		$thisStaff->SetEmConLName($item['fld_em_con_last_name']);
		$thisStaff->SetEmConAddress($item['fld_em_con_address']);
		$thisStaff->SetEmConSuburb($item['fld_em_con_suburb']);
		$thisStaff->SetEmConPostCode($item['fld_em_con_postcode']);
		$thisStaff->SetEmConStateID($item['fld_em_con_state_id']);
		$thisStaff->SetEmConMobile($item['fld_em_con_mobile']);
		$thisStaff->SetEmConHomePhone($item['fld_em_con_home_phone']);
		
		return $thisStaff;
	}
	
	public static function LoadActiveStaff()
	{
		$arrActiveStaff = getActiveStaff()->fetchAll();
		
		$ActiveStaff = array();
		
		foreach( $arrActiveStaff as $Staff )
		{		
			$ActiveStaff[] = Staff::ArrayItemToStaff($Staff);
		}
		
		return $ActiveStaff;
	}
	
	public static function LoadArchivedStaff()
	{
		$arrStaff = getArchivedStaff()->fetchAll();
		
		$ArchivedStaff = array();
		
		foreach( $arrStaff as $Staff )
		{		
			$ArchivedStaff[] = Staff::ArrayItemToStaff($Staff);
		}
		
		return $ArchivedStaff;
	}
	
	public function UpdateStaff($FirstName,$LastName,$Gender,$SafeDOB,$Address,$Suburb,$PostCode,
				$State,$Branch,$WorkEmail,$Email,$WorkPhone,$Mobile,
				$HomePhone,$OtherEmploymentDetails,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConState,$EmConPostCode,
				$EmConMobile,$EmConHomePhone)
	{
		
		// Staff Details
		$this->SetFirstName($FirstName);
		$this->SetLastname($LastName);
		$this->SetBirthDate($SafeDOB);
		$this->SetAddress($Address);
		$this->SetSuburb($Suburb);
		$this->SetPostCode($PostCode);
		$this->SetStateID($State);
		$this->SetBranchID($Branch);
		$this->SetWorkEmail($WorkEmail);
		$this->SetPersonalEmail($Email);
		$this->SetWorkMobile($WorkPhone);
		$this->SetPersonalMobile($Mobile);
		$this->SetHomePhone($HomePhone);
		$this->SetNotes($OtherEmploymentDetails);
		
		$this->SetEmConFName($EmConFName);
		$this->SetEmConLName($EmConLName);
		$this->SetEmConAddress($EmConAddress);
		$this->SetEmConSuburb($EmConSuburb);
		$this->SetEmConPostCode($EmConPostCode);
		$this->SetEmConStateID($EmConState);
		$this->SetEmConMobile($EmConMobile);
		$this->SetEmConHomePhone($EmConHomePhone);
		
		updStaff($this->UserID,$FirstName,$LastName,$Gender,$SafeDOB,$Address,$Suburb,$PostCode,
				$State,$Branch,$WorkEmail,$Email,$WorkPhone,$Mobile,
				$HomePhone,$OtherEmploymentDetails,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConState,$EmConPostCode,
				$EmConMobile,$EmConHomePhone);
		
	}
	
	public function has_branch_by_branch_id($branch_id)
	{
		return get_has_branch_by_user_id_and_branch($this->UserID,$branch_id);
	}
	
	public static function load_volunteers_by_branches($staff_id)
	{
		$arr_volunteers = get_active_volunteers_by_staffs_branches($staff_id)->fetchAll();
		
		$volunteers = array();
					
		foreach( $arr_volunteers As $volunteer )
		{
			$volunteers[] = Staff::ArrayItemToStaff($volunteer);
		}
		
		return $volunteers;
	}
	
	public static function LoadVolunteersByBranch($BranchID)
	{
		$arrVolunteers = getActiveVolunteersByBranch($BranchID)->fetchAll();
		
		$thisVolunteers = array();
					
		foreach( $arrVolunteers As $Volunteer )
		{
			$thisVolunteers[] = Staff::ArrayItemToStaff($Volunteer);
		}
		
		return $thisVolunteers;
	}
	
	public static function load_archived_volunteers_by_branches($staff_id)
	{
		$arr_volunteers = get_archived_volunteers_by_branches($staff_id)->fetchAll();
		
		$volunteers = array();
					
		foreach( $arr_volunteers As $volunteer )
		{
			$volunteers[] = Staff::ArrayItemToStaff($volunteer);
		}
		
		return $volunteers;
		
	}
	
	public static function LoadArchivedVolunteers()
	{
		$arrVolunteers = getArchivedVolunteers()->fetchAll();
		
		$thisVolunteers = array();
					
		foreach( $arrVolunteers As $Volunteer )
		{
			$thisVolunteers[] = Staff::ArrayItemToStaff($Volunteer);
		}
		
		return $thisVolunteers;
	}
	
	public static function LoadActiveVolunteers()
	{
		$arrVolunteers = getActiveVolunteers()->fetchAll();
		
		$thisVolunteers = array();
					
		foreach( $arrVolunteers As $Volunteer )
		{
			$thisVolunteers[] = Staff::ArrayItemToStaff($Volunteer);
		}
		
		return $thisVolunteers;
	}
	
	
	
	public static function LoadArchivedVolunteersByBranch($BranchID)
	{
		$arrVolunteers = getArchivedVolunteersByBranch($BranchID)->fetchAll();
		
		$thisVolunteers = array();
					
		foreach( $arrVolunteers As $Volunteer )
		{
			$thisVolunteers[] = Staff::ArrayItemToStaff($Volunteer);
		}
		
		return $thisVolunteers;
	}
	
	//this function is dependent upon being called by Member Merge
	public static function MergeStaff($to_destroy,$to_keep,$staff_to_keep)
	{
		transferStaffsRegions($to_destroy,$to_keep);
		
		transferGroupsRoles($to_destroy,$to_keep);
		
		if( $staff_to_keep == $to_keep )
		{
			deleteStaff($to_destroy);
			
		} else {
			deleteStaff($to_keep);
			transferStaffID($to_keep,$to_destroy);
		}
		
		
	}
	
	public static function CountVolunteerAttendancesByBranch($Role,$BranchID,$StartDate,$EndDate)
	{
		$GroupMembers = getCountVolunteerAttendanceInPeriodByRoleBranch($Role,$BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $GroupMembers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['volunteer_attendances']);
				
				$i++;
			}
			
			return $index_array;
	}
	
	public static function CountVolunteersByBranchAttendance($Role,$BranchID,$StartDate,$EndDate)
	{
		$GroupMembers = getCountVolunteersInPeriodByRoleBranch($Role,$BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $GroupMembers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['volunteers']);
				
				$i++;
			}
			
			return $index_array;
	}
	
	public static function CountVolunteerAttendancesByRegionAttendance($Role,$RegionID,$StartDate,$EndDate)
	{
		$Attendances = getCountVolunteerAttendancesInPeriodByRoleRegion($Role,$RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Attendances as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['volunteer_attendances']);
				
				$i++;
			}
			
			return $index_array;
	}
	
	public static function CountVolunteersByRegionAttendance($Role,$RegionID,$StartDate,$EndDate)
	{
		$GroupMembers = getCountVolunteersInPeriodByRoleRegion($Role,$RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $GroupMembers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['volunteers']);
				
				$i++;
			}
			
			return $index_array;
	}
	
} // End Staff Class

function get_has_branch_by_user_id_and_branch($user_id,$branch_id)
{
	global $dbh;
	
	try {
		
		$has_branch = $dbh->prepare('	
									SELECT *
									FROM tbl_state_users_state_activity_dates
									WHERE fld_deleted = 0
									AND fld_user_id = :user_id
									AND fld_branch_id = :branch_id
									AND fld_start_date <= DATE(NOW())
									AND ( fld_end_date IS NULL
										OR fld_end_date >= DATE(NOW())
										)
								');
		$has_branch->execute(array(	
								':user_id' => $user_id,
								':branch_id' => $branch_id
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $has_branch->rowCount() > 0;
}

function transferStaffID($giver,$receiver)
{
	global $dbh;
	
	try {
		
		$Staff = $dbh->prepare('	
								UPDATE tbl_staff
								SET fld_user_id = :Giver
								WHERE fld_user_id = :Receiver
								');
		$Staff->execute(array(	':Receiver' => $receiver,
								':Giver' => $giver,
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function deleteStaff($UserID)
{
	global $dbh;
	
	try {
		
		$Staff = $dbh->prepare('	
								DELETE FROM tbl_staff
								WHERE fld_user_id = :UserID
									
								
								');
		$Staff->execute(array(	':UserID' => $UserID
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function transferGroupsRoles($giver,$receiver)
{
	global $dbh;
	
	try {
		
		$GroupsRoles = $dbh->prepare('	
								UPDATE tbl_groups_roles
								SET fld_user_id = :Receiver
								WHERE fld_user_id = :Giver
									
								
								');
		$GroupsRoles->execute(array(	':Receiver' => $receiver,
										':Giver' => $giver
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $GroupsRoles;
}


function transferStaffsRegions($giver,$receiver)
{
	global $dbh;
	
	try {
		
		$StaffsRegions = $dbh->prepare('	
								UPDATE tbl_staffs_regions
								SET fld_user_id = :Receiver
								WHERE fld_user_id = :Giver
									
								
								');
		$StaffsRegions->execute(array(	':Receiver' => $receiver,
										':Giver' => $giver
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $StaffsRegions;
}

function getGroupLeadersByRoleGroupAndDates($GroupRole,$GroupID,$StartDate,$EndDate)
{
	global $dbh;
	
	try {
		
		$Staff = $dbh->prepare('	
								SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
											SELECT * 
											FROM tbl_groups_roles
											JOIN tbl_group_roles
											ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
											JOIN tbl_groups
											ON tbl_groups_roles.fld_group_id = tbl_groups.id_group
											WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
											AND tbl_groups.id_group = :GroupID
											AND tbl_group_roles.fld_group_role = :GroupRole
											AND tbl_groups_roles.fld_start_date <= :EndDate
											AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate )
											AND tbl_groups_roles.fld_deleted = 0
											)
									
								
								');
		$Staff->execute(array(	':GroupID' => $GroupID,
								':GroupRole' => $GroupRole,
								':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								':StartDate' => ($StartDate == 'null') ? null : $StartDate
								
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function getStaffByRegionAttendanceBetweenDates($GroupRole,$RegionID,$StartDate,$EndDate)
{
	global $dbh;
	
	try {
		
		$Staff = $dbh->prepare('	
								SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												JOIN tbl_groups
												ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
												WHERE tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
												AND tbl_group_attendance.fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
												AND EXISTS(
													SELECT *
													FROM tbl_groups_regions
													WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
													AND tbl_groups_regions.fld_region_id = :RegionID
													AND (tbl_groups_regions.fld_start_date <= :EndDate2
														AND (tbl_groups_regions.fld_end_date IS NULL
															OR tbl_groups_regions.fld_end_date >= :StartDate2)
														)
												)
											)
								AND EXISTS(
											SELECT * 
											FROM tbl_groups_roles
											JOIN tbl_group_roles
											ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
											JOIN tbl_groups
											ON tbl_groups_roles.fld_group_id = tbl_groups.id_group
											WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
											AND tbl_group_roles.fld_group_role = :GroupRole
											AND tbl_groups_roles.fld_start_date <= :EndDate3
											AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate3 )
											AND tbl_groups_roles.fld_deleted = 0
											AND EXISTS(
													SELECT *
													FROM tbl_groups_regions
													WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
													AND tbl_groups_regions.fld_region_id = :RegionID2
													AND (tbl_groups_regions.fld_start_date <= :EndDate4
														AND (tbl_groups_regions.fld_end_date IS NULL
															OR tbl_groups_regions.fld_end_date >= :StartDate4)
														)
												)
											)
								
								');
		$Staff->execute(array(	':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								':RegionID' => $RegionID,
								':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
								':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
								':GroupRole' => $GroupRole,
								':EndDate3' => ($EndDate == 'null') ? null : $EndDate,
								':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
								':RegionID2' => $RegionID,
								':EndDate4' => ($EndDate == 'null') ? null : $EndDate,
								':StartDate4' => ($StartDate == 'null') ? null : $StartDate
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function getVolunteerByGroupAttendance($VolRole,$GroupID,$StartDate,$EndDate)
{
	
	global $dbh;
	
	try {
		
		$Staff = $dbh->prepare('
		
								SELECT DISTINCT tbl_staff.fld_user_id, tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
										FROM tbl_staff
										JOIN tbl_users
										ON tbl_users.id_user = tbl_staff.fld_user_id
										LEFT OUTER JOIN tbl_user_types
										ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
										JOIN tbl_groups_roles
										ON tbl_staff.fld_user_id = tbl_groups_roles.fld_user_id
										JOIN tbl_group_roles
										ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
										JOIN tbl_group_attendance
										ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
										WHERE tbl_groups_roles.fld_start_date <= :EndDate
										AND ( 
														tbl_groups_roles.fld_end_date IS NULL OR
														tbl_groups_roles.fld_end_date >= :StartDate
														)
										AND tbl_group_roles.fld_group_role = :GroupRole
										AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
										AND tbl_groups_roles.fld_deleted = 0
										AND tbl_group_attendance.fld_group_id = :GroupID
										AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
										AND tbl_group_attendance.fld_date >= tbl_groups_roles.fld_start_date 
										AND (tbl_groups_roles.fld_end_date IS NULL OR 
											tbl_group_attendance.fld_date <= tbl_groups_roles.fld_end_date);
									
								');
		$Staff->execute(array(	':EndDate' => $EndDate,
								':StartDate' => $StartDate,
								':GroupRole' => $VolRole,
								':GroupID' => $GroupID,
								':StartDate2' => $StartDate,
								':EndDate2' => $EndDate
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}


function getStaffByGroupAttendanceBetweenDates($GroupRole,$GroupID,$StartDate,$EndDate)
{
	global $dbh;
	
	try {
		
		$Staff = $dbh->prepare('	
								SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
												AND fld_group_id = :GroupID
												AND fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
											)
								AND EXISTS(
											SELECT * 
											FROM tbl_groups_roles
											JOIN tbl_group_roles
											ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
											WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
											AND tbl_groups_roles.fld_group_id = :GroupID2
											AND tbl_group_roles.fld_group_role = :GroupRole
											AND tbl_groups_roles.fld_start_date <= :EndDate2
											AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate2 )
											AND tbl_groups_roles.fld_deleted = 0
											)
								');
		$Staff->execute(array(	':GroupID' => $GroupID,
								':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								':GroupID2' => $GroupID,
								':GroupRole' => $GroupRole,
								':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
								':StartDate2' => ($StartDate == 'null') ? null : $StartDate
								 ));

	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function getIsVolunteerJobClass($JobClassID)
{
	global $dbh;
	
	try {
		$JobClass = $dbh->prepare("SELECT tbl_staff_roles.*
								FROM tbl_staff_roles
								WHERE id_staff_role = :JobClassID
								AND fld_staff_vol = 'volunteer'
								");
		$JobClass->execute(array(':JobClassID' => $JobClassID ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $JobClass;
} 

function getActiveVolunteers()
{
	global $dbh;
	global $StaffVolunteer;
	//no need to check user type due to need for explicit connection between users and staff
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								JOIN tbl_branches
								ON tbl_staff.fld_branch_id = tbl_branches.id_branch
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
												)
											)
								
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(	':StaffVolunteer' => $StaffVolunteer ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function get_active_volunteers_by_staffs_branches($staff_id)
{
	global $dbh;
	global $StaffVolunteer;
	
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW())
												)
											)
								AND tbl_staff.fld_branch_id IN(
																SELECT fld_branch_id
																FROM tbl_state_users_state_activity_dates
																WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																	OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																	)
																AND fld_user_id = :staff_id
																)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(	
								':StaffVolunteer' => $StaffVolunteer,
								':staff_id' => $staff_id
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}


function get_archived_volunteers_by_branches($staff_id)
{
	global $dbh;
	global $StaffVolunteer;
	
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
											)
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer2
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'staff'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								AND tbl_staff.fld_branch_id IN(
																SELECT fld_branch_id
																FROM tbl_state_users_state_activity_dates
																WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																AND fld_user_id = :staff_id
																)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(	
								':StaffVolunteer' => $StaffVolunteer,
								':StaffVolunteer2' => $StaffVolunteer,
								':staff_id' => $staff_id
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function getActiveVolunteersByBranch($BranchID)
{
	global $dbh;
	global $StaffVolunteer;
	//no need to check user type due to need for explicit connection between users and staff
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								JOIN tbl_branches
								ON tbl_staff.fld_branch_id = tbl_branches.id_branch
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND tbl_branches.fld_branch_abbreviation = :BranchID
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW())
												)
											)
								
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(	':BranchID' => $BranchID,
								':StaffVolunteer' => $StaffVolunteer ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function isVolunteer($UserID)
{
	//ensure person was never a staff member
	global $dbh;
	global $StaffVolunteer;
	//Is volunteer is true if the person was ever a volunteer
	try {
		$Staff = $dbh->prepare("SELECT COUNT(*) AS Total
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								WHERE tbl_users.id_user = :UserID
								AND tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'staff'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(	
								':UserID' => $UserID,
								':StaffVolunteer' => $StaffVolunteer ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arrTotal = $Staff->fetch();
	
	return $arrTotal['Total'] > 0;
}

function getArchivedVolunteers()
{
	//ensure person was never a staff member
	global $dbh;
	global $StaffVolunteer;
	//no need to check user type due to need for explicit connection between users and staff
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								JOIN tbl_branches
								ON tbl_staff.fld_branch_id = tbl_branches.id_branch
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
											)
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates

											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer2
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'staff'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(	
								':StaffVolunteer' => $StaffVolunteer,
								':StaffVolunteer2' => $StaffVolunteer ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;

}



function getArchivedVolunteersByBranch($BranchID)
{
	//ensure person was never a staff member
	global $dbh;
	global $StaffVolunteer;
	//no need to check user type due to need for explicit connection between users and staff
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								JOIN tbl_branches
								ON tbl_staff.fld_branch_id = tbl_branches.id_branch
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND tbl_branches.fld_branch_abbreviation = :BranchID
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
											)
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer2
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'staff'
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												
											)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(	':BranchID' => $BranchID,
								':StaffVolunteer' => $StaffVolunteer,
								':StaffVolunteer2' => $StaffVolunteer ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;

}

function getGender($GenderID)
{
	global $dbh;

	try {
			$Gender = $dbh->prepare('SELECT * 
									FROM tbl_genders
									WHERE id_gender = :GenderID');
			$Gender->execute(array(':GenderID' => $GenderID ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $Gender;
}

class ReminderDate
{
	private $ReminderID;
	private $Date;
	private $UserID;
	private $TypeID;
	private $Deleted;
	
	public function SetReminderID($ReminderID)
	{
		$this->ReminderID = $ReminderID;
	}
	
	public function GetReminderID()
	{
		return $this->ReminderID;
	}
	
	public function SetDate($Date)
	{
		$this->Date = $Date;
	}
	
	public function GetDate()
	{
		return $this->Date;
	}
	
	public function SetUserID($UserID)
	{
		$this->UserID = $UserID;
	}
	
	public function GetUserID()
	{
		return $this->UserID;
	}
	
	public function SetTypeID($TypeID)
	{
		$this->TypeID = $TypeID;
	}
	
	public function GetTypeID()
	{
		return $this->TypeID;
	}
	
	public function SetDeleted($Deleted)
	{
		$this->Deleted = $Deleted;
	}
	
	public function GetDeleted()
	{
		return $this->Deleted;
	}
	
	
	function __construct() {
	      //yet another empty constructor
   	}
   	
	public static function LoadLastPoliceCheck($UserID,$show_deleted = false)
	{
		global $PoliceCheck;
		
		$UserID = intval($UserID);
		
		// Insert code to retrieve staff member here
		$arrReminder = getLastReminderByUserAndType($UserID,$PoliceCheck,$show_deleted)->fetch();
		
		if($arrReminder['last_police_check'] == '')
		{
			return NULL;
		} else {
			return ReminderDate::ArrayItemToReminder($arrReminder);
		}
	}
	
	public static function LoadPoliceCheckRecordsByUserID($UserID,$show_deleted = false)
	{
		global $PoliceCheck;
		
		$arrReminderDates = getReminderDatesByTypeAndUserID($PoliceCheck,$UserID,$show_deleted)->fetchAll();
		
		$ReminderDates = array();
		
		foreach( $arrReminderDates as $item)
		{
			$ReminderDates[] = ReminderDate::ArrayItemToReminder($item);
		}
		
		return $ReminderDates;
		
	}
	
	public function UpdateReminderDate($Date)
	{
		$this->Date = $Date;
		
		return updReminderDate($this->ReminderID,$this->Date);
		
	}
	
	public static function CreatePoliceCheckRecord($UserID,$Date)
	{
		global $PoliceCheck;
		
		$newID = addReminder($UserID,$Date,$PoliceCheck);
		
		return ReminderDate::LoadReminder($newID);
		
	}
	
	public static function LoadReminder($RemID,$show_deleted = false)
	{
		$RemID = intval($RemID);
		
		// Insert code to retrieve staff member here
		$pdoReminder = getReminderByID($RemID,$show_deleted);
		
		if($pdoReminder->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrRem = $pdoReminder->fetch();
			return ReminderDate::ArrayItemToReminder($arrRem);
		}
		
	}
	
	public static function ArrayItemToReminder($item)
	{
		$thisReminderDate = new ReminderDate();
		
		// User Details
		$thisReminderDate->SetReminderID($item['id_reminder_date']);
		$thisReminderDate->SetDate($item['fld_date']);
		$thisReminderDate->SetUserID($item['fld_user_id']);
		$thisReminderDate->SetTypeID($item['fld_type_id']);
		$thisReminderDate->SetDeleted($item['fld_deleted']);
		
		return $thisReminderDate;
	}
} // END CLASS REMINDER

function getLastReminderByUserAndType($UserID,$ReminderTypeID,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$ReminderDate = $dbh->prepare('
										SELECT  MAX(fld_date) AS last_police_check, tbl_reminder_dates.*
										FROM tbl_reminder_dates
										WHERE fld_user_id = :UserID
										AND fld_type_id = :ReminderTypeID
										'.$show.';
										');
			$ReminderDate->execute(array(
											':UserID' => $UserID,
											':ReminderTypeID' => $ReminderTypeID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $ReminderDate;
}

function getReminderByID($ReminderID,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$ReminderDates = $dbh->prepare('
										SELECT *
										FROM tbl_reminder_dates
										WHERE id_reminder_date = :ReminderID
										'.$show.';
										');
			$ReminderDates->execute(array(
										':ReminderID' => $ReminderID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $ReminderDates;
}

function updReminderDate($ReminderID,$Date)
{
	global $dbh;
	try {
			$Update = $dbh->prepare('
										UPDATE tbl_reminder_dates
										SET 
										fld_date = :Date
										WHERE id_reminder_date = :ReminderID
										');
			$Update->execute(array(
										':Date' => ($Date == 'null') ? null : $Date,
										':ReminderID' => $ReminderID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $ReminderID;
}

function getReminderDatesByTypeAndUserID($TypeID,$UserID,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$ReminderDates = $dbh->prepare('
										SELECT *
										FROM tbl_reminder_dates
										WHERE fld_user_id = :UserID
										AND fld_type_id = :TypeID
										'.$show.'
										ORDER BY fld_date DESC; 
										');
			$ReminderDates->execute(array(
										':UserID' => $UserID,
										':TypeID' => $TypeID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $ReminderDates;
}

function addReminder($UserID,$Date,$ReminderType)
{
	global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_reminder_dates(fld_user_id,fld_date,fld_type_id) 
								  VALUES (:UserID,:Date,:ReminderType) ');
		$qryInsert->execute(array(
									':UserID' => $UserID,
									':Date' => ($Date == 'null') ? null : $Date,
									':ReminderType' => $ReminderType
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
}

class Member extends User
{
	private $FirstName;
	private $LastName;
	private $GenderID;
	
	public function SetFirstName($FirstName)
	{
		$this->FirstName = $FirstName;
	}
	
	public function GetFirstName()
	{
		return $this->FirstName;
	}
	
	public function SetLastName($LastName)
	{
		$this->LastName = $LastName;
	}
	
	public function GetLastName()
	{
		return $this->LastName;
	}
	
	public function SetGenderID($GenderID)
	{
		$this->GenderID = $GenderID;
	}
	
	public function GetGenderID()
	{
		return $this->GenderID;
	}
	
	public function GetGenderName()
	{
		$pdoGender = getGender($this->GenderID);
		
		if($pdoGender->rowCount() != 1)
		{
			return 'bad gender';
		} else {
			$arrGender = $pdoGender->fetch();
			return $arrGender['fld_gender'];
		}
		
	}
	
	public static function CountMembersByRegionAttendance($RegionID,$StartDate,$EndDate)
	{
		$GroupMembers = getCountMembersInPeriodByRegion($RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $GroupMembers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['members']);
				
				$i++;
			}
			
			return $index_array;
	}
	
	public static function CountMemberAttendancesByRegionAttendance($RegionID,$StartDate,$EndDate)
	{
		$GroupMembers = getCountMemberAttendancesInPeriodByRegion($RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $GroupMembers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['member_attendances']);
				
				$i++;
			}
			
			return $index_array;
	}
	
	public static function CountMemberAttendancesByBranch($BranchID,$StartDate,$EndDate)
	{
		$GroupMembers = getCountMemberAttendancesInPeriodByBranch($BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $GroupMembers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['member_attendances']);
				
				$i++;
			}
			
			return $index_array;
	}
	
	public static function CountMembersByBranchAttendance($BranchID,$StartDate,$EndDate)
	{
		$GroupMembers = getCountMembersInPeriodByBranch($BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $GroupMembers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['members']);
				
				$i++;
			}
			
			return $index_array;
	}
	
	public static function LoadMembersByGroupAttendance($GroupID,$StartDate,$EndDate)
	{
		$Members = array();
		
		$arrIDs = getMembersByGroupAttendance($GroupID,$StartDate,$EndDate)->fetchAll();
		
		foreach( $arrIDs as $thisID )
		{
			$Members[] = Member::LoadMember($thisID['fld_user_id']);
		}
		
		return $Members;
	}
	
	public function IsMyCommittedRecord($CommittedID)
	{
		$CommittedID = intval($CommittedID);
		
		return MemberCommittedDates::IsMyCommittedDate($this->UserID,$CommittedID);
	}
	
	public function MakeCommitted($Date)
	{
		//make sure member isn't already committed
		if(!$this->GetCommitted($Date))
		{
			MemberCommittedDates::CreateCommittedDate($this->UserID,$Date);
		}
	}
	
	public function GetMostRecentCommittedRecord()
	{
		return MemberCommittedDates::LoadMostRecentCommittedRecord($this->UserID);
	}
	
	public function GetCommitted($Date)
	{
		return MemberCommittedDates::IsMemberCommitted($this->UserID,$Date);
	}
	
	public function GetCommittedDates()
	{
		return MemberCommittedDates::LoadMembersCommittedDates($this->UserID);
	}
	
	public function GetActivityDates()
	{
		global $MemberString;
		
		return UserActivityDates::LoadUserActivityDates($this->UserID,$MemberString);
	}
	
	public function CreateUserActivity($StartDate,$EndDate)
	{
		global $MemberString;
		
		UserActivityDates::CreateUserActivity($this->UserID,$MemberString,$StartDate,$EndDate);
	}
	
	public function GetCurrentActivity()
	{
		global $MemberString;
		
		return UserActivityDates::LoadCurrentUserActivity($this->UserID,$MemberString);
	}
	
	public function GetFirstAttended() //returns null if member has never attended
	{
		return \Business\Attendance::GetAttendanceUserFirstAttended($this->UserID);
	}
	
	function __construct() {
       parent::__construct();
       //yet another empty constructor
   	}
   	
	public function CreateCommittedRecord($CommittedDate)
	{
		return MemberCommittedDates::CreateCommittedDate($this->UserID,$CommittedDate);
	}
	
   	public function GetLastGroupAttended()
	{
		return \Business\Group::LoadMemberLastGroupAttended($this->UserID);
		
	}
	
	public static function LoadLapsedCommittedGrowers($months_since_attended)
	{
		$Members = array();
		
		$arrIDs = getLapsedCommittedGrowers($months_since_attended)->fetchAll();
		
		foreach( $arrIDs as $thisID )
		{
			$Members[] = Member::LoadMember($thisID['fld_user_id']);
		}
		
		return $Members;
	}
	
	public static function CreateMemberFromUser($UserID,$FirstName,$Lastname,$Gender)
	{
				
		addMember(
				$UserID,$FirstName,$Lastname,$Gender
				);
		
		return Member::LoadMember($UserID);
	}
   
   	public static function CreateMember($FirstName,$Lastname,$Gender)
	{
		$NewUserID = addEmptyUser();
					
		addMember(
				$NewUserID,$FirstName,$Lastname,$Gender
				);
		
		return Member::LoadMember($NewUserID);
	}
   
   	public static function LoadMember($UserID)
	{
		$UserID = intval($UserID);
		
		// Insert code to retrieve staff member here
		$pdoMember = getMemberByID($UserID);
		
		if($pdoMember->rowCount() == 0)
		{
			return false;
		} else {
			$arrMember = $pdoMember->fetch();
			return Member::ArrayItemToMember($arrMember);
		}
		
	}
	
	public static function LoadNewCommittedMembersByRegionByGroupAttendanceBetweenDates($RegionID,$StartDate,$EndDate)
	{
		$RegionID = intval($RegionID);
		
		$arrMembers = getNewCommittedMembersByRegionByGroupAttendanceBetweenDates($RegionID,$StartDate,$EndDate)->fetchAll();
		
		$Members = array();
		
		foreach( $arrMembers as $Member )
		{		
			$Members[] = Member::ArrayItemToMember($Member);
		}
		
		return $Members;
	}
	
	public static function LoadNewCommittedMembersByGroupAttendanceBetweenDates($GroupID,$StartDate,$EndDate)
	{
		$GroupID = intval($GroupID);
		
		$arrMembers = getNewCommittedMembersByGroupAttendanceBetweenDates($GroupID,$StartDate,$EndDate)->fetchAll();
		
		$Members = array();
		
		foreach( $arrMembers as $Member )
		{		
			$Members[] = Member::ArrayItemToMember($Member);
		}
		
		return $Members;
	}
	
	public static function LoadMemberByRegionAttendanceBetweenDates($RegionID,$StartDate,$EndDate,$LimitToCommitted = false)
	{
		$RegionID = intval($RegionID);
		
		$arrMembers = getMemberByRegionAttendanceBetweenDates($RegionID,$StartDate,$EndDate,$LimitToCommitted)->fetchAll();
		
		$Members = array();
		
		foreach( $arrMembers as $Member )
		{		
			$Members[] = Member::ArrayItemToMember($Member);
		}
		
		return $Members;
	}
	
	public static function LoadMemberByGroupAttendanceBetweenDates($GroupID,$StartDate,$EndDate,$LimitToCommitted = false)
	{
		$GroupID = intval($GroupID);
		
		$arrMembers = getMemberByGroupAttendanceBetweenDates($GroupID,$StartDate,$EndDate,$LimitToCommitted)->fetchAll();
		
		$Members = array();
		
		foreach( $arrMembers as $Member )
		{		
			$Members[] = Member::ArrayItemToMember($Member);
		}
		
		return $Members;
	}
	
	public static function LoadMembersBySearchString($SearchString,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		$arrMembers = getMembersBySearchString($SearchString,$Hide_CO)->fetchAll();
		
		$Members = array();
		
		foreach( $arrMembers as $Member )
		{		
			$Members[] = Member::ArrayItemToMember($Member);
		}
		
		return $Members;
		
		
	}
	
	public static function LoadMembersBySearchStringGroupOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringGroupOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
	
	public static function LoadMembersBySearchStringGroupOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringGroupOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
	
	public static function LoadMembersBySearchStringRegionOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringRegionOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
	
	public static function LoadMembersBySearchStringRegionOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringRegionOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
	
	public static function LoadMembersBySearchStringStateOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringStateOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
	
	public static function LoadMembersBySearchStringStateOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringStateOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
	
	public static function LoadMembersBySearchStringOptimised($SearchString,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringOptimised($SearchString,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
	
	public static function LoadMembersBySearchStringOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true) //auto hide community observers
	{
		$SearchString = trim($SearchString);
		
		return getMembersBySearchStringOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO)->fetchAll();
		
	}
		
	public static function ArrayItemToMember($item)
	{
		$thisMember = new Member();
		
		// User Details
		$thisMember->SetUserID($item['id_user']);
		$thisMember->SetUserTypeID($item['fld_user_type_id']);
		$thisMember->SetUserName($item['fld_username']);
		$thisMember->SetHashedPassword($item['fld_password']);
		$thisMember->SetSalt($item['fld_salt']);
		$thisMember->SetScreenName($item['fld_screen_name']);
		$thisMember->SetEmailAddress($item['fld_email_address']);
		$thisMember->SetDeleted($item['fld_deleted']);
		$thisMember->SetUserTypeName($item['fld_user_type']);
		$thisMember->SetUserTypeCategory($item['fld_type_category']);
		$thisMember->SetDefaultPage($item['fld_default_page']);
		// Member Details
		$thisMember->SetFirstName($item['fld_first_name']);
		$thisMember->SetLastname($item['fld_last_name']);
		$thisMember->SetGenderID($item['fld_gender']);
		
		return $thisMember;
	}
	
	
	
	public function UpdateMember($FirstName,$LastName,$Gender)
	{
				
		$this->FirstName = $FirstName;
		$this->LastName = $LastName;
		$this->GenderID = $Gender;
		
		
		updMember($this->UserID,$this->FirstName,$this->LastName,$this->GenderID);
		
	}
	
	public static function MergeMembers($to_keep,$to_destroy,$staff_to_keep)
	{
		if( $staff_to_keep != '' )
		{
			\Membership\Staff::MergeStaff($to_destroy,$to_keep,$staff_to_keep);
		
		}
		
		\Membership\MemberCommittedDates::MergeMemberCommittedDates($to_keep,$to_destroy);
		
		\Membership\User::MergeUsers($to_keep,$to_destroy);
		
	}
	
	
} // END MEMBER CLASS 

function getCountVolunteerAttendanceInPeriodByRoleBranch($Role,$BranchID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (
															
															SELECT COUNT(DISTINCT tbl_group_attendance.id_attendance)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															JOIN tbl_groups_roles
															ON tbl_group_attendance.fld_user_id = tbl_groups_roles.fld_user_id
															JOIN tbl_group_roles
															ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_regions.fld_region_id IN(
																									SELECT id_region
																									FROM tbl_regions
																									WHERE fld_branch_id = :BranchID
																									AND tbl_regions.fld_deleted = 0
																									)
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :STAFF_VOL_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_roles.fld_group_role = :Role
															AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_groups_roles.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_roles.fld_end_date
																)
															AND tbl_groups_roles.fld_deleted = 0
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
														) AS volunteer_attendances
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id IN(
																	SELECT id_region
																	FROM tbl_regions
																	WHERE fld_branch_id = :BranchID2
																	AND tbl_regions.fld_deleted = 0
																	)
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0			
												)
								
								
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
		
								':BranchID' => $BranchID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':STAFF_VOL_STRING' => STAFF_VOL_STRING,
								':Role' => $Role,
								':BranchID2' => $BranchID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
} 

function getCountVolunteersInPeriodByRoleBranch($Role,$BranchID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (
															
															SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															JOIN tbl_groups_roles
															ON tbl_group_attendance.fld_user_id = tbl_groups_roles.fld_user_id
															JOIN tbl_group_roles
															ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_regions.fld_region_id IN(
																									SELECT id_region
																									FROM tbl_regions
																									WHERE fld_branch_id = :BranchID
																									AND tbl_regions.fld_deleted = 0
																									)
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :STAFF_VOL_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_roles.fld_group_role = :Role
															AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_groups_roles.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_roles.fld_end_date
																)
															AND tbl_groups_roles.fld_deleted = 0
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
														) AS volunteers
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id IN(
																	SELECT id_region
																	FROM tbl_regions
																	WHERE fld_branch_id = :BranchID2
																	AND tbl_regions.fld_deleted = 0
																	)
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0			
												)
								
								
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
		
								':BranchID' => $BranchID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':STAFF_VOL_STRING' => STAFF_VOL_STRING,
								':Role' => $Role,
								':BranchID2' => $BranchID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
} 

function getCountVolunteerAttendancesInPeriodByRoleRegion($Role,$RegionID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (	
															SELECT COUNT(DISTINCT tbl_group_attendance.id_attendance)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															JOIN tbl_groups_roles
															ON tbl_group_attendance.fld_user_id = tbl_groups_roles.fld_user_id
															JOIN tbl_group_roles
															ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
															AND tbl_groups_regions.fld_region_id = :RegionID
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :STAFF_VOL_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_roles.fld_group_role = :Role
															AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_groups_roles.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_roles.fld_end_date
																)
															AND tbl_groups_roles.fld_deleted = 0
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
															
															
														) AS volunteer_attendances
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id = :RegionID2
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0				
												)
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
								':RegionID' => $RegionID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':STAFF_VOL_STRING' => STAFF_VOL_STRING,
								':Role' => $Role,
								':RegionID2' => $RegionID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
}

function getCountVolunteersInPeriodByRoleRegion($Role,$RegionID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (	
															SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															JOIN tbl_groups_roles
															ON tbl_group_attendance.fld_user_id = tbl_groups_roles.fld_user_id
															JOIN tbl_group_roles
															ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
															AND tbl_groups_regions.fld_region_id = :RegionID
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :STAFF_VOL_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_roles.fld_group_role = :Role
															AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_groups_roles.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_roles.fld_end_date
																)
															AND tbl_groups_roles.fld_deleted = 0
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
															
															
														) AS volunteers
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id = :RegionID2
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0				
												)
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
								':RegionID' => $RegionID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':STAFF_VOL_STRING' => STAFF_VOL_STRING,
								':Role' => $Role,
								':RegionID2' => $RegionID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
}

function getCountMemberAttendancesInPeriodByBranch($BranchID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (
															SELECT COUNT(DISTINCT tbl_group_attendance.id_attendance)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_regions.fld_region_id IN(
																									SELECT id_region
																									FROM tbl_regions
																									WHERE fld_branch_id = :BranchID
																									AND tbl_regions.fld_deleted = 0
																									)
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :MEMBER_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
														) AS member_attendances
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id IN(
																	SELECT id_region
																	FROM tbl_regions
																	WHERE fld_branch_id = :BranchID2
																	AND tbl_regions.fld_deleted = 0
																	)
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0			
												)
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
								':BranchID' => $BranchID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':MEMBER_STRING' => MEMBER_STRING,
								':BranchID2' => $BranchID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
}

function getCountMembersInPeriodByBranch($BranchID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (
															SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_regions.fld_region_id IN(
																									SELECT id_region
																									FROM tbl_regions
																									WHERE fld_branch_id = :BranchID
																									AND tbl_regions.fld_deleted = 0
																									)
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :MEMBER_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
														) AS members
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id IN(
																	SELECT id_region
																	FROM tbl_regions
																	WHERE fld_branch_id = :BranchID2
																	AND tbl_regions.fld_deleted = 0
																	)
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0			
												)
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
								':BranchID' => $BranchID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':MEMBER_STRING' => MEMBER_STRING,
								':BranchID2' => $BranchID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
} 

function getCountMemberAttendancesInPeriodByRegion($RegionID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (
															SELECT COUNT(DISTINCT tbl_group_attendance.id_attendance)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_regions.fld_region_id = :RegionID
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :MEMBER_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
														) AS member_attendances
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id = :RegionID2
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0				
												)
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
								':RegionID' => $RegionID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':MEMBER_STRING' => MEMBER_STRING,
								':RegionID2' => $RegionID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
}

function getCountMembersInPeriodByRegion($RegionID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Count = $dbh->prepare('	
								SELECT fld_group_name, (
															SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															JOIN tbl_user_activity_dates
															ON tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_groups_regions.fld_region_id = :RegionID
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_group_attendance.fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_user_activity_dates.fld_user_type_string = :MEMBER_STRING
															AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
															AND (
																tbl_user_activity_dates.fld_end_date IS NULL OR
																tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																)
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															AND tbl_user_activity_dates.fld_deleted = 0
															AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
															GROUP BY tbl_group_attendance.fld_group_id
														) AS members
								FROM tbl_groups
								WHERE id_group IN(
									
												SELECT fld_group_id
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id = :RegionID2
												AND fld_start_date <= :EndDate2
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
												AND tbl_groups_regions.fld_deleted = 0				
												)
								AND fld_non_group_type IS NULL
								AND tbl_groups.fld_deleted = 0
								GROUP BY id_group
								ORDER BY fld_group_name ASC;
								');
		$Count->execute(array(	
								':RegionID' => $RegionID,
								':StartDate' => $StartDate,
								':EndDate' => $EndDate,
								':MEMBER_STRING' => MEMBER_STRING,
								':RegionID2' => $RegionID,
								':EndDate2' => $EndDate,
								':StartDate2' => $StartDate
								
								 ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Count;
}

function getMembersByGroupAttendance($GroupID,$StartDate,$EndDate)
{
	global $dbh;
	
	try {
			$Members = $dbh->prepare(' 	SELECT DISTINCT tbl_members.fld_user_id
										FROM tbl_members
										JOIN tbl_user_activity_dates
										ON tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id
										JOIN tbl_group_attendance
										ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
										WHERE tbl_user_activity_dates.fld_start_date <= :EndDate
										AND ( 
														tbl_user_activity_dates.fld_end_date IS NULL OR
														tbl_user_activity_dates.fld_end_date >= :StartDate
														)
										AND tbl_user_activity_dates.fld_user_type_string = :MemberString
										AND tbl_user_activity_dates.fld_deleted = 0
										AND tbl_group_attendance.fld_group_id = :GroupID
										AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
										
										AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date 
										AND (tbl_user_activity_dates.fld_end_date IS NULL OR 
											tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date);
										');
			$Members->execute(array(
										':EndDate' => $EndDate,
										':StartDate' => $StartDate,
										':MemberString' => MEMBER_STRING,
										':GroupID' => $GroupID,
										':StartDate2' => $StartDate,
										':EndDate2' => $EndDate
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Members;
	
}

function getLapsedCommittedGrowers($months_since_attended)
{
	global $dbh;
	global $MemberString;
	
	try {
			$Members = $dbh->prepare(' 	SELECT tbl_members.fld_user_id
										FROM tbl_members
										WHERE EXISTS(
													SELECT *
													FROM tbl_user_activity_dates
													WHERE tbl_user_activity_dates.fld_user_id = tbl_members.fld_user_id
													AND fld_start_date <= DATE(NOW())
													AND ( 
														fld_end_date IS NULL OR
														fld_end_date >= DATE(NOW())
														)
													AND fld_user_type_string = :MemberString
													AND fld_deleted = 0
													)
										AND EXISTS(
													SELECT *
													FROM tbl_member_committed_dates
													WHERE tbl_member_committed_dates.fld_user_id = tbl_members.fld_user_id
													AND fld_start_date <= DATE(NOW())
													AND ( 
														fld_end_date IS NULL OR
														fld_end_date >= DATE(NOW())
														)
													AND fld_deleted = 0 
													)
										AND TIMESTAMPDIFF(	MONTH, 
															(	
																SELECT MAX(fld_date) AS last_attended
																FROM tbl_group_attendance
																WHERE fld_deleted = 0
																AND tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
															
															),
															DATE(NOW())
														  ) >= :months_since_attended
										');
			$Members->execute(array(
										':MemberString' => $MemberString,
										':months_since_attended' => $months_since_attended
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Members;
}

function deleteMember($to_destroy)
{
	global $dbh;
	try {
			$Member = $dbh->prepare('
										DELETE FROM tbl_members
										WHERE fld_user_id = :UserID;
										');
			$Member->execute(array(
										':UserID' => $to_destroy
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Member;
}

class MemberCommittedDates
{
	private $CommittedID;
	private $UserID;
	private $StartDate;
	private $EndDate;
	private $Deleted;
	
	public function SetCommittedID($CommittedID)
	{
		$this->CommittedID = $CommittedID;
	}
	
	public function GetCommittedID()
	{
		return $this->CommittedID;
	}
	
	public function SetUserID($UserID)
	{
		$this->UserID = $UserID;
	}
	
	public function GetUserID()
	{
		return $this->UserID;
	}
			
	public function SetStartDate($StartDate)
	{
		$this->StartDate = $StartDate;
	}
	
	public function GetStartDate()
	{
		return $this->StartDate;
	}
	
	public function SetEndDate($EndDate)
	{
		$this->EndDate = $EndDate;
	}
	
	public function GetEndDate()
	{
		return $this->EndDate;
	}
	
	public function SetDeleted($Deleted)
	{
		$this->Deleted = $Deleted;
	}
	
	public function GetDeleted()
	{
		return $this->Deleted;
	}
	
	function __construct() {
		//empty constructor that may need to be altered later
	}
	
	public static function IsMyCommittedDate($UserID,$CommittedID)
	{
		return isMyCommittedDate($UserID,$CommittedID);
	}
	
	public function UpdateMemberCommitted($StartDate,$EndDate)
	{
		return updMemberCommittted($this->CommittedID,$StartDate,$EndDate);

	}
	
	public static function MergeMemberCommittedDates($to_keep,$to_destroy)
	{
		mergeMemberCommittedDates($to_keep,$to_destroy);
	}
	
	public static function LoadMostRecentCommittedRecord($UserID,$show_deleted = false)
	{
		$UserID = intval($UserID);
		
		$pdoCmdDte = getMostRecentCommittedDateByUserID($UserID,$show_deleted);
		
		if($pdoCmdDte->rowCount() == 0)
		{
			return NULL;
		} else {
			
			$arrCmdDte = $pdoCmdDte->fetch();
			
			return MemberCommittedDates::LoadCommittedDate($arrCmdDte['id_committed']);
		}
	}
	
	public static function IsMemberCommitted($UserID,$Date)
	{
		$UserID = intval($UserID);
		
		$pdoCmdDte = getCommittedDateByUserIDAndDate($UserID,$Date,false);
		
		if($pdoCmdDte->rowCount() == 0)
		{
			return false;
		} else {
			return true;
		}
		
	}
	
	public static function LoadMembersCommittedDates($UserID,$show_deleted = false)
	{
		
		$ArrayCommittedDates = array();
		
		$pdoCmdDtes = getCommittedDatesByUserID($UserID,$show_deleted);
		
		if($pdoCmdDtes->rowCount() == 0)
		{
			return NULL;
		} else {
			$ArrCmdDtes = $pdoCmdDtes->fetchAll();
			
			foreach( $ArrCmdDtes As $CmdDte )
			{
				$ArrayCommittedDates[] = MemberCommittedDates::ArrayItemToMemberCommittedDates($CmdDte);
			}
		}
		
		return $ArrayCommittedDates;
	}
	
	public static function CreateCommittedDateComplete($UserID,$StartDate,$EndDate)
	{
					
		$NewCmdDteID = addCommittedDateComplete(
				$UserID,$StartDate
				);
		
		return MemberCommittedDates::LoadCommittedDate($NewCmdDteID);
	}
	
	public static function CreateCommittedDate($UserID,$StartDate)
	{
					
		$NewCmdDteID = addCommittedDate(
				$UserID,$StartDate
				);
		
		return MemberCommittedDates::LoadCommittedDate($NewCmdDteID);
	}
	
	public function MarkAsDeleted()
	{
		$this->Deleted = 1;
		
		delToggleMemberCommittedDate($this->CommittedID,$this->Deleted);
		
		return $this->CommittedID;
	}
	
	public static function LoadCommittedDate($CommittedID,$show_deleted = false)
	{
		$CommittedID = intval($CommittedID);
		
		// Insert code to retrieve staff member here
		$pdoCmdDte = getCommittedDate($CommittedID,$show_deleted);
		
		if($pdoCmdDte->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrCmdDte = $pdoCmdDte->fetch();
			return MemberCommittedDates::ArrayItemToMemberCommittedDates($arrCmdDte);
		}
		
	}
	
	public static function ArrayItemToMemberCommittedDates($item)
	{
		$thisMemberCommittedDates = new MemberCommittedDates();
		
		// User Details
		$thisMemberCommittedDates->SetCommittedID($item['id_committed']);
		$thisMemberCommittedDates->SetUserID($item['fld_user_id']);
		$thisMemberCommittedDates->SetStartDate($item['fld_start_date']);
		$thisMemberCommittedDates->SetEndDate($item['fld_end_date']);
		$thisMemberCommittedDates->SetDeleted($item['fld_deleted']);
		
		return $thisMemberCommittedDates;
	}
} // End Member Committed Dates

function getMostRecentCommittedDateByUserID($UserID,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$Committed = $dbh->prepare('
										SELECT tbl_member_committed_dates.*, MAX(DATE(fld_start_date))
										FROM tbl_member_committed_dates
										WHERE fld_user_id = :UserID
										'.$show.' 
										');
			$Committed->execute(array(
										':UserID' => $UserID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Committed;
}

function delMemberCommittedDateByID($CommittedID)
{
	global $dbh;
	try {
			$Committed = $dbh->prepare('
										DELETE FROM tbl_member_committed_dates
										WHERE id_committed = :CommittedID
										
										');
			$Committed->execute(array(
										':CommittedID' => $CommittedID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return true;
}

function delToggleMemberCommittedDate($CommittedID,$Deleted)
{
	global $dbh;
	try {
			$Committed = $dbh->prepare('
										UPDATE tbl_member_committed_dates
										SET 
										fld_deleted = :Deleted
										WHERE id_committed = :CommittedID
										');
			$Committed->execute(array(
										':Deleted' => $Deleted,
										':CommittedID' => $CommittedID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $CommittedID;
}

function isMyCommittedDate($UserID,$CommittedID)
{
	global $dbh;
	try {
			$Committed = $dbh->prepare('
										SELECT COUNT(*) AS total
										FROM tbl_member_committed_dates
										WHERE fld_user_id = :UserID
										AND id_committed = :CommittedID
										');
			$Committed->execute(array(
										':UserID' => $UserID,
										':CommittedID' => $CommittedID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	$result = $Committed->fetch();
	
	
	return $result['total'] == 1;
}

function updMemberCommittted($CommittedID,$StartDate,$EndDate)
{
	global $dbh;
	try {
			$Committed = $dbh->prepare('
										UPDATE tbl_member_committed_dates
										SET 
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_committed = :CommittedID
										');
			$Committed->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
										':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':CommittedID' => $CommittedID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $CommittedID;
}

function mergeMemberCommittedDates($to_keep,$to_destroy)
{
	global $dbh;
	try {
			$Committed = $dbh->prepare('
										UPDATE tbl_member_committed_dates
										SET fld_user_id = :ToKeep
										WHERE fld_user_id = :ToDestroy
										');
			$Committed->execute(array(
										':ToKeep' => $to_keep,
										':ToDestroy' => $to_destroy
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $to_keep;
}

function getCurrentCommittedDate($UserID,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$Committed = $dbh->prepare('
										SELECT *
										FROM tbl_member_committed_dates
										WHERE fld_user_id = :UserID
										AND fld_start_date <= DATE(NOW())
										AND ( 
											fld_end_date IS NULL
											OR fld_end_date >= DATE(NOW())
											)
										'.$show.' 
										');
			$Committed->execute(array(
										':UserID' => $UserID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Committed;
}

function getCommittedDateByUserIDAndDate($UserID,$Date,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$Committed = $dbh->prepare('
										SELECT *
										FROM tbl_member_committed_dates
										WHERE fld_user_id = :UserID
										AND fld_start_date <= :Date
										AND ( 
											fld_end_date IS NULL
											OR fld_end_date >= :Date2
											)
										'.$show.' 
										');
			$Committed->execute(array(
										':UserID' => $UserID,
										':Date' => ($Date == 'null') ? null : $Date,
										':Date2' => ($Date == 'null') ? null : $Date
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Committed;
}

function getCommittedDatesByUserID($UserID,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$Committed = $dbh->prepare('
										SELECT *
										FROM tbl_member_committed_dates
										WHERE fld_user_id = :UserID
										'.$show.'
										ORDER BY fld_start_date DESC
										');
			$Committed->execute(array(
										':UserID' => $UserID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Committed;
}

function addCommittedDateComplete($UserID,$StartDate,$EndDate)
{
	global $dbh;
	try {

			$Committed = $dbh->prepare('
										INSERT INTO tbl_member_committed_dates(fld_user_id,fld_start_date,fld_end_date)
										VALUES(:UserID,:StartDate,:EndDate)
										');
			$Committed->execute(array(
										':UserID' => $UserID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
										':EndDate' => ($EndDate == 'null') ? null : $EndDate
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $dbh->lastInsertId();
}

function addCommittedDate($UserID,$StartDate)
{
	global $dbh;
	try {
			$Committed = $dbh->prepare('
										INSERT INTO tbl_member_committed_dates(fld_user_id,fld_start_date)
										VALUES(:UserID,:StartDate)
										');
			$Committed->execute(array(
										':UserID' => $UserID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $dbh->lastInsertId();
}

function getCommittedDate($CommittedID,$show_deleted)
{
	global $dbh;
	
	$show = ($show_deleted ? '' : 'AND fld_deleted = 0' ); 
	
	try {
			$Committed = $dbh->prepare('
										SELECT *
										FROM tbl_member_committed_dates
										WHERE id_committed = :CommittedID
										'.$show.'
										');
			$Committed->execute(array(
										':CommittedID' => $CommittedID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Committed;
}

class UserActivityDates
{
	private $UserActivityID;
	private $UserID;
	private $UserTypeString;
	private $StaffRoleID;
	private $StartDate;
	private $EndDate;
	private $Deleted;
	
	public function SetUserActivityID($UserActivityID)
	{
		$this->UserActivityID = $UserActivityID;
	}
	
	public function GetUserActivityID()
	{
		return $this->UserActivityID;
	}
	
	public function SetUserID($UserID)
	{
		$this->UserID = $UserID;
	}
	
	public function GetUserID()
	{
		return $this->UserID;
	}
	
	public function SetUserTypeString($UserTypeString)
	{
		$this->UserTypeString = $UserTypeString;
	}
	
	public function GetUserTypeString()
	{
		return $this->UserTypeString;
	}
	
	public function SetStaffRoleID($StaffRoleID)
	{
		$this->StaffRoleID = $StaffRoleID;
	}
	
	public function GetStaffRoleID()
	{
		return $this->StaffRoleID;
	}
	
	public function GetStaffRole()
	{
		return StaffRole::LoadStaffRole($this->StaffRoleID);
	}
		
	public function SetStartDate($StartDate)
	{
		$this->StartDate = $StartDate;
	}
	
	public function GetStartDate()
	{
		return $this->StartDate;
	}
	
	public function SetEndDate($EndDate)
	{
		$this->EndDate = $EndDate;
	}
	
	public function GetEndDate()
	{
		return $this->EndDate;
	}
	
	public function SetDeleted($Deleted)
	{
		$this->Deleted = $Deleted;
	}
	
	public function GetDeleted()
	{
		return $this->Deleted;
	}
	
	function __construct() {
		//empty constructor that may need to be altered later
	}
   	
	public static function LoadAllUserActivityDates($UserID)
	{
		$UserID = intval($UserID);
		
		$arrActivities = getActivities($UserID)->fetchAll();
		
		$AllActivities = array();
		
		foreach( $arrActivities as $Activity )
		{
			$AllActivities[] = UserActivityDates::ArrayItemToUserActivity($Activity);
		}
		
		return $AllActivities;
	}
	
	public static function LoadUserActivityDates($UserID,$UserType,$Deleted = false)
	{
		$UserID = intval($UserID);
		
		$arrActivities = getActivitiesByUserIDAndType($UserID,$UserType,$Deleted)->fetchAll();
		
		$AllActivities = array();
		
		foreach( $arrActivities as $Activity )
		{
			$AllActivities[] = UserActivityDates::ArrayItemToUserActivity($Activity);
		}
		
		return $AllActivities;
	}
	
	public static function LoadCurrentUserActivity($UserID,$UserType)
	{
		//if there are two conflicting records this will only collect the first
		$UserID = intval($UserID);
				
		$pdoCurrentActivity = getCurrentActivity($UserID,$UserType);
		
		if($pdoCurrentActivity->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrActivity = $pdoCurrentActivity->fetch();
			return UserActivityDates::ArrayItemToUserActivity($arrActivity);
		}
	}
	
	public static function LoadLastUserActivity($UserID,$UserType) //may return future activities
	{
		//if there are two conflicting records this will only collect the first
		$UserID = intval($UserID);
				
		$pdoLastUserActivity = getLastUserActivity($UserID,$UserType);
		
		if($pdoLastUserActivity->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrActivity = $pdoLastUserActivity->fetch();
			return UserActivityDates::ArrayItemToUserActivity($arrActivity);
		}
	}
	
   	public static function CreateUserActivity($UserID,$UserTypeString,$StartDate,$EndDate,$StaffRole = 'null')
	{
		$UserID = intval($UserID);
					
		$NewActivityID = addUserActivity(
				$UserID,$UserTypeString,$StartDate,$EndDate,$StaffRole
				);
		
		return UserActivityDates::LoadUserActivity($NewActivityID);
	}
   
   	public static function LoadUserActivity($UserActivityID)
	{
		$UserActivityID = intval($UserActivityID);
		
		// Insert code to retrieve staff member here
		$pdoActivity = getUserActivityByID($UserActivityID);
		
		if($pdoActivity->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrActivity = $pdoActivity->fetch();
			return UserActivityDates::ArrayItemToUserActivity($arrActivity);
		}
		
	}
	
	public static function ArrayItemToUserActivity($item)
	{
		$thisActivity = new UserActivityDates();
		
		// Details SetStaffRole
		$thisActivity->SetUserActivityID($item['id_user_activity']);
		$thisActivity->SetUserID($item['fld_user_id']);
		$thisActivity->SetUserTypeString($item['fld_user_type_string']);
		$thisActivity->SetStaffRoleID($item['fld_staff_type_id']);
		$thisActivity->SetStartDate($item['fld_start_date']);
		$thisActivity->SetEndDate($item['fld_end_date']);
		
		return $thisActivity;
	}
	
	public function UpdateUserActivity($StartDate,$EndDate,$StaffRole = 'null')
	{
		
		$this->StartDate = $StartDate;
		$this->EndDate = $EndDate;
		
		
		updUserActivity($this->UserActivityID,$this->StartDate,$this->EndDate,$StaffRole);
		
	}
	
	public function UpdateUserActivityComplete($UserID,$UserTypeString,$StartDate,$EndDate) // will probably never use this
	{
		
		$this->UserID = $UserID;
		$this->UserTypeString = $UserTypeString;
		$this->StartDate = $StartDate;
		$this->EndDate = $EndDate;
		
		
		updUserActivityComplete($this->UserActivityID,$this->UserID,$this->UserTypeString,$this->StartDate,$this->EndDate);
		
	}
} // END USER ACTIVITY CLASS

function delActivityDateByID($ActivityID)
{
	global $dbh;
	
	try {
			$UserActivity = $dbh->prepare('
										DELETE FROM tbl_user_activity_dates
										WHERE id_user_activity = :ActivityID
										');
			$UserActivity->execute(array(
										':ActivityID' => $ActivityID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return true;
}

function getActivitiesByUserIDAndType($UserID,$UserType,$Deleted)
{
	global $dbh;
	
	$is_deleted = ($Deleted ? '' : 'AND fld_deleted = 0' );
	
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_user_activity_dates.*
										FROM tbl_user_activity_dates
										WHERE fld_user_id = :UserID
										AND fld_user_type_string = :UserType
										'.$is_deleted.'
										ORDER BY fld_start_date DESC;
										');
			$UserActivity->execute(array(
										':UserID' => $UserID,
										':UserType' => $UserType
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function getActivities($UserID)
{
	global $dbh;
	
	
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_user_activity_dates.*
										FROM tbl_user_activity_dates
										WHERE fld_user_id = :UserID
										ORDER BY fld_start_date DESC
										');
			$UserActivity->execute(array(
										':UserID' => $UserID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function getUserActivityByID($UserActivityID)
{
	global $dbh;
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_user_activity_dates.*
										FROM tbl_user_activity_dates
										WHERE id_user_activity = :UserActivityID
										
										');
			$UserActivity->execute(array(
										':UserActivityID' => $UserActivityID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function getLastUserActivity($UserID,$UserType)
{
	global $dbh;
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_user_activity_dates.*
										FROM tbl_user_activity_dates
										WHERE fld_user_id = :UserID
										AND fld_user_type_string = :UserType
										AND fld_start_date = (
																SELECT MAX(fld_start_date) AS StartDate
																FROM tbl_user_activity_dates
																WHERE fld_user_id = :UserID2
																AND fld_user_type_string = :UserType2
																)
										');
			$UserActivity->execute(array(
										':UserID' => $UserID,
										':UserType' => $UserType,
										':UserID2' => $UserID,
										':UserType2' => $UserType
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function getCurrentActivity($UserID,$UserType)
{
	global $dbh;
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_user_activity_dates.* 
										FROM tbl_user_activity_dates
										WHERE fld_user_id = :UserID
										AND fld_user_type_string = :UserType
										AND fld_start_date <= DATE(NOW())
										AND (fld_end_date IS NULL 
											OR fld_end_date >= DATE(NOW()))
										');
			$UserActivity->execute(array(
										':UserID' => $UserID,
										':UserType' => $UserType
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function updUserActivity(
				$UserActivityID,$StartDate,$EndDate,$StaffRole
				)
{
	global $dbh;
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_user_activity_dates
									SET
										fld_start_date = :StartDate,
										fld_end_date = :EndDate,
										fld_staff_type_id = :StaffRole
										WHERE id_user_activity = :UserActivityID
									');
		$qryUpdate->execute(array(
								 ':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 ':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								 ':StaffRole' => ($StaffRole == 'null') ? null : $StaffRole,
								 ':UserActivityID' => $UserActivityID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function updUserActivityComplete(
				$UserActivityID,$UserID,$UserTypeString,$StartDate,$EndDate
				)
{
	global $dbh;
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_user_activity_dates
									SET
										fld_user_id = :UserID,
										fld_user_type_string = :UserTypeString,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_user_activity = :UserActivityID
									');
		$qryUpdate->execute(array(
								 ':UserID' => $UserID, 
								 ':UserTypeString' => $UserTypeString,
								 ':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 ':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								 ':UserActivityID' => $UserActivityID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function addUserActivity($UserID,$UserTypeString,$StartDate,$EndDate,$StaffRole)
{
	global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_user_activity_dates(fld_user_id,fld_user_type_string,fld_start_date,fld_end_date,fld_staff_type_id) 
								  VALUES (:UserID,:UserTypeString,:StartDate,:EndDate,:StaffRole) ');
		$qryInsert->execute(array(
								':UserID' => $UserID,
								':UserTypeString' => $UserTypeString, 
								':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								':StaffRole' => ($StaffRole == 'null') ? null : $StaffRole
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
}

class StaffRole
{
	private $StaffRoleID;
	private $RoleName;
	private $StaffVolString;
		
	public function SetStaffRoleID($StaffRoleID)
	{
		$this->StaffRoleID = $StaffRoleID;
	}
	
	public function GetStaffRoleID()
	{
		return $this->StaffRoleID;
	}
	
	public function SetRoleName($RoleName)
	{
		$this->RoleName = $RoleName;
	}
	
	public function GetRoleName()
	{
		return $this->RoleName;
	}
	
	public function SetStaffVolString($StaffVolString)
	{
		$this->StaffVolString = $StaffVolString;
	}
	
	public function GetStaffVolString()
	{
		return $this->StaffVolString;
	}
		
	function __construct() {
		//empty constructor that may need to be altered later
	}
   	
   	public static function LoadStaffRole($StaffRoleID)
	{
		$StaffRoleID = intval($StaffRoleID);
		
		// Insert code to retrieve staff member here
		$pdoRole = getUserStaffRoleByID($StaffRoleID);
		
		if($pdoRole->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrRole = $pdoRole->fetch();
			return StaffRole::ArrayItemToStaffRole($arrRole);
		}
		
	}
	
	public static function ArrayItemToStaffRole($item)
	{
		$thisRole = new StaffRole();
		
		// Details SetStaffRole
		$thisRole->SetStaffRoleID($item['id_staff_role']);
		$thisRole->SetRoleName($item['fld_role_name']);
		$thisRole->SetStaffVolString($item['fld_staff_vol']);
		
		return $thisRole;
	}
		
} // END MEMBER CLASS 


function getUserStaffRoleByID($StaffRoleID)
{
	global $dbh;
	try {
			$StaffRole = $dbh->prepare('
										SELECT *
										FROM tbl_staff_roles
										WHERE id_staff_role = :StaffRoleID
										
										');
			$StaffRole->execute(array(
										':StaffRoleID' => $StaffRoleID
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $StaffRole;
}


function getNewCommittedMembersByRegionByGroupAttendanceBetweenDates($RegionID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	try {
		
		$Members = $dbh->prepare('SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
												AND fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
												AND EXISTS(
													SELECT *
													FROM tbl_groups_regions
													WHERE tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
													AND tbl_groups_regions.fld_region_id = :RegionID
													AND (tbl_groups_regions.fld_start_date <= :EndDate2
														AND (tbl_groups_regions.fld_end_date IS NULL
															OR tbl_groups_regions.fld_end_date >= :StartDate2)
														)
												)
											)
								AND EXISTS(
											SELECT *
											FROM tbl_member_committed_dates
											WHERE tbl_member_committed_dates.fld_user_id = tbl_users.id_user
											AND fld_start_date <= :EndDate3
											AND (fld_end_date IS NULL 
												OR fld_end_date >= :StartDate3
												)
											AND tbl_member_committed_dates.fld_deleted = :false
											)
								
								
								');
		$Members->execute(array(
						':StartDate' => $StartDate,
						':EndDate' => $EndDate,
						':RegionID' => $RegionID,
						':EndDate2' => $EndDate,
						':StartDate2' => $StartDate,
						':EndDate3' => $EndDate,
						':StartDate3' => $StartDate,
						':false' => $false
						));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getNewCommittedMembersByGroupAttendanceBetweenDates($GroupID,$StartDate,$EndDate)
{
	global $dbh;
	global $false;
	
	try {
		
		$Members = $dbh->prepare('SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
												AND fld_group_id = :GroupID
												AND fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
											)
								AND EXISTS(
											SELECT *
											FROM tbl_member_committed_dates
											WHERE tbl_member_committed_dates.fld_user_id = tbl_users.id_user
											AND fld_start_date <= :EndDate2
											AND (fld_end_date IS NULL 
												OR fld_end_date >= :StartDate2
												)
											AND tbl_member_committed_dates.fld_deleted = :false
											)
								
								');
		$Members->execute(array(
						':GroupID' => $GroupID,
						':StartDate' => $StartDate,
						':EndDate' => $EndDate,
						':EndDate2' => $EndDate,
						':StartDate2' => $StartDate,
						':false' => $false
						));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getMemberByRegionAttendanceBetweenDates($RegionID,$StartDate,$EndDate,$LimitToCommitted)
{
	global $dbh;
	
	if ( $LimitToCommitted )
	{
		$this_query = 'SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
												AND fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
												AND EXISTS(
													SELECT *
													FROM tbl_groups_regions
													WHERE tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
													AND tbl_groups_regions.fld_region_id = :RegionID
													AND (tbl_groups_regions.fld_start_date <= :EndDate2
														AND (tbl_groups_regions.fld_end_date IS NULL
															OR tbl_groups_regions.fld_end_date >= :StartDate2)
														)
												)
											)
								AND tbl_members.fld_committed_date <= :EndDate3
								
								';
		$this_array = array(
						':StartDate' => $StartDate,
						':EndDate' => $EndDate,
						':RegionID' => $RegionID,
						':EndDate2' => $EndDate,
						':StartDate2' => $StartDate,
						':EndDate3' => $EndDate
						);
	} else {
		$this_query = 'SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
												AND fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
												AND EXISTS(
													SELECT *
													FROM tbl_groups_regions
													WHERE tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
													AND tbl_groups_regions.fld_region_id = :RegionID
													AND (tbl_groups_regions.fld_start_date <= :EndDate2
														AND (tbl_groups_regions.fld_end_date IS NULL
															OR tbl_groups_regions.fld_end_date >= :StartDate2)
														)
												)
											)
								';
		$this_array = array(	
						':StartDate' => $StartDate,
						':EndDate' => $EndDate,
						':RegionID' => $RegionID,
						':EndDate2' => $EndDate,
						':StartDate2' => $StartDate
						);
	}
	
	
	try {
		
		$Members = $dbh->prepare($this_query);
		$Members->execute($this_array);
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getCountCommittedMembersAttendancesByGroupBetweenDates($GroupID,$StartDate,$EndDate)
{
	global $dbh;
	try {
		$qryCount = $dbh->prepare('
									SELECT COUNT(DISTINCT tbl_group_attendance.id_attendance) AS Total
									FROM tbl_members
									JOIN tbl_user_activity_dates
									ON tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id
									JOIN tbl_group_attendance
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									JOIN tbl_member_committed_dates
									ON tbl_members.fld_user_id = tbl_member_committed_dates.fld_user_id
									WHERE tbl_user_activity_dates.fld_start_date <= :EndDate
									AND ( 
													tbl_user_activity_dates.fld_end_date IS NULL OR
													tbl_user_activity_dates.fld_end_date >= :StartDate
													)
									AND tbl_user_activity_dates.fld_user_type_string = :MemberString
									AND tbl_user_activity_dates.fld_deleted = 0
									AND tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
									
									AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date 
									AND (tbl_user_activity_dates.fld_end_date IS NULL OR 
										tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date)
									AND tbl_member_committed_dates.fld_start_date <= :EndDate3
											AND (tbl_member_committed_dates.fld_end_date IS NULL 
												OR tbl_member_committed_dates.fld_end_date >= :StartDate3
												)
											AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_group_attendance.fld_date >= tbl_member_committed_dates.fld_start_date 
									AND (tbl_member_committed_dates.fld_end_date IS NULL OR 
										tbl_group_attendance.fld_date <= tbl_member_committed_dates.fld_end_date);
									');
		$qryCount->execute(array(
								 ':EndDate' => $EndDate, 
								 ':StartDate' => $StartDate, 
								 ':MemberString' => MEMBER_STRING, 
								 ':GroupID' => $GroupID, 
								 ':StartDate2' => $StartDate,
								 ':EndDate2' => $EndDate,
								 ':EndDate3' => $EndDate,
								 ':StartDate3' => $StartDate,
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$ArrResults = $qryCount->fetch();
	
	return $ArrResults['Total'];
}

function getCountCommittedMembersByGroupBetweenDates($GroupID,$StartDate,$EndDate)
{
	global $dbh;
	try {
		$qryCount = $dbh->prepare('
									SELECT COUNT(DISTINCT tbl_members.fld_user_id) AS Total
									FROM tbl_members
									JOIN tbl_user_activity_dates
									ON tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id
									JOIN tbl_group_attendance
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									JOIN tbl_member_committed_dates
									ON tbl_members.fld_user_id = tbl_member_committed_dates.fld_user_id
									WHERE tbl_user_activity_dates.fld_start_date <= :EndDate
									AND ( 
													tbl_user_activity_dates.fld_end_date IS NULL OR
													tbl_user_activity_dates.fld_end_date >= :StartDate
													)
									AND tbl_user_activity_dates.fld_user_type_string = :MemberString
									AND tbl_user_activity_dates.fld_deleted = 0
									AND tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
									
									AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date 
									AND (tbl_user_activity_dates.fld_end_date IS NULL OR 
										tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date)
									AND tbl_member_committed_dates.fld_start_date <= :EndDate3
											AND (tbl_member_committed_dates.fld_end_date IS NULL 
												OR tbl_member_committed_dates.fld_end_date >= :StartDate3
												)
											AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_group_attendance.fld_date >= tbl_member_committed_dates.fld_start_date 
									AND (tbl_member_committed_dates.fld_end_date IS NULL OR 
										tbl_group_attendance.fld_date <= tbl_member_committed_dates.fld_end_date);
									');
		$qryCount->execute(array(
								 ':EndDate' => $EndDate, 
								 ':StartDate' => $StartDate, 
								 ':MemberString' => MEMBER_STRING, 
								 ':GroupID' => $GroupID, 
								 ':StartDate2' => $StartDate,
								 ':EndDate2' => $EndDate,
								 ':EndDate3' => $EndDate,
								 ':StartDate3' => $StartDate,
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$ArrResults = $qryCount->fetch();
	
	return $ArrResults['Total'];
}

function getMemberByGroupAttendanceBetweenDates($GroupID,$StartDate,$EndDate,$LimitToCommitted)
{
	global $dbh;
	global $false;
	
	if ( $LimitToCommitted )
	{
		$this_query = 'SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
												AND fld_group_id = :GroupID
												AND fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
											)
								AND EXISTS(
											SELECT *
											FROM tbl_member_committed_dates
											WHERE tbl_member_committed_dates.fld_user_id = tbl_users.id_user
											AND fld_start_date <= :EndDate2
											AND (fld_end_date IS NULL 
												OR fld_end_date >= :StartDate2
												)
											AND tbl_member_committed_dates.fld_deleted = :false
											)
								
								';
		$this_array = array(
						':GroupID' => $GroupID,
						':StartDate' => $StartDate,
						':EndDate' => $EndDate,
						':EndDate2' => $EndDate,
						':StartDate2' => $StartDate,
						':false' => $false
						);
	} else {
		$this_query = 'SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
												AND fld_group_id = :GroupID
												AND fld_deleted = 0
												AND fld_date BETWEEN :StartDate AND :EndDate
											)';
		$this_array = array(	
						':GroupID' => $GroupID,
						':StartDate' => $StartDate,
						':EndDate' => $EndDate
						);
	}
	
	
	try {
		
		$Members = $dbh->prepare($this_query);
		$Members->execute($this_array);
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function updCommitted($UserID,$Committed) //due to committed date this can only be used to set committed
{
	global $dbh;
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_members
									SET
										fld_committed = :Committed,
										fld_committed_date = DATE(NOW())
										WHERE fld_user_id = :UserID
									');
		$qryUpdate->execute(array(
								 ':Committed' => $Committed, 
								 ':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function getUserPages($UserID)
{
	global $dbh;
	try {
			$UserPages = $dbh->prepare('SELECT tbl_pages.* 
										FROM tbl_pages 
										JOIN tbl_user_type_pages
										ON tbl_pages.id_page = tbl_user_type_pages.fld_page_id
										JOIN tbl_users
										ON tbl_user_type_pages.fld_user_type_id = tbl_users.fld_user_type_id
										WHERE tbl_users.id_user = :UserID');
			$UserPages->execute(array(':UserID' => $UserID ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserPages;
}

function getUserTypeNavPages($UserTypeID)
{
	global $dbh;
	
	$strQuery = 'select id_page, fld_page_name, fld_menu_category_name, fld_sub_menu_cat_name
					from tbl_menu_categories 
					join tbl_pages
					on tbl_menu_categories.id_menu_category = tbl_pages.fld_menu_category_id
					join tbl_user_type_pages
					on tbl_pages.id_page = tbl_user_type_pages.fld_page_id
					join tbl_user_types
					on tbl_user_type_pages.fld_user_type_id = tbl_user_types.id_user_type
					where tbl_user_types.id_user_type = :UserTypeID
					order by tbl_menu_categories.fld_menu_order ASC, tbl_menu_categories.fld_sub_menu_cat_name ASC, tbl_pages.fld_menu_item_order ASC;';
	
		try {
		
			$Nav = $dbh->prepare($strQuery);
			$Nav->execute(array(':UserTypeID' => $UserTypeID
								 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Nav;
	
}

function ValidateUser($UserName,$Password,&$arrErrors)
{
	//returns user object or null
		
		$DefaultErrorMessage = ' User Name and Password combination';
		$StaffEndDateExpired = ' Staff Login, your end date has been reached';
		//collect the salt
		$Salt = getSaltByUserName($UserName);
		
		
		//fail if not exactly one record
		if( $Salt->rowCount() != 1)
		{
			$arrErrors['Bad'] = $DefaultErrorMessage;
			addLoginRecord($UserName,'failure','Bad user name!',$_SERVER["REMOTE_ADDR"]);
			return NULL;
		} else {
			//validate username and password
			//fetch the salt
			$arrRowSalt = $Salt->fetch();
			
			//create the hash
			$HashedPassword = hash('sha256',$arrRowSalt['fld_salt'].$Password);
			
			$User = getUserByHashedPasswordAndUserName($HashedPassword,$UserName);
			
			//capture user details
			if($User->rowCount() != 1)
			{
				$arrErrors['Bad'] = $DefaultErrorMessage;
				addLoginRecord($UserName,'failure','Bad password!',$_SERVER["REMOTE_ADDR"]);
				return NULL;
			} else {
				//fetch row
				$arrUser = $User->fetch();
				
				$ThisUser = User::LoadUser($arrUser['id_user'],true,false);
				
				if( $ThisUser->GetUserTypeCategory() == 'staff' )
				{
					//validate staff login
					$pdoStaff = validateStaffLogin($ThisUser->GetUserID());
					
					if( $pdoStaff->rowCount() != 1 )
					{
						$arrErrors['Bad'] = $StaffEndDateExpired;
						addLoginRecord($UserName,'failure','Staff End Date Reached!',$_SERVER["REMOTE_ADDR"]);
						return NULL;
					}
				}
								
				addLoginRecord($UserName,'success','',$_SERVER["REMOTE_ADDR"],$arrUser['id_user']);
				
				return $ThisUser;
				
			}
	}
	
}  

function getUserTypeByUserTypeName($UserTypeName)
{
	global $dbh;
	try {
			$UserType = $dbh->prepare('SELECT * FROM tbl_user_types WHERE fld_user_type = :UserTypeName');
			$UserType->execute(array(':UserTypeName' => $UserTypeName ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserType;
}

function getUserTypeByUserTypeID($UserTypeID)
{
	global $dbh;
	try {
			$UserType = $dbh->prepare('SELECT * FROM tbl_user_types WHERE id_user_type = :UserTypeID');
			$UserType->execute(array(':UserTypeID' => $UserTypeID ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserType;
}

function SetLoggedInUser($User)
{
	//
	$_SESSION['loggedIn'] = true;
	$_SESSION['User'] = $User;
	
	addLoginRecord($User->GetUserName(),'success','',$_SERVER["REMOTE_ADDR"],$User->GetUserID());
}


//collect salt
function getSaltByUserName($strUName)
{
	global $dbh;
	try {
			$Salt = $dbh->prepare('SELECT fld_salt FROM tbl_users WHERE fld_username = :UName');
			$Salt->execute(array(':UName' => $strUName ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Salt;
}

function getUserByHashedPasswordAndUserName($HashedPassword,$UserName)
{
	global $dbh;

	try {
			$User = $dbh->prepare('SELECT id_user 
									FROM tbl_users 
									WHERE fld_password = :PWord 
									AND fld_username = :UName');
			$User->execute(array(':PWord' => $HashedPassword,
							 ':UName' => $UserName ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $User;
}

function addEmptyUser() //Add empty user for creating staff
{
	global $dbh;
	
	try {
		$qryInsert = $dbh->query('INSERT INTO tbl_users()
								  VALUES(); ');
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
}

function getArchivedStaff()
{
	global $StaffVolunteer;
	global $dbh;
	//no need to check user type due to need for explicit connection between users and staff
	//second exists just checks if they have ever been a staff member
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND NOT EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles 
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'staff'
											AND fld_user_type_string = :StaffVolunteer
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
											)
								AND EXISTS (
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles 
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'staff'
											AND fld_user_type_string = :StaffVolunteer2
											AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
											)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(
								':StaffVolunteer' => $StaffVolunteer,
								':StaffVolunteer2' => $StaffVolunteer
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;

}

function getActiveStaff()
{
	global $dbh;
	global $StaffVolunteer;
	//no need to check user type due to need for explicit connection between users and staff
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.fld_deleted = 0
								AND tbl_staff.fld_deleted = 0
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles 
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'staff'
											AND fld_user_type_string = :StaffVolunteer
											AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
												)
											)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC;
								");
		$Staff->execute(array(':StaffVolunteer' => $StaffVolunteer ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function addMember(
				$NewUserID,$FirstName,$Lastname,$Gender,$CommittedDate,$StartDate,$EndDate
				)
{
	global $dbh;
	
	if( trim($CommittedDate) == 'null' )
	{
		$Committed = 0;
	} else {
		$Committed = 1;
	}
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_members(fld_user_id,fld_first_name,fld_last_name,fld_gender,fld_committed_date,fld_committed
															) 
								  VALUES (:NewUserID,:FirstName,:Lastname,:Gender,:CommittedDate,:Committed
											) ');
		$qryInsert->execute(array(':NewUserID' => $NewUserID,
								 ':FirstName' => $FirstName, 
								 ':Lastname' => $Lastname,
								 ':Gender' => $Gender,
								 ':CommittedDate' => ($CommittedDate == 'null') ? null : $CommittedDate,
								 ':Committed' => $Committed
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
}

function addStaff(
				$NewUserID,$FirstName,$Lastname,$Gender,$BirthDate,$Address,$Suburb,$PostCode,
				$StateID,$BranchID,$WorkEmail,$PersonalEmail,$WorkMobile,$PersonalMobile,
				$HomePhone,$Notes,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConStateID,$EmConPostCode,
				$EmConMobile,$EmConHomePhone
				)
{
	global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_staff(fld_user_id,fld_first_name,fld_last_name,fld_gender,fld_birth_date,fld_address,
															fld_suburb,fld_postcode,fld_state_id,fld_branch_id,
															fld_work_email,fld_personal_email,fld_work_mobile,fld_personal_mobile,fld_home_phone,
															fld_em_con_first_name,fld_em_con_last_name,fld_em_con_address,fld_em_con_suburb,
															fld_em_con_state_id,fld_em_con_postcode,fld_em_con_mobile,fld_em_con_home_phone,
															fld_notes
															) 
								  VALUES (:NewUserID,:FirstName,:Lastname,:Gender,:BirthDate,:Address,:Suburb,:PostCode,
								  			:StateID,:BranchID,:WorkEmail,:PersonalEmail,:WorkMobile,
											:PersonalMobile,:HomePhone,:EmConFName,:EmConLName,:EmConAddress,:EmConSuburb,:EmConStateID,
											:EmConPostCode,:EmConMobile,:EmConHomePhone,:Notes
											) ');
		$qryInsert->execute(array(':NewUserID' => $NewUserID,
								 ':FirstName' => $FirstName, 
								 ':Lastname' => $Lastname,
								 ':Gender' => $Gender, 
								 ':BirthDate' => ($BirthDate == 'null') ? null : $BirthDate,
								 ':Address' => $Address, 
								 ':Suburb' => $Suburb, 
								 ':PostCode' => $PostCode,
								 ':StateID' => $StateID,
								 ':BranchID' => $BranchID,
								 ':WorkEmail' => $WorkEmail, 
								 ':PersonalEmail' => $PersonalEmail,
								 ':WorkMobile' => $WorkMobile,
								 ':PersonalMobile' => $PersonalMobile,
								 ':HomePhone' => $HomePhone, 
								 ':EmConFName' => $EmConFName, 
								 ':EmConLName' => $EmConLName,
								 ':EmConAddress' => $EmConAddress,
								 ':EmConSuburb' => $EmConSuburb,
								 ':EmConStateID' => $EmConStateID, 
								 ':EmConPostCode' => $EmConPostCode, 
								 ':EmConMobile' => $EmConMobile,
								 ':EmConHomePhone' => $EmConHomePhone,
								 ':Notes' => $Notes
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
}

function updMember(
				$UserID,$FirstName,$Lastname,$Gender,$CommittedDate,$Committed
				)
{
	global $dbh;
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_members
									SET
										fld_first_name = :FirstName,
										fld_last_name = :Lastname,
										fld_gender = :Gender,
										fld_committed_date = :CommittedDate,
										fld_committed = :Committed
										WHERE fld_user_id = :UserID
									');
		$qryUpdate->execute(array(
								 ':FirstName' => $FirstName, 
								 ':Lastname' => $Lastname, 
								 ':Gender' => $Gender,
								 ':CommittedDate' => ($CommittedDate == 'null') ? null : $CommittedDate,
								 ':Committed' => $Committed,
								 ':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function updStaff(
				$UserID,$FirstName,$Lastname,$Gender,$BirthDate,$Address,$Suburb,$PostCode,
				$StateID,$BranchID,$WorkEmail,$PersonalEmail,$WorkMobile,$PersonalMobile,
				$HomePhone,$Notes,$EmConFName,$EmConLName,$EmConAddress,$EmConSuburb,$EmConStateID,$EmConPostCode,
				$EmConMobile,$EmConHomePhone
				)
{
	global $dbh;
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_staff
									SET
										fld_first_name = :FirstName,
										fld_last_name = :Lastname,
										fld_birth_date = :BirthDate,
										fld_gender = :Gender,
										fld_address = :Address,
										fld_suburb = :Suburb,
										fld_postcode = :PostCode,
										fld_state_id = :StateID,
										fld_branch_id = :BranchID,
										fld_work_email = :WorkEmail,
										fld_personal_email = :PersonalEmail,
										fld_work_mobile = :WorkMobile,
										fld_personal_mobile = :PersonalMobile,
										fld_home_phone = :HomePhone,
										fld_em_con_first_name = :EmConFName,
										fld_em_con_last_name = :EmConLName,
										fld_em_con_address = :EmConAddress,
										fld_em_con_suburb = :EmConSuburb,
										fld_em_con_state_id = :EmConStateID,
										fld_em_con_postcode = :EmConPostCode,
										fld_em_con_mobile = :EmConMobile,
										fld_em_con_home_phone = :EmConHomePhone,
										fld_notes = :Notes
										WHERE fld_user_id = :UserID
									');
		$qryUpdate->execute(array(
								 ':FirstName' => $FirstName, 
								 ':Lastname' => $Lastname, 
								 ':BirthDate' => ($BirthDate == 'null') ? null : $BirthDate,
								 ':Gender' => $Gender,
								 ':Address' => $Address, 
								 ':Suburb' => $Suburb, 
								 ':PostCode' => $PostCode,
								 ':StateID' => $StateID,
								 ':BranchID' => $BranchID,
								 ':WorkEmail' => $WorkEmail, 
								 ':PersonalEmail' => $PersonalEmail,
								 ':WorkMobile' => $WorkMobile,
								 ':PersonalMobile' => $PersonalMobile,
								 ':HomePhone' => $HomePhone, 
								 ':EmConFName' => $EmConFName, 
								 ':EmConLName' => $EmConLName,
								 ':EmConAddress' => $EmConAddress,
								 ':EmConSuburb' => $EmConSuburb,
								 ':EmConStateID' => $EmConStateID, 
								 ':EmConPostCode' => $EmConPostCode, 
								 ':EmConMobile' => $EmConMobile,
								 ':EmConHomePhone' => $EmConHomePhone,
								 ':Notes' => $Notes,
								 ':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

//needs to be sent the correct user type id
function addUser($UserName,$Password,$Salt,$ScreenName,$Email,$UserTypeID)
{
	global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_users(fld_user_type_id,fld_username,fld_password,fld_salt,fld_screen_name,fld_email_address,fld_deleted) 
								  VALUES (:UserType,:UserName,:Password,:Salt,:ScreenName,:EmailAddress,0) ');
		$qryInsert->execute(array(':UserType' => $UserTypeID,
								 ':UserName' => $UserName, 
								 ':Password' => $Password, 
								 ':Salt' => $Salt,
								 ':ScreenName' => $ScreenName,
								 ':EmailAddress' => $Email
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
}

function addStaffLogin($UserID,$UserName,$HashedPassword,$Salt,$ScreenName,$UserTypeID)
{
	global $dbh;
	
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_users
									SET fld_username = :UserName,
									fld_password = :HashedPassword,
									fld_salt = :Salt,
									fld_screen_name = :ScreenName,
									fld_user_type_id = :UserTypeID
									WHERE id_user = :UserID
									');
		$qryUpdate->execute(array(
									':UserName' => $UserName,
								 	':HashedPassword' => $HashedPassword,
								 	':Salt' => $Salt,
								 	':ScreenName' => $ScreenName,
								 	':UserTypeID' => $UserTypeID,
								 	':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function updUser($UserID,$ScreenName,$Email)
{
	global $dbh;
	
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_users
									SET fld_screen_name = :ScreenName,
									fld_email_address = :EmailAddress
									WHERE id_user = :UserID
									');
		$qryUpdate->execute(array(':ScreenName' => $ScreenName,
								 ':EmailAddress' => $Email, 
								 ':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
}

function updUserSafe($UserID,$UserName,$HashedPassword,$ScreenName,$EmailAddress,$UserTypeID)
{
	global $dbh;
	
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_users
									SET fld_username = :UserName,
									fld_password = :HashedPassword,
									fld_screen_name = :ScreenName,
									fld_email_address = :EmailAddress,
									fld_user_type_id = :UserTypeID
									WHERE id_user = :UserID
									');
		$qryUpdate->execute(array(
									':UserName' => $UserName,
								 	':HashedPassword' => $HashedPassword,
								 	':ScreenName' => $ScreenName,
								 	':EmailAddress' => $EmailAddress,
								 	':UserTypeID' => $UserTypeID, 
								 	':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function updUserType($UserID,$UserTypeID)
{
	global $dbh;
	
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_users
									SET fld_user_type_id = :UserTypeID
									WHERE id_user = :UserID
									');
		$qryUpdate->execute(array(':UserTypeID' => $UserTypeID,
								 ':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function updUserLogin($UserID,$UserName,$HashedPassword)
{
	global $dbh;
	
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_users
									SET fld_username = :UserName,
									fld_password = :HashedPassword
									WHERE id_user = :UserID
									');
		$qryUpdate->execute(array(':UserName' => $UserName,
								 ':HashedPassword' => $HashedPassword, 
								 ':UserID' => $UserID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
}

//adds a login record to the login records table
function addLoginRecord($user_name,$success,$reason_for_failure,$ip,$user_id = 0)
{
	global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_login_records(fld_user_name,fld_login_date,fld_success,fld_reason_for_failure,fld_ip,fld_user_id) 
								  VALUES (:user_name,NOW(),:success,:reason_for_failure,:ip,:user_id) ');
		$qryInsert->execute(array(':user_name' => $user_name,
								 ':success' => $success, 
								 ':reason_for_failure' => $reason_for_failure, 
								 ':ip' => $ip,
								 ':user_id' => $user_id
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function getStaffVolDuePoliceChecks($months_till_due,$user_type_cat)
{
	global $dbh;
	global $PoliceCheck;
	
	try {
		$Members = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_users.id_user
											AND fld_deleted = 0
											AND fld_start_date <= DATE(NOW())
											AND (
												fld_end_date IS NULL 
												OR fld_end_date >= DATE(NOW())
												)
											AND tbl_staff_roles.fld_staff_vol = :user_type_cat
											)
								AND NOT EXISTS	(
												SELECT MAX(fld_date) AS last_police_check
												FROM tbl_reminder_dates
												WHERE tbl_reminder_dates.fld_user_id = tbl_users.id_user
												AND fld_deleted = 0
												AND fld_type_id = :PoliceCheck
												HAVING TIMESTAMPDIFF(MONTH, MAX(fld_date), DATE(NOW())) <= :months_till_due
												
												)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC
								");
		$Members->execute(array(	
								':user_type_cat' => $user_type_cat,
								':PoliceCheck' => $PoliceCheck,
								':months_till_due' => $months_till_due
								));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getStaffBySearchStringForAttendance($SearchString,$GroupID,$GroupDate)
{
	global $dbh;
	
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE (
									fld_first_name LIKE concat('%', :Search1, '%') 
									OR fld_last_name LIKE concat('%', :Search2, '%')
									OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%')
									)
								
								AND NOT EXISTS(
												SELECT * FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_users.id_user
												AND tbl_group_attendance.fld_group_id = :GroupID
												AND tbl_group_attendance.fld_date = :GroupDate
												)
								AND EXISTS( SELECT *
											FROM tbl_user_activity_dates
											WHERE tbl_users.id_user = tbl_user_activity_dates.fld_user_id
											AND fld_start_date <= :GroupDate2
											AND (
												fld_end_date IS NULL 
												OR fld_end_date >= :GroupDate3
												)
											AND fld_deleted = 0
											)
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC
								");
		$Staff->execute(array(	
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID' => $GroupID,
								':GroupDate' => $GroupDate,
								':GroupDate2' => $GroupDate,
								':GroupDate3' => $GroupDate
								));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
} 

function getStaffBySearchString($SearchString)
{
	global $dbh;
	
	try {
		$Members = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE (fld_first_name LIKE concat('%', :Search1, '%') OR fld_last_name LIKE concat('%', :Search2, '%'))
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC
								");
		$Members->execute(array(	
								':Search1' => $SearchString,
								':Search2' => $SearchString
								));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
} 

function getVolunteersBySearchStringOptimised($SearchString,$RecordLimit,$Extra) //this may need to be changed to capture anyone who has ever been a volunteer or maybe feed it a date.
{
	global $dbh;
	global $StateUser;
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$staff_id = intval($_SESSION['User']->GetUserID());
		
		$LimitToState = "AND tbl_staff.fld_branch_id IN(
																SELECT fld_branch_id
																FROM tbl_state_users_state_activity_dates
																WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																	OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																	)
																AND fld_user_id = ".$staff_id."
																)";
		
	} else {
		$LimitToState = '';
	}
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':limit' => $RecordLimit
								);
	}
	
	if( $Extra == 'all' )
	{
		$VolExtra = "AND EXISTS(
									SELECT *
									FROM tbl_user_activity_dates
									JOIN tbl_staff_roles
									ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
									WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
									AND tbl_staff_roles.fld_staff_vol = 'volunteer'
									AND tbl_user_activity_dates.fld_deleted = 0
								   )";
	} elseif( $Extra == 'current' )
	{
		$VolExtra = "AND EXISTS(
									SELECT *
									FROM tbl_user_activity_dates
									JOIN tbl_staff_roles
									ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
									WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
									AND tbl_staff_roles.fld_staff_vol = 'volunteer'
									AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
										AND (tbl_user_activity_dates.fld_end_date IS NULL 
											OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
											)
										)
									AND tbl_user_activity_dates.fld_deleted = 0
								   )";
	} elseif( $Extra == 'archive' )
	{
		$VolExtra = "	AND NOT EXISTS 
						(	SELECT *
							FROM tbl_user_activity_dates
							JOIN tbl_staff_roles
							ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
							WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
							AND tbl_staff_roles.fld_staff_vol = 'volunteer'
							AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
								AND (tbl_user_activity_dates.fld_end_date IS NULL 
									OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
									)
								)
							AND tbl_user_activity_dates.fld_deleted = 0
						)
						AND EXISTS
						(
							SELECT *
									FROM tbl_user_activity_dates
									JOIN tbl_staff_roles
									ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
									WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
									AND tbl_staff_roles.fld_staff_vol = 'volunteer'
									AND tbl_user_activity_dates.fld_end_date <= DATE(NOW()) 
									AND tbl_user_activity_dates.fld_deleted = 0
						)
						";
	} else {
		$VolExtra = "AND EXISTS(
									SELECT *
									FROM tbl_user_activity_dates
									JOIN tbl_staff_roles
									ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
									WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
									AND tbl_staff_roles.fld_staff_vol = 'volunteer'
									AND tbl_user_activity_dates.fld_deleted = 0
								   )";
	}
	
	try {
		$Volunteers = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE 	(fld_first_name LIKE concat('%', :Search1, '%') 
										OR fld_last_name LIKE concat('%', :Search2, '%')
										OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%'))
								".$VolExtra."
								AND NOT EXISTS(
									SELECT *
									FROM tbl_user_activity_dates
									JOIN tbl_staff_roles
									ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
									WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
									AND tbl_staff_roles.fld_staff_vol = 'staff'
									AND tbl_user_activity_dates.fld_deleted = 0
								)
								".$LimitToState."
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC
								".$LimitToo."
								");
		$Volunteers->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Volunteers;
}

function getStaffBySearchStringOptimised($SearchString,$RecordLimit,$Extra)
{
	global $dbh;
	global $CommunityObserver;
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':limit' => $RecordLimit
								);
	}
	
	if( $Extra == 'all' )
	{
		$StaffExtra = "	AND EXISTS 
						(SELECT *
						FROM tbl_user_activity_dates
						JOIN tbl_staff_roles
						ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
						WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
						AND tbl_staff_roles.fld_staff_vol = 'staff'
						AND tbl_user_activity_dates.fld_deleted = 0
						)";
	} elseif( $Extra == 'current' )
	{
		$StaffExtra = "	AND EXISTS 
						(SELECT *
						FROM tbl_user_activity_dates
						JOIN tbl_staff_roles
						ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
						WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
						AND tbl_staff_roles.fld_staff_vol = 'staff'
						AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
						AND (tbl_user_activity_dates.fld_end_date IS NULL
							OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) )
						AND tbl_user_activity_dates.fld_deleted = 0
						)";
	} elseif( $Extra == 'archive' )
	{
		$StaffExtra = "	AND NOT EXISTS 
						(	SELECT *
							FROM tbl_user_activity_dates
							JOIN tbl_staff_roles
							ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
							WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
							AND tbl_staff_roles.fld_staff_vol = 'staff'
							AND tbl_user_activity_dates.fld_start_date <= DATE(NOW())
							AND (tbl_user_activity_dates.fld_end_date IS NULL
								OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) )
							AND tbl_user_activity_dates.fld_deleted = 0
						)
						AND EXISTS
						(
							SELECT *
							FROM tbl_user_activity_dates
							JOIN tbl_staff_roles
							ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
							WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
							AND tbl_staff_roles.fld_staff_vol = 'staff'
							AND tbl_user_activity_dates.fld_end_date <= DATE(NOW())
							AND tbl_user_activity_dates.fld_deleted = 0
						)
						";
	} else {
		$StaffExtra = ''; //bad extra value is ignored
	}
	
	try {
		$Members = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE 	(fld_first_name LIKE concat('%', :Search1, '%') 
										OR fld_last_name LIKE concat('%', :Search2, '%')
										OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%'))
								".$StaffExtra."
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC
								".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
} 

function getMembersBySearchStringOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID' => $GroupID,
								':Date5' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date6' => $Date,
								':Date7' => $Date
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID' => $GroupID,
								':Date5' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date6' => $Date,
								':Date7' => $Date,
								':limit' => $RecordLimit
								);
	}
	
	
	try {
		$Members = $dbh->prepare("SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name,
										(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
										WHERE tbl_member_committed_dates.fld_user_id = a.id_user
										AND fld_start_date <= :Date
										AND (fld_end_date IS NULL
											OR fld_end_date >= :Date2)
										AND tbl_member_committed_dates.fld_deleted = 0
										 )  AS is_committed,
									 (SELECT tbl_group_attendance.fld_date
										FROM tbl_group_attendance
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date3)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS last_attended, 
										(SELECT tbl_groups.fld_group_name AS last_attended
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date4)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS group_name
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									WHERE (
											fld_first_name LIKE concat('%', :Search1, '%') 
											OR fld_last_name LIKE concat('%', :Search2, '%')
											OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%')
											)
									AND NOT EXISTS(
													SELECT *
													FROM tbl_group_attendance
													WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
													AND tbl_group_attendance.fld_group_id = :GroupID
													AND tbl_group_attendance.fld_date = :Date5
													)
									AND EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												WHERE tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id	
												AND tbl_user_activity_dates.fld_deleted = 0
												AND fld_user_type_string = :MemberString
												AND fld_start_date <= :Date6
												AND (fld_end_date IS NULL	
													OR fld_end_date >= :Date7
													)
												)
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
} 

function getMembersBySearchStringOptimised($SearchString,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':limit' => $RecordLimit
								);
	}
	
	
	try {
		$Members = $dbh->prepare("SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name,
									(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
									WHERE tbl_member_committed_dates.fld_user_id = a.id_user
									AND fld_start_date <= :Date
									AND (fld_end_date IS NULL
									 	OR fld_end_date >= :Date2)
									AND tbl_member_committed_dates.fld_deleted = 0
									 ) AS is_committed,
									 (SELECT tbl_group_attendance.fld_date
										FROM tbl_group_attendance
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date3)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS last_attended, 
										(SELECT tbl_groups.fld_group_name AS last_attended
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date4)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS fld_group_name
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									WHERE (
											fld_first_name LIKE concat('%', :Search1, '%') 
											OR fld_last_name LIKE concat('%', :Search2, '%')
											OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%')
											)
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
} 

function getMembersBySearchStringStateOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID' => $GroupID,
								':Date5' => $Date,
								':Date6' => $Date,
								':GroupID2' => $GroupID,
								':Date7' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date8' => $Date,
								':Date9' => $Date
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID' => $GroupID,
								':Date5' => $Date,
								':Date6' => $Date,
								':GroupID2' => $GroupID,
								':Date7' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date8' => $Date,
								':Date9' => $Date,
								':limit' => $RecordLimit
								);
	}
	
	
	try {
		$Members = $dbh->prepare("SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name,
										(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
										WHERE tbl_member_committed_dates.fld_user_id = a.id_user
										AND fld_start_date <= :Date
										AND (fld_end_date IS NULL
											OR fld_end_date >= :Date2)
										AND tbl_member_committed_dates.fld_deleted = 0
										 ) AS is_committed,
									 (SELECT tbl_group_attendance.fld_date
										FROM tbl_group_attendance
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date3)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS last_attended, 
										(SELECT tbl_groups.fld_group_name AS last_attended
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date4)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS group_name
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									WHERE (
											fld_first_name LIKE concat('%', :Search1, '%') 
											OR fld_last_name LIKE concat('%', :Search2, '%')
											OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%')
											)
									AND (EXISTS (
												SELECT tbl_regions.fld_branch_id
												FROM tbl_regions
												JOIN tbl_groups_regions
												ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
												WHERE tbl_groups_regions.fld_group_id = :GroupID
												AND tbl_groups_regions.fld_start_date <= :Date5
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :Date6)
												)
										OR NOT EXISTS(	
												SELECT *
												FROM tbl_group_attendance
												WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
												)	
										)
									AND NOT EXISTS(
													SELECT *
													FROM tbl_group_attendance
													WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
													AND tbl_group_attendance.fld_group_id = :GroupID2
													AND tbl_group_attendance.fld_date = :Date7
													)
									AND EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												WHERE tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id	
												AND tbl_user_activity_dates.fld_deleted = 0
												AND fld_user_type_string = :MemberString
												AND fld_start_date <= :Date8
												AND (fld_end_date IS NULL	
													OR fld_end_date >= :Date9
													)
												)
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getMembersBySearchStringStateOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	global $CommunityObserver;
	
	$Hide_ComObs = ( $Hide_CO ? "AND fld_first_name != '".$CommunityObserver."'" : '');
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':GroupID' => $GroupID,
								':Date6' => $Date,
								':Date7' => $Date
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':GroupID' => $GroupID,
								':Date6' => $Date,
								':Date7' => $Date,
								':limit' => $RecordLimit
								);
	}
	
	
	try {
		$Members = $dbh->prepare("SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name, c.last_attended, c.fld_group_name,
									(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
									WHERE tbl_member_committed_dates.fld_user_id = a.id_user
									AND fld_start_date <= :Date
									AND (fld_end_date IS NULL
									 	OR fld_end_date >= :Date2)
									AND tbl_member_committed_dates.fld_deleted = 0
									 ) AS is_committed
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									LEFT OUTER JOIN (
										SELECT MAX(tbl_group_attendance.fld_date) AS last_attended, fld_group_name, tbl_group_attendance.fld_user_id, tbl_regions.fld_branch_id
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_groups_regions
										ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
										JOIN tbl_regions
										ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
										WHERE (tbl_group_attendance.fld_deleted IS NULL OR tbl_group_attendance.fld_deleted = 0)
										AND (tbl_group_attendance.fld_date IS NULL OR tbl_group_attendance.fld_date <= :Date3)
										AND (tbl_groups_regions.fld_deleted IS NULL OR tbl_groups_regions.fld_deleted = 0)
										AND (tbl_groups_regions.fld_start_date IS NULL OR tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date)
										AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
										GROUP BY tbl_group_attendance.fld_user_id
											) b ON (b.fld_user_id = a.id_user)
									LEFT OUTER JOIN (
										SELECT MAX(tbl_group_attendance.fld_date) AS last_attended, fld_group_name, tbl_group_attendance.fld_user_id, tbl_groups.id_group
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE (tbl_group_attendance.fld_deleted IS NULL OR tbl_group_attendance.fld_deleted = 0)
										AND (tbl_group_attendance.fld_date IS NULL OR tbl_group_attendance.fld_date <= :Date4)
										GROUP BY tbl_group_attendance.fld_user_id
											) c ON (c.fld_user_id = a.id_user)
									WHERE (fld_first_name LIKE concat('%', :Search1, '%') OR fld_last_name LIKE concat('%', :Search2, '%'))
									AND (b.fld_branch_id IS NULL OR b.fld_branch_id IN(
																					SELECT tbl_regions.fld_branch_id
																					FROM tbl_regions
																					JOIN tbl_groups_regions
																					ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
																					WHERE tbl_groups_regions.fld_group_id = :GroupID
																					AND tbl_groups_regions.fld_start_date <= :Date6
																					AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :Date7)
																					))
									".$Hide_ComObs."
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getMembersBySearchStringRegionOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	global $CommunityObserver;
	
	
	$Hide_ComObs = ( $Hide_CO ? "AND fld_first_name != '".$CommunityObserver."'" : '');
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':GroupID' => $GroupID,
								':Date6' => $Date,
								':Date7' => $Date
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':GroupID' => $GroupID,
								':Date6' => $Date,
								':Date7' => $Date,
								':limit' => $RecordLimit
								);
	}
	
	try {
		$Members = $dbh->prepare("SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name, c.last_attended, c.fld_group_name,
									(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
									WHERE tbl_member_committed_dates.fld_user_id = a.id_user
									AND fld_start_date <= :Date
									AND (fld_end_date IS NULL
									 	OR fld_end_date >= :Date2)
									AND tbl_member_committed_dates.fld_deleted = 0
									 ) AS is_committed
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									LEFT OUTER JOIN (
										SELECT MAX(tbl_group_attendance.fld_date) AS last_attended, fld_group_name, tbl_group_attendance.fld_user_id, tbl_groups_regions.fld_region_id
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_groups_regions
										ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
										WHERE (tbl_group_attendance.fld_deleted IS NULL OR tbl_group_attendance.fld_deleted = 0)
										AND (tbl_group_attendance.fld_date IS NULL OR tbl_group_attendance.fld_date <= :Date3)
										AND (tbl_groups_regions.fld_deleted IS NULL OR tbl_groups_regions.fld_deleted = 0)
										AND (tbl_groups_regions.fld_start_date IS NULL OR tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date)
										AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
										GROUP BY tbl_group_attendance.fld_user_id
											) b ON (b.fld_user_id = a.id_user)
									LEFT OUTER JOIN (
										SELECT MAX(tbl_group_attendance.fld_date) AS last_attended, fld_group_name, tbl_group_attendance.fld_user_id, tbl_groups.id_group
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE (tbl_group_attendance.fld_deleted IS NULL OR tbl_group_attendance.fld_deleted = 0)
										AND (tbl_group_attendance.fld_date IS NULL OR tbl_group_attendance.fld_date <= :Date4)
										GROUP BY tbl_group_attendance.fld_user_id
											) c ON (c.fld_user_id = a.id_user)
									WHERE (fld_first_name LIKE concat('%', :Search1, '%') OR fld_last_name LIKE concat('%', :Search2, '%'))
									AND (b.fld_region_id IS NULL OR b.fld_region_id IN(
																					SELECT fld_region_id
																					FROM tbl_groups_regions
																					WHERE fld_group_id = :GroupID
																					AND tbl_groups_regions.fld_start_date <= :Date6
																					AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :Date7 )
																					))
									".$Hide_ComObs."
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getMembersBySearchStringRegionOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID' => $GroupID,
								':Date5' => $Date,
								':Date6' => $Date,
								':GroupID2' => $GroupID,
								':Date7' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date8' => $Date,
								':Date9' => $Date
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID' => $GroupID,
								':Date5' => $Date,
								':Date6' => $Date,
								':GroupID2' => $GroupID,
								':Date7' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date8' => $Date,
								':Date9' => $Date,
								':limit' => $RecordLimit
								);
	}
	
	try {
		$Members = $dbh->prepare("SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name,
										(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
										WHERE tbl_member_committed_dates.fld_user_id = a.id_user
										AND fld_start_date <= :Date
										AND (fld_end_date IS NULL
											OR fld_end_date >= :Date2)
										AND tbl_member_committed_dates.fld_deleted = 0
										 ) AS is_committed,
									 (SELECT tbl_group_attendance.fld_date
										FROM tbl_group_attendance
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date3)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS last_attended, 
										(SELECT tbl_groups.fld_group_name AS last_attended
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date4)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS group_name
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									WHERE (
											fld_first_name LIKE concat('%', :Search1, '%') 
											OR fld_last_name LIKE concat('%', :Search2, '%')
											OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%')
											)
									AND (EXISTS (
												SELECT *
												FROM tbl_groups_regions
												WHERE fld_group_id = :GroupID
												AND tbl_groups_regions.fld_start_date != :Date5
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :Date6 )
												)
										OR NOT EXISTS(	
													SELECT *
													FROM tbl_group_attendance
													WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
													)			
										)
									AND NOT EXISTS(
													SELECT *
													FROM tbl_group_attendance
													WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
													AND tbl_group_attendance.fld_group_id = :GroupID2
													AND tbl_group_attendance.fld_date = :Date7
													)
									AND EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												WHERE tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id	
												AND tbl_user_activity_dates.fld_deleted = 0
												AND fld_user_type_string = :MemberString
												AND fld_start_date <= :Date8
												AND (fld_end_date IS NULL	
													OR fld_end_date >= :Date9
													)
												)
												
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getMembersBySearchStringGroupOptimisedForAttendance($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID2' => $GroupID,
								':Date5' => $Date,
								':GroupID3' => $GroupID,
								':Date6' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date7' => $Date,
								':Date8' => $Date
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Date4' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':Search3' => $SearchString,
								':GroupID2' => $GroupID,
								':Date5' => $Date,
								':GroupID3' => $GroupID,
								':Date6' => $Date,
								':MemberString' => MEMBER_STRING,
								':Date7' => $Date,
								':Date8' => $Date,
								':limit' => $RecordLimit
								);
	}
	
	try {
		$Members = $dbh->prepare("
									SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name,
									(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
									WHERE tbl_member_committed_dates.fld_user_id = a.id_user
									AND fld_start_date <= :Date
									AND (fld_end_date IS NULL
									 	OR fld_end_date >= :Date2)
									AND tbl_member_committed_dates.fld_deleted = 0
									 ) AS is_committed,
									 (SELECT tbl_group_attendance.fld_date
										FROM tbl_group_attendance
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date3)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS last_attended, 
										(SELECT tbl_groups.fld_group_name AS last_attended
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE tbl_group_attendance.fld_user_id = a.id_user
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :Date4)
																	GROUP BY InnerAtt.fld_user_id
																	)
										LIMIT 1) AS group_name
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									WHERE (
											fld_first_name LIKE concat('%', :Search1, '%') 
											OR fld_last_name LIKE concat('%', :Search2, '%')
											OR concat(fld_first_name, ' ', fld_last_name)  LIKE concat('%', :Search3, '%')
											)
									AND NOT EXISTS(
													SELECT *
													FROM tbl_group_attendance
													WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
													AND tbl_group_attendance.fld_group_id = :GroupID2
													AND tbl_group_attendance.fld_date = :Date5
													)
													
									AND (EXISTS(
													SELECT *
													FROM tbl_group_attendance
													WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
													AND tbl_group_attendance.fld_group_id = :GroupID3
													AND tbl_group_attendance.fld_date != :Date6
													)
										OR NOT EXISTS(	
													SELECT *
													FROM tbl_group_attendance
													WHERE tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
													)	
										)
									AND EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												WHERE tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id	
												AND tbl_user_activity_dates.fld_deleted = 0
												AND fld_user_type_string = :MemberString
												AND fld_start_date <= :Date7
												AND (fld_end_date IS NULL	
													OR fld_end_date >= :Date8
													)
												)
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getMembersBySearchStringGroupOptimised($SearchString,$GroupID,$Date,$RecordLimit,$Hide_CO = true)
{
	global $dbh;
	global $CommunityObserver;
	
	
	$Hide_ComObs = ( $Hide_CO ? "AND fld_first_name != '".$CommunityObserver."'" : '');
	
	if( $RecordLimit == 'all' )
	{
		$LimitToo = '';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':GroupID' => $GroupID
								);
		
	} else {
		
		$RecordLimit = intval($RecordLimit);
		
		$LimitToo = 'LIMIT :limit';
		
		$param_array = array(	
								':Date' => $Date,
								':Date2' => $Date,
								':Date3' => $Date,
								':Search1' => $SearchString,
								':Search2' => $SearchString,
								':GroupID' => $GroupID,
								':limit' => $RecordLimit
								);
	}
	
	try {
		$Members = $dbh->prepare("SELECT a.id_user, tbl_members.fld_first_name, tbl_members.fld_last_name, b.last_attended, b.fld_group_name,
									(SELECT MAX(fld_user_id) FROM tbl_member_committed_dates
									WHERE tbl_member_committed_dates.fld_user_id = a.id_user
									AND fld_start_date <= :Date
									AND (fld_end_date IS NULL
									 	OR fld_end_date >= :Date2)
									AND tbl_member_committed_dates.fld_deleted = 0
									 ) AS is_committed
									FROM tbl_users a
									JOIN tbl_members
									ON a.id_user = tbl_members.fld_user_id
									LEFT OUTER JOIN (
										SELECT MAX(tbl_group_attendance.fld_date) AS last_attended, fld_group_name, tbl_group_attendance.fld_user_id, tbl_groups.id_group
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE (tbl_group_attendance.fld_deleted IS NULL OR tbl_group_attendance.fld_deleted = 0)
										AND (tbl_group_attendance.fld_date IS NULL OR tbl_group_attendance.fld_date <= :Date3)
										GROUP BY tbl_group_attendance.fld_user_id
											) b ON (b.fld_user_id = a.id_user)
									WHERE (fld_first_name LIKE concat('%', :Search1, '%') OR fld_last_name LIKE concat('%', :Search2, '%'))
									AND (b.id_group IS NULL OR b.id_group = :GroupID)
									".$Hide_ComObs."
									ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
									".$LimitToo."
								");
		$Members->execute($param_array);
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getMembersBySearchString($SearchString,$Hide_CO = true)
{
	global $dbh;
	global $CommunityObserver;
	
	
	$Hide_ComObs = ( $Hide_CO ? "AND fld_first_name != '".$CommunityObserver."'" : '');
	
	try {
		$Members = $dbh->prepare("SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE (fld_first_name LIKE concat('%', :Search1, '%') OR fld_last_name LIKE concat('%', :Search2, '%'))
								".$Hide_ComObs."
								ORDER BY tbl_members.fld_first_name ASC, tbl_members.fld_last_name ASC
								");
		$Members->execute(array(	
								':Search1' => $SearchString,
								':Search2' => $SearchString
								));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Members;
}

function getVolunteersBySearchString($SearchString) //this may need to be changed to capture anyone who has ever been a volunteer or maybe feed it a date.
{
	global $dbh;
	
	try {
		$Staff = $dbh->prepare("SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE (fld_first_name LIKE concat('%', :Search1, '%') OR fld_last_name LIKE concat('%', :Search2, '%'))
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											JOIN tbl_staff_roles
											ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND tbl_staff_roles.fld_staff_vol = 'volunteer'
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
										   )
								ORDER BY tbl_staff.fld_first_name ASC, tbl_staff.fld_last_name ASC
								");
		$Staff->execute(array(	':Search1' => $SearchString,
								':Search2' => $SearchString
								));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function getMemberByID($UserID)
{
	global $dbh;
	
	try {
		$Member = $dbh->prepare('SELECT tbl_users.*, tbl_members.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_members
								ON tbl_users.id_user = tbl_members.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE id_user = :user_id
								');
		$Member->execute(array(':user_id' => $UserID));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Member;
}

function getStaffByID($UserID)
{
	global $dbh;
	
	try {
		$Staff = $dbh->prepare('SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE id_user = :user_id
								');
		$Staff->execute(array(':user_id' => $UserID));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function validateStaffLogin($UserID)
{
	global $dbh;
	global $StaffVolunteer;
	
	try {
		$Staff = $dbh->prepare('SELECT tbl_users.*, tbl_staff.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_staff
								ON tbl_users.id_user = tbl_staff.fld_user_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE id_user = :user_id
								AND EXISTS(
											SELECT *
											FROM tbl_user_activity_dates
											WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
											AND fld_user_type_string = :StaffVolunteer
											AND (tbl_user_activity_dates.fld_start_date <= DATE(NOW())
												AND (tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_user_activity_dates.fld_end_date >= DATE(NOW()) 
													)
												)
											)
								
								');
		$Staff->execute(array(
								':user_id' => $UserID,
								':StaffVolunteer' => $StaffVolunteer
								));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Staff;
}

function getUserByIDNonSafe($UserID)
{
	global $dbh;
	
	try {
		$User = $dbh->prepare('	SELECT *
								FROM tbl_users
								WHERE id_user = :user_id
								');
		$User->execute(array(':user_id' => $UserID));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User;
}

function getUserByID($UserID,$HideAdenJones = true)
{
	global $dbh;
	
	if( $HideAdenJones )
	{
		$Hide = "AND fld_username != 'AdenJones'";
	} else {
		$Hide = "";
	}
	
	try {
		$User = $dbh->prepare('SELECT tbl_users.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page, tbl_user_types.fld_type_category
								FROM tbl_users
								JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE id_user = :user_id
								'.$Hide.'
								');
		$User->execute(array(':user_id' => $UserID));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $User;
}

//check duplicate user name
function chkDuplicateUser($UserName)
{
	global $dbh;
	
	try {
			$UNames = $dbh->prepare('SELECT fld_username FROM tbl_users WHERE fld_username = :UName');
			$UNames->execute(array(':UName' => $UserName ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UNames;
}

function chkDuplicateScreen($ScreenName)
{
	global $dbh;
	
	try {
			$SNames = $dbh->prepare('SELECT fld_screen_name FROM tbl_users WHERE fld_screen_name = :SName');
			$SNames->execute(array(':SName' => $ScreenName ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $SNames;
}

//check duplicate user name that is Not Self
function chkDuplicateUserNS($UserID,$UserName)
{
	global $dbh;
	
	try {
			$UNames = $dbh->prepare('SELECT fld_username 
									FROM tbl_users 
									WHERE fld_username = :UName
									AND id_user <> :idUser');
			$UNames->execute(array(':UName' => $UserName,
								   ':idUser' => $UserID ));
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $UNames;
}

function chkDuplicateScreenNS($UserID,$ScreenName)
{
	global $dbh;
	
	try {
			$SNames = $dbh->prepare('SELECT fld_screen_name 
									FROM tbl_users 
									WHERE fld_screen_name = :SName
									AND id_user <> :idUser');
			$SNames->execute(array(':SName' => $ScreenName,
								   ':idUser' => $UserID ));
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $SNames;
}

/* Username specific error reporting function */
function CreateErrorMessageUName($UserID,$Msg,$UserName,$ErrorName,$SelfCheck)
{
	global $blnIsGood;
	
	if( $Msg != 'good')
	{
		\Validation\CreateErrorMessage($Msg,$ErrorName);
		
	} else {
		//collect list of usernames
		if($SelfCheck)
		{
			$UNames = chkDuplicateUserNS($UserID,$UserName);
		} else {
			$UNames = chkDuplicateUser($UserName);
		}
		
		
		if($UNames->rowCount() > 0)
		{
			\Validation\CreateErrorMessage(' duplicate detected. Please try a different User Name!',$ErrorName);
			$blnIsGood = false;
			
		}
		
	}//End User Name validation
}

function CreateErrorMessageSName($UserID,$Msg,$ScreenName,$ErrorName,$SelfCheck)
{
	global $blnIsGood;
	
	if( $Msg != 'good')
	{
		\Validation\CreateErrorMessage($Msg,$ErrorName);
		
	} else {
		//collect list of ScreenNames
		if($SelfCheck)
		{
			$SNames = chkDuplicateScreenNS($UserID,$ScreenName);
		} else {
			$SNames = chkDuplicateScreen($ScreenName);
		}
		
		
		if($SNames->rowCount() > 0)
		{
			\Validation\CreateErrorMessage(' duplicate detected. Please try a different Screen Name!',$ErrorName);
			$blnIsGood = false;
			
		}
		
	}//End User Name validation
}

function CreateErrorMessagePasswordMatch($Message,$Salt,$OldHashedPassword,$Password,$ErrorName)
{
	global $blnIsGood;
	
	if( $Message != 'good')
	{
		\Validation\CreateErrorMessage($Message,$ErrorName);
		
	} else {
		
		$chkPWord = CheckOldPassword($Salt,$OldHashedPassword,$Password);
		
		if(!$chkPWord)
		{
			\Validation\CreateErrorMessage(" does not match!",$ErrorName);
			$blnIsGood = false;
			
		}
	}
}

//check that the submitted password matches with the submitted user
function CheckOldPassword($Salt,$OldHashedPassword,$Password)
{
	
	$OldPassWord = hash('sha256',"$Salt$Password");
	
	return $OldPassWord == $OldHashedPassword;
	
}

//gotta make sure this isn't called by general users
function GetAllUsers($HideAdenJones = true)
{
	if( $HideAdenJones )
	{
		$Hide = "WHERE fld_username != 'AdenJones'";
	} else {
		$Hide = "";
	}
	
	global $dbh;
	
	try {
			$AllUsers = $dbh->query(	'SELECT tbl_users.*, tbl_user_types.fld_user_type, tbl_user_types.fld_default_page
										FROM tbl_users
										JOIN tbl_user_types
										ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
										'.$Hide.'
										ORDER BY tbl_users.fld_screen_name
										');
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $AllUsers;
}

function LoadAllUsers()
{
	$thisUsers = array();
	
	// Insert code to retrieve member here
	$arrUsers = GetAllUsers()->fetchAll();
	
	foreach($arrUsers as $thisArrayUser)
	{
		$thisUser = new User();
		
		$thisUser->SetUserID($thisArrayUser['id_user']);
		$thisUser->SetUserTypeID($thisArrayUser['fld_user_type_id']);
		$thisUser->SetUserName($thisArrayUser['fld_username']);
		$thisUser->SetHashedPassword($thisArrayUser['fld_password']);
		$thisUser->SetSalt($thisArrayUser['fld_salt']);
		$thisUser->SetScreenName($thisArrayUser['fld_screen_name']);
		$thisUser->SetEmailAddress($thisArrayUser['fld_email_address']);
		$thisUser->SetDeleted($thisArrayUser['fld_deleted']);
		$thisUser->SetUserTypeName($thisArrayUser['fld_user_type']);
		$thisUser->SetDefaultPage($thisArrayUser['fld_default_page']);
		
		$thisUsers[] = $thisUser;
	}
	
	// returns object to be assigned
	
	return $thisUsers;
}

function UserExists($UserID)
{
	if(getUserByID($UserID)->rowCount() == 0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function UserTypeExists($UserTypeID)
{
	$UserType = GetUserTypeByID($UserTypeID);
		
	if($UserType->rowCount() != 1)
	{
		return " has an invalid value!";
	}
	
	return 'good';
}

function GetUserTypeByID($UserTypeID)
{
	global $dbh;
	
	try {
		$UserType = $dbh->prepare('	SELECT * 
									FROM tbl_user_types
									WHERE id_user_type = :UserTypeID
									');
		$UserType->execute(array(':UserTypeID' => $UserTypeID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserType;
}

function getStaffUserTypes()
{
	global $dbh;
	
	try {
		$UserTypes = $dbh->query("SELECT * 
								FROM tbl_user_types
								WHERE fld_type_category = 'staff'
								");
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserTypes;
}

function getVolunteerUserTypes()
{
	global $dbh;
	
	try {
		$UserTypes = $dbh->query("SELECT * 
								FROM tbl_user_types
								WHERE fld_user_type = 'Group User'
								");
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserTypes;
}

function getNonStaffUserTypes()
{
	global $dbh;
	
	try {
		$UserTypes = $dbh->query("SELECT * 
								FROM tbl_user_types
								WHERE fld_type_category != 'staff'
								");
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserTypes;
}


function GetUserTypes()
{

	global $dbh;
	

	try {
		$UserTypes = $dbh->query('SELECT * 
								FROM tbl_user_types
								
								');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserTypes;

}
?>