<?php
	//can load code into here for storing page histories and navigating through them
	$_SESSION['CurrentPage'] = \Pages\Page::LoadPage($page_id);
	
	$return_address = funRqScpVarNonSafe('return_address','');
	
	if($return_address != '')
	{
		$_SESSION['ReturnAddress'] = urldecode($return_address);
	}
	
	//Code for passing around messages
	$this_message = funRqScpVar('msg_general_notifier',''); //assumes url encoding
	
	if($this_message != '')
	{
		$this_message = urldecode($this_message);
		
		$msg_general_notifier = $this_message;
	}
?>