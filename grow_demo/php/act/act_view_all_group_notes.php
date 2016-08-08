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
					if( $Staff->GetBranch()->GetBranchAbbreviation() != $GroupsLastRegion->GetRegion()->GetBranch()->GetBranchAbbreviation() )
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
		
		if( $secure )
		{
			$url_return_to_group = '<a href="'.$lnk_view_groups_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			
		} else {
			$url_return_to_group = '<a href="'.$lnk_view_groups.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			
		}
		
		$dates_per_page = 15;
		$rows_per_column = 5;
		
		$Dates = $Group->LoadGroupDates();
		
		
		
		/*$arr_dates = funGetGroupDates($id_group);	
		$obj_group_reflections = getGroupReflections($id_group);
		$arr_group_reflections = $obj_group_reflections->fetchAll();
		*/
		
	}
	
?>