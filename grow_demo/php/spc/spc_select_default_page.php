<?php
/*
	Selects default page based
	upon user type
*/

	
	$this_message = funRqScpVar('msg_general_notifier',''); //assumes url encoding
	
	$append_message = '';
	
	if($this_message != '')
	{
		$append_message = '&msg_general_notifier='.$this_message;
	}
	
	$link_target_page = "$full_uri/index.php?page_id=".$_SESSION['User']->GetDefaultPage().$append_message;

	header( "Location: $link_target_page" );
	exit;



?>

