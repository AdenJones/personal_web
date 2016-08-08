<?php
	
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		$url_add_group_type = '<a href="'.$lnk_add_edit_group_type.'">Add Group Type</a>';
		$Heading = 'Secure';
		$secure = true;
	} else {
		$url_add_group_type = '';
		$Heading = '';
		$secure = false;
	}
	
	$GroupTypes = Business\GroupType::LoadGroupTypes();
	
	
	
?>