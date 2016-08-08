<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	$search_scope = trim(funRqScpVar('search_scope',''));
	$id_group = intval(funRqScpVar('id_group',''));
	$this_date = trim(funRqScpVar('this_date',''));
	
	
	$MembersUnfiltered = Membership\Member::LoadMembersBySearchString($str_text,false); //auto displays Community Observers
	
	$Members = array();
	
	if( $search_scope == 'group' )
	{
		foreach( $MembersUnfiltered as $Member )
		{
			
			$LastGroup = $Member->GetLastGroupAttended();
			
			if( $LastGroup == NULL or $LastGroup->GetGroupID() == $id_group )
			{
				$Members[] = $Member; 
			}
		}
	} elseif( $search_scope == 'region' )
	{
		foreach( $MembersUnfiltered as $Member )
		{
			$ThisGroup = \Business\Group::LoadGroup($id_group);
			
			if( $ThisGroup != NULL )
			{
				$ThisGroupRegion = $ThisGroup->LoadGroupsRegionByDate($this_date);
			} else {
				$ThisGroupRegion = NULL;
			}
			
			$LastGroup = $Member->GetLastGroupAttended();
			
			if( $LastGroup != NULL )
			{
				$GroupRegion = $LastGroup->LoadGroupsRegionByDate($this_date);
			} else {
				$GroupRegion = NULL;
			}
			
			if( ($ThisGroup == NULL or $LastGroup == NULL) or ($ThisGroupRegion == NULL or $GroupRegion == NULL ) or ($ThisGroupRegion->GetRegionID() == $GroupRegion->GetRegionID()) )
			{
				$Members[] = $Member; 
			}
		}
		
	} elseif( $search_scope == 'state' )
	{
		
		foreach( $MembersUnfiltered as $Member )
		{
			$ThisGroup = \Business\Group::LoadGroup($id_group);
			
			if( $ThisGroup != NULL )
			{
				$ThisGroupRegion = $ThisGroup->LoadGroupsRegionByDate($this_date);
				
			} else {
				$ThisGroupRegion = NULL;
			}
			
			$LastGroup = $Member->GetLastGroupAttended();
			
			if( $LastGroup != NULL )
			{
				$GroupRegion = $LastGroup->LoadGroupsRegionByDate($this_date);
			} else {
				$GroupRegion = NULL;
			}
			
			
			
			if( ($ThisGroup == NULL or $LastGroup == NULL) or ($ThisGroupRegion == NULL or $GroupRegion == NULL ) or ($ThisGroupRegion->GetRegion()->GetBranchID() == $GroupRegion->GetRegion()->GetBranchID()) )
			{
				$Members[] = $Member; 
			}
		}
		
	} elseif( $search_scope == 'all' )
	{
		$Members = $MembersUnfiltered;
	}
	
?>

