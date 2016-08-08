<?php
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		
		$Heading = 'Secure';
		$secure = true;
	} else {
		
		$Heading = '';
		$secure = false;
	}
	
	$GroupID = funRqScpVar('id_group','');
	
	$bad_group = $GroupID == '';
	
	$ReturnTo = funRqScpVarNonSafe('return_to','');
	
	if( $ReturnTo != '' )
	{
		$_SESSION['return_to'] = $ReturnTo;
	}
	
	if(!$bad_group)
	{
		$Group = Business\Group::LoadGroup($GroupID);
				
		//jump out if bad user id is entered
		if($Group == NULL)
		{
			$bad_group = true;
		} else {
			
			//State User security
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
				
				$GroupsLastRegion = $Group->LoadGroupsCurrentRegion();
				
				if( $Group->LoadGroupsCurrentRegion() != NULL)
				{
					if( !$Staff->has_region($GroupsLastRegion->GetRegionID()) )
					{
						$bad_group = true;
					}
				}
			}
		}
	}
	
	//all further processing restricted by bad group detection
	if(!$bad_group)
	{
		$GroupsLeaders = $Group->LoadGroupsLeaders();
		
		if( $secure )
		{
			if($_SESSION['return_to'] == 'view_group' )
			{
				$url_return_to_group = '<a href="'.$lnk_view_group.'&id_group='.$Group->GetGroupID().'">'.$Group->GetGroupName().'</a>';
				
			} else {
			
				$url_return_to_group = '<a href="'.$lnk_view_groups_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			
			$url_add_group_leader = '<a href="'.$lnk_add_edit_group_leader.'&id_group='.$GroupID.'">Add Group Leader</a>';
		} else {
			
			if($_SESSION['return_to'] == 'view_group' )
			{
				$url_return_to_group = '<a href="'.$lnk_view_group.'&id_group='.$Group->GetGroupID().'">'.$Group->GetGroupName().'</a>';
				
			} else {
			
				$url_return_to_group = '<a href="'.$lnk_view_groups.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			
			$url_add_group_leader = '';
		}
	}
	
?>