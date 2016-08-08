	<table class="general" style="width:100%;">
		<tr><th>Search Staff <img onclick="jsHidePopUp(window.mem_sel_popup_container)" class="float_right generic_close" src="<?php echo BaseExternalURL; ?>/images/white_close.gif"/></th></tr>
		
<?php
	
	
	
	if(count($Staff) == 0)
	{
		echo '<tr><th>No Results</th></tr>'."\n";
	} else {
		
		foreach($Staff as $thisStaff)
		{
			$Name = $thisStaff->GetFirstName().' '.$thisStaff->GetLastname();
			
			$this_url = '<a href="#" onclick="jsAddVolunteerGeneric('.$thisStaff->GetUserID().",'".addslashes($Name)."'".')">';
				
			$this_url_end = '</a>';
			
			echo '<tr><td>'.$this_url.$Name.$this_url_end.'</td></tr>'."\n";
		}
	}
		
	
?>

	</table>
