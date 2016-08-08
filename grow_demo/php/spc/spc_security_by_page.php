<?php
/*
	Security By Page
	This page controls
	whether or not a user
	is allowed access to a
	given page.
	Redirects to default page
	if any attempt is intercepted.
	
*/
	
		
		//will need to add &view=self functionality once I have created the relevent db records
		
		//check that the user is allowed access to the current page
		$this_page = $_REQUEST['page_id'];
		
		if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
		{
			$this_page .= '&access=secure';
		}
		
		if(isset($_REQUEST['view']) and $_REQUEST['view'] == 'self')
		{
			$this_page .= '&view=self';
		}
		
		//echo $this_page;
		
		//reload user pages
		$_SESSION['User']->LoadUserPages();
		
		if($_SESSION['User']->CheckUserPage($this_page) == false)
		{
			//redirect to default page
			header( "Location: $lnk_page_denied" );
			//ensure no further processing is performed
			exit;
					
		}
		
		$GroupID = funRqScpVar('id_group','');
		
		if( $GroupID != '' )
		{
			if($_SESSION['User']->CheckUserGroup($GroupID) == false )
			{
				//redirect to default page
			header( "Location: $lnk_group_denied" );
			//ensure no further processing is performed
			exit;
			}
		}
	
	
?>