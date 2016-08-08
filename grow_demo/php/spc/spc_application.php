<?php

/* 	Master application Action file.
	This file contains all links,
	and global settings.
*/

/*	set default time zone */
date_default_timezone_set('Australia/Brisbane');
/*	hide all errors except critical errors
	this is contigent upon the webserver having
	display_errors = on; */
/*error_reporting(E_ALL ^ E_NOTICE);*/
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

/*	declare a string of valid salt characters
	this is for use in hashing algorithms */
$strValChar = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*()-=_+[]{}`~<>,./?;:';
/* Newline Character */
$newLine = "\r\n";

/*	Create absolute uri string */
$protocol = 'http://'; //this can be changed to https when https is enabled
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$full_uri = "$protocol$host$uri";

//initialise return to
if(!isset($_SESSION['return_to']))
{
	$_SESSION['return_to'] = 'not_set';
}


/*
	Global boolean values
	used throughout when setting mysql values to true or false

*/

$true = 1;
$false = 0;

/*	Global variables */

/* User Type Categories */
$UserTypeCatStaff = 'staff';
$UserTypeCatAdmin = 'admin';
$UserTypeCatVol = 'volunteer';

define('VOLUNTEER','volunteer');

/* User Types New*/
define('ADMINISTRATOR','Administrator');
define('STAFF_ADMINISTRATOR','Staff Administrator');
define('NATIONAL_USER' ,'National User');
define('STATE_USER', 'State User');
define('GROUP_USER', 'Group User');
define('FIELD_WORKER', 'Field Worker');

/* User Types Old*/
$Admin = 'Administrator';
$StaffAdmin = 'Staff Administrator';
$NationalUser = 'National User';
$StateUser = 'State User';
$GroupUser = 'Group User';
$FieldWorker = 'Field Worker';
/* User Type Categories */
define('ADMIN','admin');

$CommunityObserver = 'Community_Observer';

$Organiser = 'Organiser';
$Recorder = 'Recorder';

$Committed = 'Committed';

define('ORGANISER','Organiser');
define('RECORDER','Recorder');
define('SPONSOR','Sponsor');

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

/* User Types for user activity dates */
$StaffVolunteer  = 'staff_volunteer';
$MemberString = 'member';
define('MEMBER_STRING', 'member');
define('STAFF_VOL_STRING', 'staff_volunteer');
// Global Notifier Error message
$msg_general_notifier = 'No Notifications!';

//Reminder Date Types
$PoliceCheck = 'PoliceCheck';

//Reort Ranges
define('GROUP', 'GROUP');
define('REGION', 'REGION');
define('BRANCH', 'BRANCH');

//Label Types
define('REGION_LABELS', 'Region Labels');
define('BRANCH_LABELS', 'Branch Labels');

//Report Types
define('GROUP_BY_GROUP', 'Group By Group');
define('GROUP_BY_REGION', 'Group By Region');
define('GROUP_BY_BRANCH', 'Group By Branch');
define('TREND_BY_REGION', 'Trend By Region');
define('TREND_BY_BRANCH', 'Trend By Branch');
define('ATTENDEES_BY_GROUP', 'Attendees By Group');
define('ATTENDEES_BY_REGION', 'Attendees By Region');
define('ATTENDEES_BY_BRANCH', 'Attendees By Branch');
define('ATTENDANCES_BY_GROUP', 'Attendances By Group');
define('ATTENDANCES_BY_REGION', 'Attendances By Region');
define('ATTENDANCES_BY_BRANCH', 'Attendances By Branch');
define('STATISTICS_BY_GROUP', 'Statistics By Group');
define('STATISTICS_BY_REGION', 'Statistics By Region');
define('STATISTICS_BY_BRANCH', 'Statistics By Branch');
/*	Master links */
//insecure pages
$lnk_splash = "$full_uri/index.php?page_id=splash";
$lnk_sign_up = "$full_uri/index.php?page_id=sign_up";
//generic secure pages
$lnk_default_page = "$full_uri/index.php?page_id=select_default_page";
$lnk_log_out = "$full_uri/index.php?page_id=log_out";
$lnk_my_details = "$full_uri/index.php?page_id=view_my_details";
$lnk_edit_my_profile = "$full_uri/index.php?page_id=edit_my_profile";
$lnk_edit_my_login_details = "$full_uri/index.php?page_id=edit_my_login";
$lnk_page_denied = "$full_uri/index.php?page_id=page_denied";
$lnk_group_denied = "$full_uri/index.php?page_id=group_denied";

//secure staff pages
$lnk_add_edit_staff = "$full_uri/index.php?page_id=add_edit_staff";
$lnk_find_staff = "$full_uri/index.php?page_id=find_staff";
$lnk_find_vol = "$full_uri/index.php?page_id=find_vol";
$lnk_view_staff_new = "$full_uri/index.php?page_id=view_staff_new";
$lnk_view_vol_new = "$full_uri/index.php?page_id=view_vol_new";
$lnk_view_staff = "$full_uri/index.php?page_id=view_staff";
$lnk_view_staff_secure = "$full_uri/index.php?page_id=view_staff&access=secure";
$lnk_add_edit_staff_login = "$full_uri/index.php?page_id=add_edit_staff_login";
$lnk_view_regions = "$full_uri/index.php?page_id=view_regions";
$lnk_view_regions_secure = "$full_uri/index.php?page_id=view_regions&access=secure"; 
$lnk_add_edit_region = "$full_uri/index.php?page_id=add_edit_region";
$lnk_view_group_types_secure = "$full_uri/index.php?page_id=view_group_types&access=secure"; 
$lnk_add_edit_group_type = "$full_uri/index.php?page_id=add_edit_group_type";
$lnk_view_groups = "$full_uri/index.php?page_id=view_groups";
$lnk_view_groups_secure = "$full_uri/index.php?page_id=view_groups&access=secure";
$lnk_add_edit_group = "$full_uri/index.php?page_id=add_edit_group";
$lnk_view_group_schedule = "$full_uri/index.php?page_id=view_group_schedule";
$lnk_view_group_schedule_secure = "$full_uri/index.php?page_id=view_group_schedule&access=secure";
$lnk_add_edit_group_schedule = "$full_uri/index.php?page_id=add_edit_group_schedule";
$lnk_view_groups_regions_secure = "$full_uri/index.php?page_id=view_groups_regions&access=secure";
$lnk_view_groups_regions = "$full_uri/index.php?page_id=view_groups_regions";
$lnk_add_edit_group_region = "$full_uri/index.php?page_id=add_edit_group_region";
$lnk_view_user = "$full_uri/index.php?page_id=view_user";
$lnk_view_group = "$full_uri/index.php?page_id=view_group";

$lnk_view_all_venues_secure = "$full_uri/index.php?page_id=view_all_venues&access=secure";
$lnk_view_all_venues = "$full_uri/index.php?page_id=view_all_venues";

$lnk_add_edit_venue = "$full_uri/index.php?page_id=add_edit_venue";
$lnk_view_groups_venues_secure = "$full_uri/index.php?page_id=view_groups_venues&access=secure";
$lnk_view_groups_venues = "$full_uri/index.php?page_id=view_groups_venues";
$lnk_add_edit_group_venue = "$full_uri/index.php?page_id=add_edit_group_venue";
$lnk_view_groups_leaders = "$full_uri/index.php?page_id=view_groups_leaders";
$lnk_view_groups_leaders_secure = "$full_uri/index.php?page_id=view_groups_leaders&access=secure";
$lnk_add_edit_group_leader = "$full_uri/index.php?page_id=add_edit_group_leader";
$lnk_view_group_attendance = "$full_uri/index.php?page_id=view_group_attendance";
$lnk_view_group_attendance_secure = "$full_uri/index.php?page_id=view_group_attendance&access=secure";
$lnk_add_edit_group_attendance = "$full_uri/index.php?page_id=add_edit_group_attendance";
$lnk_add_edit_member = "$full_uri/index.php?page_id=add_edit_member";
$lnk_add_edit_group_schedule_dates = "$full_uri/index.php?page_id=add_edit_group_schedule_dates";
$lnk_view_staff_regions_secure = "$full_uri/index.php?page_id=view_staff_regions&access=secure";
$lnk_add_edit_staff_region = "$full_uri/index.php?page_id=add_edit_staff_region";
$lnk_view_help = "$full_uri/index.php?page_id=help";
$lnk_view_help_secure = "$full_uri/index.php?page_id=help&access=secure";
$lnk_add_edit_group_recess = "$full_uri/index.php?page_id=add_edit_group_recess";
$lnk_find_member = "$full_uri/index.php?page_id=find_member";

$lnk_pdf_report_group_by_group = "$full_uri/index.php?page_id=pdf_report_group_by_group";
$lnk_pdf_report_group_by_region = "$full_uri/index.php?page_id=pdf_report_group_by_region";
$lnk_pdf_report_group_by_region_optimised = "$full_uri/index.php?page_id=pdf_report_group_by_region_optimised";
$lnk_pdf_report_group_by_branch = "$full_uri/index.php?page_id=pdf_report_group_by_branch";
$lnk_pdf_report_group_by_branch_optimised = "$full_uri/index.php?page_id=pdf_report_group_by_branch_optimised";

$lnk_pdf_report_trend_by_region = "$full_uri/index.php?page_id=pdf_report_trend_by_region_optimised";
$lnk_pdf_report_trend_by_branch = "$full_uri/index.php?page_id=pdf_report_trend_by_branch_optimised";
$lnk_view_staff_volunteer_dates = "$full_uri/index.php?page_id=view_staff_volunteer_dates";
$lnk_view_staff_volunteer_dates_secure = "$full_uri/index.php?page_id=view_staff_volunteer_dates&access=secure";
$lnk_add_edit_staff_volunteer_dates =  "$full_uri/index.php?page_id=add_edit_staff_volunteer_dates";
$lnk_view_member_dates = "$full_uri/index.php?page_id=view_member_dates";
$lnk_add_edit_member_dates = "$full_uri/index.php?page_id=add_edit_member_dates";
$lnk_merge_members = "$full_uri/index.php?page_id=merge_members";
$lnk_add_edit_no_meeting_reason = "$full_uri/index.php?page_id=add_edit_no_meeting_reason";
$lnk_add_edit_member_committed_dates = "$full_uri/index.php?page_id=add_edit_member_committed_dates";
$lnk_reminder_not_attending_committed_members = "$full_uri/index.php?page_id=reminder_not_attending_committed_members";
$lnk_view_staff_reminder_dates = "$full_uri/index.php?page_id=view_staff_reminder_dates";
$lnk_add_edit_police_check_reminder_dates = "$full_uri/index.php?page_id=add_edit_police_check_reminder_dates";
$lnk_view_group_notes_secure = "$full_uri/index.php?page_id=view_group_notes&access=secure";
$lnk_view_group_notes_by_date = "$full_uri/index.php?page_id=view_group_notes_by_date";
$lnk_add_edit_group_note = "$full_uri/index.php?page_id=add_edit_group_note";
$lnk_view_my_group_notes = "$full_uri/index.php?page_id=view_my_group_notes";
$lnk_view_state_user_states_secure = "$full_uri/index.php?page_id=view_state_user_states&access=secure";
$lnk_add_edit_state_user_state_activity_dates =  "$full_uri/index.php?page_id=add_edit_state_user_state_activity_dates";
$lnk_add_edit_team = "$full_uri/index.php?page_id=add_edit_team";
$lnk_view_teams = "$full_uri/index.php?page_id=view_teams";
$lnk_view_teams_secure = "$full_uri/index.php?page_id=view_teams&access=secure";
$lnk_view_branches_regions_secure = "$full_uri/index.php?page_id=view_branches_regions&access=secure";
$lnk_add_edit_branch_region = "$full_uri/index.php?page_id=add_edit_branch_region";

$lnk_add_edit_hospital_orientation = "$full_uri/index.php?page_id=add_edit_hospital_orientation";
$lnk_view_hospital_orientations_secure = "$full_uri/index.php?page_id=view_hospital_orientations&access=secure";
$lnk_view_hospital_orientations = "$full_uri/index.php?page_id=view_hospital_orientations";

$lnk_add_edit_social_event = "$full_uri/index.php?page_id=add_edit_social_event";
$lnk_view_social_events_secure = "$full_uri/index.php?page_id=view_social_events&access=secure";
$lnk_view_social_events = "$full_uri/index.php?page_id=view_social_events";

$lnk_add_edit_community_outreach = "$full_uri/index.php?page_id=add_edit_community_outreach";
$lnk_view_community_outreach_secure = "$full_uri/index.php?page_id=view_community_outreach&access=secure";
$lnk_view_community_outreach = "$full_uri/index.php?page_id=view_community_outreach";

$lnk_add_edit_training = "$full_uri/index.php?page_id=add_edit_training";
$lnk_view_training_secure = "$full_uri/index.php?page_id=view_training&access=secure";
$lnk_view_training = "$full_uri/index.php?page_id=view_training";

$lnk_view_audit_dates = "$full_uri/index.php?page_id=view_audit_dates";
$lnk_view_audit_dates_secure = "$full_uri/index.php?page_id=view_audit_dates&access=secure";
$lnk_add_edit_audit_date = "$full_uri/index.php?page_id=add_edit_audit_date";

//secute administrator pages
$lnk_view_all_users = "$full_uri/index.php?page_id=view_all_users"; //this is only for senior admins (not staff)
$lnk_add_edit_user = "$full_uri/index.php?page_id=add_edit_user"; //this is only for senior admins (not staff)
$lnk_add_edit_help = "$full_uri/index.php?page_id=add_edit_help"; //this is only for senior admins (not staff)

//new reports
$lnk_view_report = "$full_uri/index.php?page_id=view_report";
$lnk_view_my_reports = "$full_uri/index.php?page_id=view_my_reports";
$lnk_pdf_generic_report = "$full_uri/index.php?page_id=pdf_generic_report";
$lnk_csv_generic_report = "$full_uri/index.php?page_id=csv_generic_report";

//labels
$lnk_view_labels = "$full_uri/index.php?page_id=view_labels";
$lnk_csv_generic_labels = "$full_uri/index.php?page_id=csv_labels";
$lnk_view_my_labels = "$full_uri/index.php?page_id=view_my_labels";
$lnk_volunteer_labels_L7163 = "$full_uri/index.php?page_id=pdf_volunteer_labels_L7163";
$lnk_volunteer_labels_L7160 = "$full_uri/index.php?page_id=pdf_volunteer_labels_L7160";

/*	ensure true prepared statements are used */
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//start a session if one has not already been started
if (session_id() === "") { session_name('grow_db'); session_start(); }

//initialise logged in variable if not set
if(!isset($_SESSION['loggedIn']))
{
	$_SESSION['loggedIn'] = false;
}

/*	Redirect to index if user attempts to 
	access a secure page when not logged In */

if( !($page_id == 'splash') and $_SESSION['loggedIn'] == false)
{
	header( "Location: $lnk_splash" );
	exit;
}

if( ($page_id == 'splash') and $_SESSION['loggedIn'] == true)
{
	header( "Location: $lnk_default_page" );
	exit;
}

?>