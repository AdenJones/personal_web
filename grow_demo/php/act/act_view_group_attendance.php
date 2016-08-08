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
			
			$non_group_type = $Group->GetNonGroupType();
						
			//State User security
			if( $_SESSION['User']->GetUserTypeName() == $StateUser )
			{
				$Staff = Membership\Staff::LoadStaff($_SESSION['User']->GetUserID());
				
				if(!$Staff->IsMyGroup($GroupID))
				{
					$bad_group = true;
				}
				
			}
		}
	}
	
	//all further processing restricted by bad group detection
	if(!$bad_group)
	{
		
		if( $secure )
		{
			if( $non_group_type == NON_GROUP_TEAM )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_teams_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_HOS_OR )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_hospital_orientations_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_SOC_EV )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_social_events_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_COM_OUT )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_community_outreach_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_TRAIN )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_training_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else
			{
				if(array_key_exists('return_to',$_SESSION) and $_SESSION['return_to'] == 'view_group' )
				{
					$url_return_to_group = '<a href="'.$lnk_view_group.'&id_group='.$Group->GetGroupID().'">'.$Group->GetGroupName().'</a>';
					
				} else {
				
					$url_return_to_group = '<a href="'.$lnk_view_groups_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				}
				
				
				
			}
			
			
		} else {
			
			if( $non_group_type == NON_GROUP_TEAM )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_teams.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_HOS_OR )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_hospital_orientations.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_SOC_EV )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_social_events.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_COM_OUT )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_community_outreach.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_TRAIN )
			{
				
				$url_return_to_group = '<a href="'.$lnk_view_training.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else
			{
				if(array_key_exists('return_to',$_SESSION) and $_SESSION['return_to'] == 'view_group' )
				{
					$url_return_to_group = '<a href="'.$lnk_view_group.'&id_group='.$Group->GetGroupID().'">'.$Group->GetGroupName().'</a>';
					
				} else {
				
					$url_return_to_group = '<a href="'.$lnk_view_groups.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				}
				
				
				
			}
			
			
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