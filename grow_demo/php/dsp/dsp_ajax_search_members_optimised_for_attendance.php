	<table class="general" style="width:100%;">
		<tr><th>Search Members <img onclick="jsHidePopUp(window.mem_sel_popup_container)" class="float_right generic_close" src="/grow_demo_html/images/white_close.gif"/></th></tr>
		
<?php
	
	
	
	if(count($arrMembers) == 0)
	{
		echo '<tr><th>No Results</th></tr>'."\n";
	} else {
		
		foreach($arrMembers as $Member)
		{
			$Name = $Member['fld_first_name'].' '.$Member['fld_last_name'];
			
			$isCommitted = $Member['is_committed'] != '';
			
			$strCommitted = ($isCommitted ? 'true' : 'false' );
			
			//$LastAtt = Business\getLastGroupAttended($Member['id_user'],$this_date)->fetch();
			
			$LastGroupAttended = $Member['group_name'];//->GetGroupName();
			
			
			
			if( $LastGroupAttended == '' )
			{
				$LastGroup = 'No attendances so far!';
				$LastAttendedGroupDate = '';
			} else {
				
				$LastGroup = $LastGroupAttended;
				
				$LastAttendedGroupDate = $Member['last_attended'];
				
				if( $LastAttendedGroupDate == '' )
				{
					$LastAttendedGroupDate = 'Error!';
				} 
			}
			
			
			
			$this_url = '<a href="#" onclick="jsAddMemberGeneric('.$Member['id_user'].",'".addslashes($Name)."',".$strCommitted.');">';
				
			$this_url_end = '</a>';
			
			echo '<tr><td>'.$this_url.$Name.$this_url_end.$LastGroup.': '.$LastAttendedGroupDate.'</td></tr>'."\n";
		}
	}
		
	
?>

	</table>
