<?php
	
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_staff = '<a href="'.$lnk_add_edit_staff.'">Add Staff</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_staff = '';
		$Heading = '';
		$secure = false;
	}
	
	if( $_SESSION['User']->GetUserTypeName() == $StateUser )
	{
		$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
		
		$arrActiveVolunteers = Membership\Staff::load_volunteers_by_branches($_SESSION['User']->GetUserID());
		$arrArchivedVolunteers = Membership\Staff::load_archived_volunteers_by_branches($_SESSION['User']->GetUserID());
		$arrActiveStaff = array();
		$arrArchivedStaff = array();
	} else {
		$arrActiveStaff = Membership\Staff::LoadActiveStaff();
		$arrArchivedStaff = Membership\Staff::LoadArchivedStaff();
		$arrActiveVolunteers = Membership\Staff::LoadActiveVolunteers();
		$arrArchivedVolunteers =  Membership\Staff::LoadArchivedVolunteers();
	}
	
	
?>