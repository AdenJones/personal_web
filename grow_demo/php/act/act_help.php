<?php
	
	$PageID = funRqScpVar('help_for','');
	$ReturnTo = funRqScpVar('return_to','');
	
	$bad_help_page = $PageID == '';
	
	if( !$bad_help_page )
	{
		$Page = \Pages\Page::LoadPage($PageID);
		
		$bad_help_page = $Page == NULL;
		
		$ReturnLink = '<a href="'.html_entity_decode(urldecode($ReturnTo)).'">Return</a>';
		
		if( !$bad_help_page )
		{
			if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
			{
				$secure = true;
				
				if( $Page->getPageHelp() == '' )
				{
					$Name = 'Add';
				} else {
					$Name = 'Edit';
				}
				$edit = '<a href="'.$lnk_add_edit_help.'&help_for='.$PageID.'&return_to='.urlencode($ReturnTo).'">'.$Name.' help</a>';
			} else {
				$secure = false;
				$edit = '';
			}
		}
		
		
	}
	
?>