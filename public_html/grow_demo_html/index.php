<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

/*
	Master navigation page
	This is the only page that is
	web accessible. All other 
	content is accessed via includes
*/

//check to see if page_id has been defined
if(isset($_REQUEST['page_id'])) {
	$page_id = $_REQUEST['page_id'];
} else {
	$page_id = 'splash';
}

//base include path

$BaseIncludeURL = '../../grow_demo/php';

define ("BaseExternalURL",'http://adenjones.com.au/grow_demo_html/');

//universal dsp
$dsp_header = $BaseIncludeURL.'/dsp_generic/gen_header.php';
$dsp_footer = $BaseIncludeURL.'/dsp_generic/gen_footer.php';

//insecure
$dsp_splash_header = $BaseIncludeURL.'/dsp_generic/gen_splash_header.php';
$dsp_sign_up_header = $BaseIncludeURL.'/dsp_generic/gen_sign_up_header.php';
$dsp_splash_footer = $BaseIncludeURL.'/dsp_generic/gen_splash_footer.php';

//secure
$spc_load_page = $BaseIncludeURL.'/spc/spc_load_page.php';
$spc_security_by_page = $BaseIncludeURL.'/spc/spc_security_by_page.php'; //security by page is dependant upon load page

$dsp_home_header = $BaseIncludeURL.'/dsp_generic/gen_home_header.php';
$dsp_home_nav = $BaseIncludeURL.'/dsp_generic/gen_home_nav.php'; //this navigator is included in all secure dsp pages
$dsp_gen_sidebar = $BaseIncludeURL.'/dsp_generic/gen_sidebar.php';
$dsp_notification_sidebar = $BaseIncludeURL.'/dsp_generic/gen_notifier.php';


/* Include variables */
include $BaseIncludeURL.'/spc/spc_db_con.php';
include $BaseIncludeURL.'/spc/spc_classes_includer.php'; // class must be called before session start
include $BaseIncludeURL.'/spc/spc_application.php';
include $BaseIncludeURL.'/spc/spc_functions.php'; //this will probably be removed once object libraries have been created
include $BaseIncludeURL.'/spc/spc_query_functions.php'; //this will probably be removed once object libraries have been created
include $BaseIncludeURL.'/spc/spc_generic_controls.php';


