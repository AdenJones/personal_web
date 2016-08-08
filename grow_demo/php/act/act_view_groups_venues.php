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
				
				if( $_SESSION['User']->GetUserTypeName() == $StateUser )
				{
					if(!$Staff->IsMyGroup($GroupID))
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
		$GroupsVenues = $Group->LoadGroupsVenues();
		
		if( $secure )
		{
			if( $non_group_type == NON_GROUP_TEAM )
			{
				$page_name = 'View Teams Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_teams_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_HOS_OR )
			{
				$page_name = 'View Hospital Orientations Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_hospital_orientations_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_SOC_EV )
			{
				$page_name = 'View Social Events Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_social_events_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_COM_OUT )
			{
				$page_name = 'View Community Outreach Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_community_outreach_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_TRAIN )
			{
				$page_name = 'View Tranining Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_training_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else
			{
				if($_SESSION['return_to'] == 'view_group' )
				{
					$url_return_to_group = '<a href="'.$lnk_view_group.'&id_group='.$Group->GetGroupID().'">'.$Group->GetGroupName().'</a>';
					
				} else {
				
					$url_return_to_group = '<a href="'.$lnk_view_groups_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				}
				
			}
			
			$url_add_group_venue = '<a href="'.$lnk_add_edit_group_venue.'&id_group='.$GroupID.'">Add Venue Record</a>';
			
			
		} else {
			
			if( $non_group_type == NON_GROUP_TEAM )
			{
				$page_name = 'View Teams Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_teams.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_HOS_OR )
			{
				$page_name = 'View Hospital Orientations Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_hospital_orientations.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_SOC_EV )
			{
				$page_name = 'View Social Events Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_social_events.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_COM_OUT )
			{
				$page_name = 'View Community Outreach Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_community_outreach.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else if( $non_group_type == NON_GROUP_TRAIN )
			{
				$page_name = 'View Training Venue History';
				
				$url_return_to_group = '<a href="'.$lnk_view_training.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
			}
			else
			{
				if($_SESSION['return_to'] == 'view_group' )
				{
					$url_return_to_group = '<a href="'.$lnk_view_group.'&id_group='.$Group->GetGroupID().'">'.$Group->GetGroupName().'</a>';
					
				} else {
				
					$url_return_to_group = '<a href="'.$lnk_view_groups.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				}
				
			}
			
			$url_add_group_venue = '';
			
		}
	}
	
?>