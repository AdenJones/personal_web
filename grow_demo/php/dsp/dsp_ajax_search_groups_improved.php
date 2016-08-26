	<table class="general" style="width:100%;">
		<tr><th>Search Groups <img onclick="jsHidePopUp(window.grp_sel_popup_container)" class="float_right generic_close" src="/grow_demo_html/images/white_close.gif"/></th></tr>
		
<?php
	
	
	
	if(count($Groups) == 0)
	{
		echo '<tr><th>No Results</th></tr>'."\n";
	} else {
		
		foreach($Groups as $Group)
		{
			
			$GroupsCurrentRegion = $Group->LoadGroupsCurrentRegion();
			
			if( $GroupsCurrentRegion == NULL )
			{
				$GroupRegionName = 'Not Set!';
				
			} else {
				$GroupRegionName = $GroupsCurrentRegion->GetRegion()->GetRegionName();
			}
			
			$Name = $Group->GetGroupName().": ".$GroupRegionName;
			
			$this_url = '<a href="#" onclick="jsAddGroupGeneric('.$Group->GetGroupID().",'".$Name."'".');">';
				
			$this_url_end = '</a>';
			
			echo '<tr><td>'.$this_url.$Name.$this_url_end.'</td></tr>'."\n";
		}
	}
		
	
?>

	</table>
