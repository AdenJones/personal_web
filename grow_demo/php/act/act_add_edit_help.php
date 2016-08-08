<?php 
/* Action file for editing staff details */
	
	//initialise errors array outside of val to supress warnings
	$arrErrors = array();
	
	$PageID = funRqScpVar('help_for','');
	$ReturnTo = funRqScpVar('return_to','');
	
	$intSubmitted = funRqScpVar('form_submitted','');
	
	//local variables
	$this_year = date('Y');
	$bdYRange = ($this_year-120).':'.$this_year;
	$bad_page = $PageID == '';
	
	if( !$bad_page )
	{
		$Page = \Pages\Page::LoadPage($PageID);
		
		$bad_page = $Page == NULL;
		
		if( !$bad_page )
		{
			
			if( trim($Page->getPageHelp()) != '' )
			{
				$page_name = $page_edit_name;;
			}
			
			$PageHelp = funRqScpVarNonSafe('PageHelp',$Page->getPageHelp());
			
			//if the form has been submitted
			if($intSubmitted == 1)
			{
				$blnIsGood = true;
				
				Validation\CreateErrorMessage(Validation\ValidateString($PageHelp,0,5000),'Page Help');
						
				//if all values are good
				if($blnIsGood)
				{
					$Page->UpdateHelp($PageHelp);
					
					Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$Page->getPageID(),CHANGE_UPDATE,'tbl_pages');
					
					$link = $lnk_view_help_secure.'&help_for='.$PageID.'&return_to='.urlencode($ReturnTo);
								
					//redirect to view page
					header( "Location: $link" );
					//ensure no further processing is performed
					exit;
				} else {
					$msg_general_notifier = 'There are errors in this help record!';
				}
			} //end if submitted
		}//end second page check
	}// end first page check
	
?>