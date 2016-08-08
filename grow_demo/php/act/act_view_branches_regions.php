<?php
	
	//sticking to group due to many types using this tool
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
			
			$NonGroupType = $Group->GetNonGroupType();
			
			if( $NonGroupType == NON_GROUP_TEAM )
			{
				$SpecialGroup = Business\Team::LoadTeam($GroupID); //allows for calling team specific methods
			}
			else if( $NonGroupType == NON_GROUP_HOS_OR )
			{
				$SpecialGroup = Business\HospitalOrientation::Load($GroupID);
			}
			else if( $NonGroupType == NON_GROUP_SOC_EV )
			{
				$SpecialGroup = Business\SocialEvent::Load($GroupID);
			}
			else if( $NonGroupType == NON_GROUP_COM_OUT )
			{
				$SpecialGroup = Business\CommunityOutreach::Load($GroupID);
			}
			else if( $NonGroupType == NON_GROUP_TRAIN )
			{
				$SpecialGroup = Business\Training::Load($GroupID);
			}
			
			//State User security - will probably need to update in accord with new security
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
	
	if(!$bad_group)
	{
	
		$allow_deletes = (  $_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin) ;
		
		if( $allow_deletes )
		{
			$DelGroupRegionID = funRqScpVar('int_del_grp_rgn_id','');
			
			if( $DelGroupRegionID != '' )
			{
				$GroupRegionToDel = \Business\GroupRegion::LoadGroupRegion($DelGroupRegionID);
				
				if( $GroupRegionToDel != NULL )
				{
					
					if($GroupRegionToDel->GetGroupID() == $GroupID)
					{
						$GroupRegionToDel->Delete();
						
						Auditing\ChangeLog::CreateChangeLog($_SESSION['User']->GetUserID(),$page_id,$GroupRegionToDel->GetGroupRegionID(),CHANGE_DELETE,'tbl_groups_regions');
					}
					
					
				}
			}
		}
	}
	
	if(isset($_REQUEST['access']) and $_REQUEST['access'] == 'secure')
	{
		
		$Heading = 'Secure';
		$secure = true;
	} else {
		
		$Heading = '';
		$secure = false;
	}
	
	$returnAddress = urlencode($lnk_view_branches_regions_secure.'&id_group='.$GroupID);
	
	//all further processing restricted by bad group detection
	if(!$bad_group)
	{
		$GroupsRegions = $Group->LoadGroupsRegions();
		
		if( $secure )
		{
			if( $NonGroupType == NON_GROUP_TEAM )
			{
				$url_return_to_group = '<a href="'.$lnk_view_teams_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '<a href="'.$lnk_add_edit_branch_region.'&id_group='.$GroupID.'&return_address='.$returnAddress.'">Add Branch / Region Record</a>';
			}
			else if( $NonGroupType == NON_GROUP_HOS_OR )
			{
				$url_return_to_group = '<a href="'.$lnk_view_hospital_orientations_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '<a href="'.$lnk_add_edit_branch_region.'&id_group='.$GroupID.'&return_address='.$returnAddress.'">Add Branch / Region Record</a>';
			}
			else if( $NonGroupType == NON_GROUP_SOC_EV )
			{
				$url_return_to_group = '<a href="'.$lnk_view_social_events_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '<a href="'.$lnk_add_edit_branch_region.'&id_group='.$GroupID.'&return_address='.$returnAddress.'">Add Branch / Region Record</a>';
			}
			else if( $NonGroupType == NON_GROUP_COM_OUT )
			{
				$url_return_to_group = '<a href="'.$lnk_view_community_outreach_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '<a href="'.$lnk_add_edit_branch_region.'&id_group='.$GroupID.'&return_address='.$returnAddress.'">Add Branch / Region Record</a>';
			}
			else if( $NonGroupType == NON_GROUP_TRAIN )
			{
				$url_return_to_group = '<a href="'.$lnk_view_training_secure.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '<a href="'.$lnk_add_edit_branch_region.'&id_group='.$GroupID.'&return_address='.$returnAddress.'">Add Branch / Region Record</a>';
			}
		} else {
			if( $NonGroupType == NON_GROUP_TEAM )
			{
				$url_return_to_group = '<a href="'.$lnk_view_teams.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '';
			}
			else if( $NonGroupType == NON_GROUP_HOS_OR )
			{
				$url_return_to_group = '<a href="'.$lnk_view_hospital_orientations.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '';
			}
			else if( $NonGroupType == NON_GROUP_SOC_EV )
			{
				$url_return_to_group = '<a href="'.$lnk_view_social_events.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '';
			}
			else if( $NonGroupType == NON_GROUP_COM_OUT )
			{
				$url_return_to_group = '<a href="'.$lnk_view_community_outreach.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '';
			}
			else if( $NonGroupType == NON_GROUP_TRAIN )
			{
				$url_return_to_group = '<a href="'.$lnk_view_training.'#group_'.$GroupID.'">'.$Group->GetGroupName().'</a>';
				$url_add_group_region = '';
			}
		}
	}
	
?>