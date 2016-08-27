<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	$BaseIncludeURL = '../../grow_demo/php';
	
	include $BaseIncludeURL.'/spc/spc_db_con.php';
	include $BaseIncludeURL.'/spc/spc_classes_includer.php'; // class must be called before session start
	include $BaseIncludeURL.'/spc/spc_functions.php'; //this will probably be removed once object libraries have been created
	include $BaseIncludeURL.'/spc/spc_query_functions.php'; //this will probably be removed once object libraries have been created
	include $BaseIncludeURL.'/spc/spc_generic_controls.php';

	date_default_timezone_set('Australia/Brisbane');
	if (session_id() === "") { session_name('grow_db'); session_start(); } //start the session to use session variables
	
	ignore_user_abort(true);
	set_time_limit(0);
	
	$true = 1;
	$false = 0;
	
	$UserTypeCatStaff = 'staff';
	$UserTypeCatAdmin = 'admin';
	$UserTypeCatVol = 'volunteer';
	
	$Admin = 'Administrator';
	$StaffAdmin = 'Staff Administrator';
	$NationalUser = 'National User';
	$StateUser = 'State User';
	$GroupUser = 'Group User';
	$FieldWorker = 'Field Worker';
	
	$CommunityObserver = 'Community_Observer';
	
	$Organiser = 'Organiser';
	$Recorder = 'Recorder';
	
	$Committed = 'Committed';
	
	//non group types
	//Groups group types should be null
	define('NON_GROUP_TEAM', 'team');
	define('NON_GROUP_HOS_OR', 'hos_or');
	define('NON_GROUP_SOC_EV', 'soc_ev');
	define('NON_GROUP_COM_OUT', 'com_out');
	define('NON_GROUP_TRAIN', 'train');
	
	//Change Log Change Types
	define('CHANGE_INSERT', 'insert');
	define('CHANGE_UPDATE', 'update');
	define('CHANGE_DELETE', 'delete');
	
	//Branches regions seperator : specifically for ensuring difference between Branches and Regions in tbl_groups_regions
	define('GROUP_REGION_BRANCH', 'branch');
	define('GROUP_REGION_REGION', 'region');
	define('GROUP_REGION_UNKNOWN', 'unknown');
	
	//Decision Types
	$Pending = 'Pending';
	$Approved = 'Approved';
	$Declined = 'Declined';
	
	//Statuses
	define('STATUS_IN_PROCESS', 'In Process');
	define('STATUS_COMPLETE', 'Complete');
	define('STATUS_ERROR', 'Error');
	
	$InProcess = 'In Process';
	$Complete = 'Complete';
	$Error = 'Error';
	
	define('MEMBER_STRING', 'member');
	define('STAFF_VOL_STRING', 'staff_volunteer');
	
	define('ORGANISER','Organiser');
	define('RECORDER','Recorder');
	define('SPONSOR','Sponsor');
?>
