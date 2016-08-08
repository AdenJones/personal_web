	<table class="general" style="width:100%;">
    
    <?php $pop_up_container = 'window.mem_sel_popup_container'.$container_id; ?>
    
		<tr><th>Search Members <img onclick="jsHidePopUp(<?php echo $pop_up_container;?>)" class="float_right generic_close" src="<?php echo BaseExternalURL; ?>/images/white_close.gif"/></th></tr>
		
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
			
			$LastGroupAttended = $Member['fld_group_name'];//->GetGroupName();
			
			
			
			if( $LastGroupAttended == '' )
			{
				$LastGroup = 'No attendances so far!';
				$LastAttendedGroupDate = '';
			} else {
				
				$LastGroup = $Member['fld_group_name'];
				
				$LastAttendedGroupDate = $Member['last_attended'];
				
				if( $LastAttendedGroupDate == '' )
				{
					$LastAttendedGroupDate = 'Error!';
				} 
			}
			
			
			$this_url = '<a href="#" onclick="jsSearchMemberGeneric('.$Member['id_user'].",'".addslashes($Name)."','".$container_id."'".');">';
			$this_url_end = '</a>';
			
			echo '<tr><td>'.$this_url.$Name.$this_url_end.$LastGroup.': '.$LastAttendedGroupDate.'</td></tr>'."\n";
		}
	}
		
	
?>

	</table>
