	<table class="general" style="width:100%;">
		<tr><th>Search Members <img onclick="jsHidePopUp(window.mem_sel_popup_container)" class="float_right generic_close" src="/grow_demo_html/images/white_close.gif"/></th></tr>
		
<?php
	
	
	
	if(count($Members) == 0)
	{
		echo '<tr><th>No Results</th></tr>'."\n";
	} else {
		
		foreach($Members as $Member)
		{
			$Name = $Member->GetFirstName().' '.$Member->GetLastname();
			
			$isCommitted = $Member->GetCommitted($this_date) == $true;
			
			$strCommitted = ($isCommitted ? 'true' : 'false' );
			
			$LastGroupAttended = $Member->GetLastGroupAttended();//->GetGroupName();
			
			
			
			if( $LastGroupAttended == NULL )
			{
				$LastGroup = 'No attendances so far!';
				$LastAttendedGroupDate = '';
			} else {
				$LastGroup = $LastGroupAttended->GetGroupName();
				
				$LastAttendedGroupDate = $LastGroupAttended->GetMemberLastAttendedGroup($Member->GetUserID());
				
				if( $LastAttendedGroupDate == NULL )
				{
					$LastAttendedGroupDate = 'Error!';
				} 
			}
			
			
			
			$this_url = '<a href="#" onclick="jsAddMemberGeneric('.$Member->GetUserID().",'".addslashes($Name)."',".$strCommitted.');">';
				
			$this_url_end = '</a>';
			
			echo '<tr><td>'.$this_url.$Name.$this_url_end.$LastGroup.': '.$LastAttendedGroupDate.'</td></tr>'."\n";
		}
	}
		
	
?>

	</table>
