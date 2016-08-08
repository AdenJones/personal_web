	<table class="general" style="width:100%;">
		<tr><th>Search Volunteers <img onclick="jsHidePopUp(window.mem_sel_popup_container)" class="float_right generic_close" src="<?php echo BaseExternalURL; ?>/images/white_close.gif"/></th></tr>
		
<?php
	
	
	
	if(count($Volunteers) == 0)
	{
		echo '<tr><th>No Results</th></tr>'."\n";
	} else {
		
		foreach($Volunteers as $Volunteer)
		{
			$Name = $Volunteer->GetFirstName().' '.$Volunteer->GetLastname();
			
			$this_url = '<a href="#" onclick="jsAddVolunteerGeneric('.$Volunteer->GetUserID().",'".addslashes($Name)."'".')">';
				
			$this_url_end = '</a>';
			
			echo '<tr><td>'.$this_url.$Name.$this_url_end.'</td></tr>'."\n";
		}
	}
		
	
?>

	</table>