switch($page_id) {
	
	/* Begin Insecure Pages */
	case 'splash':
		$page_name = 'Log In';
		//insecure page
		include $BaseIncludeURL.'/act/act_splash.php';
		include $dsp_header;
		include $dsp_splash_header;
		include $BaseIncludeURL.'/dsp/dsp_splash.php';
		include $dsp_splash_footer;
		break;
	/* End Insecure Pages */
	
	/* Begin Admin Pages */
	case 'view_change_log':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_change_log.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_change_log.php';
		include $dsp_footer;
		break;
	case 'variable_inspector':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_variable_inspector.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_variable_inspector.php';
		include $dsp_footer;
		break;
	case 'administrator_home':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_admin_home.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_admin_home.php';
		include $dsp_footer;
		break;
	case 'view_all_users':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_all_users.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_all_users.php';
		include $dsp_footer;
		break;
	case 'add_edit_user':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_user.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_user.php';
		include $dsp_footer;
		break;
	case 'edit_user_type':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_edit_user_type.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_edit_user_type.php';
		include $dsp_footer;
		break;
	case 'view_disputes':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_disputes.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_disputes.php';
		include $dsp_footer;
		break;
	case 'resolve_dispute':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_resolve_dispute.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_resolve_dispute.php';
		include $dsp_footer;
		break;
	case 'php_info':
		include $spc_load_page;
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_phpinfo.php';
		break;
	case 'report_test':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_test.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_test.php';
		include $dsp_footer;
		break;
	/* End Admin Pages */
	
	/* Begin Staff Pages */
	
	case 'view_user':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_user.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_user.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_audit_date':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_audit_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_audit_dates.php';
		include $dsp_footer;
		break;
	
	case 'view_audit_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_audit_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_audit_dates.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_training':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_training.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_training.php';
		include $dsp_footer;
		break;
	
	case 'view_training':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_training.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_training.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_community_outreach':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_community_outreach.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_community_outreach.php';
		include $dsp_footer;
		break;
	
	case 'view_community_outreach':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_community_outreach.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_community_outreach.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_social_event':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_social_event.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_social_event.php';
		include $dsp_footer;
		break;
	
	case 'view_social_events':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_social_events.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_social_events.php';
		include $dsp_footer;
		break;
	
	case 'view_hospital_orientations':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_hospital_orientations.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_hospital_orientations.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_hospital_orientation':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_hospital_orientation.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_hospital_orientation.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_branch_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_branch_region.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_branch_region.php';
		include $dsp_footer;
		break;
	
	case 'view_branches_regions':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_branches_regions.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_branches_regions.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_team':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_team.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_team.php';
		include $dsp_footer;
		break;
	
	case 'view_teams':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_teams.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_teams.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_state_user_state_activity_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_state_user_state_activity_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_state_user_state_activity_dates.php';
		include $dsp_footer;
		break;
	
	case 'view_state_user_states':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_state_user_states.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_state_user_states.php';
		include $dsp_footer;
		break;
	
	case 'default_home':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_default_home.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_default_home.php';
		include $dsp_footer;
		break;
	
	case 'find_staff':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_find_staff.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_find_staff.php';
		include $dsp_footer;
		break;
	
	case 'find_vol':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_find_vol.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_find_vol.php';
		include $dsp_footer;
		break;
	
	case 'view_staff':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_staff.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_staff.php';
		include $dsp_footer;
		break;
		
	case 'view_staff_new':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_staff_new.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_staff_new.php';
		include $dsp_footer;
		break;
		
	case 'view_vol_new':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_vol_new.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_vol_new.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_staff':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_staff.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_staff.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_staff_login':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_staff_login.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_staff_login.php';
		include $dsp_footer;
		break;
	
	case 'view_regions': //this can be amended to show only limited records
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_regions.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_regions.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_region.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_region.php';
		include $dsp_footer;
		break;
	
	case 'view_group_types': //this can be amended to show only limited records
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_group_types.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_group_types.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_group_type':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_type.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_type.php';
		include $dsp_footer;
		break;
	
	case 'find_group': //this can be amended to show only limited records
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_find_group.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_find_group.php';
		include $dsp_footer;
		break;	
	
	case 'view_group': 
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_group.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_group.php';
		include $dsp_footer;
		break;
	
	case 'view_groups': //this can be amended to show only limited records
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_groups.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_groups.php';
		include $dsp_footer;
		break;	
	
	case 'add_edit_group':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group.php';
		include $dsp_footer;
		break;
		
	case 'view_group_schedule':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_group_schedule.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_group_schedule.php';
		include $dsp_footer;
		break;
	
	case 'view_groups_regions':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_groups_regions.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_groups_regions.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_group_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_region.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_region.php';
		include $dsp_footer;
		break;
		
	case 'view_all_venues':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_all_venues.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_all_venues.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_venue':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_venue.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_venue.php';
		include $dsp_footer;
		break;
	
	case 'view_groups_venues':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_groups_venues.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_groups_venues.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_group_venue':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_venue.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_venue.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_group_schedule':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_schedule.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_schedule.php';
		include $dsp_footer;
		break;
		
	case 'view_groups_leaders':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_groups_leaders.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_groups_leaders.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_group_leader':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_leader.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_leader.php';
		include $dsp_footer;
		break;
		
	case 'view_group_attendance':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_group_attendance.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_group_attendance.php';
		include $dsp_footer;
		break;
		
	case 'view_group_notes':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_group_notes.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_group_notes.php';
		include $dsp_footer;
		break;
		
	case 'view_my_group_notes':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_my_group_notes.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_my_group_notes.php';
		include $dsp_footer;
		break;
		
	case 'view_group_notes_by_date':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_group_notes_by_date.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_group_notes_by_date.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_group_note':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_note.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_note.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_group_attendance':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_attendance.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_attendance.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_member':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_member.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_member.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_group_schedule_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_schedule_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_schedule_dates.php';
		include $dsp_footer;
		break;
	
	case 'view_staff_regions':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_staff_regions.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_staff_regions.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_staff_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_staff_region.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_staff_region.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_group_recess':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_group_recess.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_group_recess.php';
		include $dsp_footer;
		break;
	
	case 'find_member':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_find_member.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_find_member.php';
		include $dsp_footer;
		break;
	
	case 'view_staff_volunteer_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_staff_volunteer_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_staff_volunteer_dates.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_staff_volunteer_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_staff_volunteer_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_staff_volunteer_dates.php';
		include $dsp_footer;
		break;
	
	case 'view_member_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_member_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_member_dates.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_member_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_member_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_member_dates.php';
		include $dsp_footer;
		break;
	
	case 'merge_members':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_merge_members.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_merge_members.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_no_meeting_reason':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_no_meeting_reason.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_no_meeting_reason.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_member_committed_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_member_committed_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_member_committed_dates.php';
		include $dsp_footer;
		break;
		
	case 'view_staff_reminder_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_staff_reminder_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_staff_reminder_dates.php';
		include $dsp_footer;
		break;
	
	case 'add_edit_police_check_reminder_dates':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_police_check_reminder_dates.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_police_check_reminder_dates.php';
		include $dsp_footer;
		break;
	/* End Staff Pages */
	
	/* Begin Staff Reports */
	
	case 'report_statistics':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_statistics.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_statistics.php';
		include $dsp_footer;
		break;
	
	case 'report_attendances':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_attendances.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_attendances.php';
		include $dsp_footer;
		break;
	
	case 'report_attendees':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_attendees.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_attendees.php';
		include $dsp_footer;
		break;
	
	case 'report_group_by_group':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_group_by_group.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_group_by_group.php';
		include $dsp_footer;
		break;
		
	case 'report_group_by_group_improved':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_group_by_group_improved.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_group_by_group_improved.php';
		include $dsp_footer;
		break;
	
	case 'report_group_by_region_improved':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_group_by_region_improved.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_group_by_region_improved.php';
		include $dsp_footer;
		break;
	
	
	case 'pdf_report_group_by_group':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_group_by_group.php';
		include $BaseIncludeURL.'/pdf/pdf_report_group_by_group.php';
		break;
	
	case 'report_group_by_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_group_by_region.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_group_by_region.php';
		include $dsp_footer;
		break;
	
	case 'pdf_report_group_by_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_group_by_region.php';
		include $BaseIncludeURL.'/pdf/pdf_report_group_by_region.php';
		break;
		
	case 'pdf_report_group_by_region_optimised':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_group_by_region_optimised.php';
		include $BaseIncludeURL.'/pdf/pdf_report_group_by_region_optimised.php';
		break;
	
	case 'report_group_by_branch':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_group_by_branch.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_group_by_branch.php';
		include $dsp_footer;
		break;
		
	case 'report_group_by_branch_improved':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_group_by_branch_improved.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_group_by_branch_improved.php';
		include $dsp_footer;
		break;
		
	case 'pdf_report_group_by_branch_optimised':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_group_by_branch_optimised.php';
		include $BaseIncludeURL.'/pdf/pdf_report_group_by_branch_optimised.php';
		break;
	
	case 'pdf_report_group_by_branch':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_group_by_branch.php';
		include $BaseIncludeURL.'/pdf/pdf_report_group_by_branch.php';
		break;
	
	case 'report_trend_by_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_trend_by_region.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_trend_by_region.php';
		include $dsp_footer;
		break;
		
	case 'report_trend_by_region_improved':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_trend_by_region_improved.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_trend_by_region_improved.php';
		include $dsp_footer;
		break;
	
	case 'pdf_report_trend_by_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_trend_by_region.php';
		include $BaseIncludeURL.'/pdf/pdf_report_trend_by_region.php';
		break;
		
	case 'pdf_report_trend_by_region_optimised':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_trend_by_region_optmised.php';
		include $BaseIncludeURL.'/pdf/pdf_report_trend_by_region_optimised.php';
		break;
	
	case 'report_trend_by_branch':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_trend_by_branch.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_trend_by_branch.php';
		include $dsp_footer;
		break;
		
	case 'report_trend_by_branch_improved':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_report_trend_by_branch_improved.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_report_trend_by_branch_improved.php';
		include $dsp_footer;
		break;
	
	case 'pdf_report_trend_by_branch':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_trend_by_branch.php';
		include $BaseIncludeURL.'/pdf/pdf_report_trend_by_branch.php';
		break;
	
	case 'pdf_report_trend_by_branch_optimised':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/pdf/act_pdf_report_trend_by_branch_optimised.php';
		include $BaseIncludeURL.'/pdf/pdf_report_trend_by_branch_optimised.php';
		break;
		
	case 'view_my_reports':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_my_reports.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_my_reports.php';
		include $dsp_footer;
		break;
		
	case 'view_my_labels':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_my_labels.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_my_labels.php';
		include $dsp_footer;
		break;
		
	case 'view_labels':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_labels.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_labels.php';
		include $dsp_footer;
		break;
	//NEW REPORTS
	
	case 'pdf_volunteer_labels_L7163':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_labels.php';
		include $BaseIncludeURL.'/pdf/pdf_volunteer_labels_L7163.php';
		break;
		
	case 'pdf_volunteer_labels_L7160':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_labels.php';
		include $BaseIncludeURL.'/pdf/pdf_volunteer_labels_L7160.php';
		break;
	
	case 'csv_generic_report':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_report.php';
		include $BaseIncludeURL.'/csv/csv_generic_report.php';
		break;
		
	case 'csv_labels':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_labels.php';
		include $BaseIncludeURL.'/csv/csv_labels.php';
		break;
	
	case 'pdf_generic_report':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_report.php';
		include $BaseIncludeURL.'/pdf/pdf_generic_report.php';
		break;
		
	case 'view_report':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_report.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_report.php';
		include $dsp_footer;
		break;
		
	case 'async_report_group_by_group':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/async/async_group_by_group.php';
		break;
		
	case 'async_report_group_by_region':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/async/async_group_by_region.php';
		break;
		
	case 'labels_volunteer':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_labels_volunteer.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_labels_volunteer.php';
		include $dsp_footer;
		break;
	/* End Staff Reports */
	
	/* Begin Reminder Systems */
	
	case 'reminder_venue_audits':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_reminder_venue_audits_due.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_reminder_venue_audits_due.php';
		include $dsp_footer;
		break;
	
	case 'reminder_not_attending_committed_members':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_reminder_not_attending_committed_members.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_reminder_not_attending_committed_members.php';
		include $dsp_footer;
		break;
	
	case 'reminder_group_without_organiser':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_reminder_group_without_organiser.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_reminder_group_without_organiser.php';
		include $dsp_footer;
		break;
		
	case 'reminder_group_recess_end':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_reminder_group_recess_end.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_reminder_group_recess_end.php';
		include $dsp_footer;
		break;
		
	case 'reminder_staff_police_check_due':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_reminder_police_check_due.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_reminder_police_check_due.php';
		include $dsp_footer;
		break;
		
	/* End Reminder Systems */ 
	
	/* Begin Unclassified Secure Pages */
	case 'view_my_details':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_view_my_details.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_view_my_details.php';
		include $dsp_footer;
		break;
	case 'edit_my_profile':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_edit_my_profile.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_edit_my_profile.php';
		include $dsp_footer;
		break;
	case 'edit_my_login':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_edit_my_login.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_edit_my_login.php';
		include $dsp_footer;
		break;
	case 'page_denied':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_page_denied.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_page_denied.php';
		include $dsp_footer;
		break;
	case 'group_denied':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_group_denied.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_group_denied.php';
		include $dsp_footer;
		break;
		
	case 'help':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_help.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_help.php';
		include $dsp_footer;
		break;
		
	case 'add_edit_help':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		$page_edit_name = $_SESSION['CurrentPage']->getPageEditName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_add_edit_help.php';
		include $dsp_header;
		include $dsp_home_header;
		include $BaseIncludeURL.'/dsp/dsp_add_edit_help.php';
		include $dsp_footer;
		break;
		
	/* End Unclassified Secure Pages */
	
	/* Begin Reports / Charts */
	
	/* End Reports / Charts */
	
	/* Begin Ajax */
	case 'search_volunteers':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_volunteers.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_volunteers.php';
		break;
		
	case 'search_volunteers_generic':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_volunteers_generic.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_volunteers_generic.php';
		break;
		
	case 'search_members':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_members.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_members.php';
		break;
	
	case 'search_members_optimised_for_attendance':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_members_optimised_for_attendance.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_members_optimised_for_attendance.php';
		break;
	
	case 'search_members_optimised':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_members_optimised.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_members_optimised.php';
		break;
	
	case 'search_staff_for_attendance':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_staff_for_attendance.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_staff_for_attendance.php';
		break;
	
	case 'search_staff':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_staff.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_staff.php';
		break;
		
	case 'search_staff_generic':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_staff_generic.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_staff_generic.php';
		break;
	
	case 'search_members_generic':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_members_generic.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_members_generic.php';
		break;
	
	case 'search_groups':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_groups.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_groups.php';
		break;
		
	case 'search_groups_improved':
		include $spc_load_page;
		$page_name = $_SESSION['CurrentPage']->getPageName();
		include $spc_security_by_page;
		include $BaseIncludeURL.'/act/act_ajax_search_groups_improved.php';
		include $BaseIncludeURL.'/dsp/dsp_ajax_search_groups_improved.php';
		break;
	/* End Ajax */
	
	//selects default page based upon user type
	case 'select_default_page':
		//
		include $spc_load_page;
		include $spc_security_by_page;
		include $BaseIncludeURL.'/spc/spc_select_default_page.php';
		break;
	case 'log_out':
		include $spc_load_page;
		include $spc_security_by_page;
		//
		include $BaseIncludeURL.'/spc/spc_log_out.php';
		break;
	default:
		$page_name = 'Bad Page ID';
		//insecure page\
		include $dsp_header;
		include $dsp_sign_up_header;
		include $BaseIncludeURL.'/spc/spc_does_not_exist.php';
		include $dsp_splash_footer;
		break;
}

?>