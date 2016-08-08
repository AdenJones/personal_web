<?php 

function cntRegionBranchSelector($ControlID,$ReportRanges,$TypeSelected,$Group,$GroupID,$Regions,$RegionID,$arr_branches,$Branch)
{
	
		echo '<select onchange="localShowHideRanges('."'".$ControlID.'_select'."'".')" id="'.$ControlID.'_select" name="'.$ControlID.'_select">';
		echo 	'<option value="SELECT">Select Range</option>';
		foreach($ReportRanges as $Range)
		{
			$Selected = $TypeSelected == $Range['key'] ? 'selected' : '';
			
			echo 	'<option '.$Selected.' value="'.$Range['key'].'">'.$Range['name'].'</option>';
			
		}
		echo '</select>';
		
		foreach($ReportRanges as $Range)
		{
			
			if($Range['key'] == REGION)
			{
				cntRegionDropDown('Region','RegionID','id_region','fld_branch_abbreviation','fld_region_name',$Regions,'Region',$RegionID);
			}elseif($Range['key'] == BRANCH)
			{
				cntDropDown('Branch:','Branch','id_branch','fld_branch_name',$arr_branches,'Branch',$Branch);
			}
		}
		
   
   echo '<script type="text/javascript">';
   	foreach($ReportRanges as $Range)
	{
		
		if($Range['key'] == REGION and $TypeSelected != REGION)
		{
			echo 'document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'none';"."\n";
		}elseif($Range['key'] == BRANCH and $TypeSelected != BRANCH)
		{
			echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'none';"."\n";
			
		}
	}
	
	if( $TypeSelected == REGION )
	{
		echo 'document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'block';"."\n";
	}elseif( $TypeSelected == BRANCH )
	{
		echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'block';"."\n";
	}
					
	echo ' function localShowHideRanges(ControlID)'."\n";
	echo '{'."\n";	
	echo 'target = document.getElementById(ControlID).value'."\n";			
	foreach($ReportRanges as $Range)
	{		
			
			if($Range['key'] == REGION)
			{
				echo 'if(target == "'.REGION.'")'."\n";
				echo '{document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'block';"."\n";
				echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'none';}"."\n";
			}elseif($Range['key'] == BRANCH)
			{
				echo 'if(target == "'.BRANCH.'")'."\n";
				echo '{document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'none';"."\n";
				echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'block';}"."\n";
				
			}
	}
	echo '}'."\n";
   	echo '</script>';
	
}

function cntGroupRegionBranchSelector($ControlID,$ReportRanges,$TypeSelected,$Group,$GroupID,$Regions,$RegionID,$arr_branches,$Branch)
{
	
		echo '<select onchange="localShowHideRanges('."'".$ControlID.'_select'."'".')" id="'.$ControlID.'_select" name="'.$ControlID.'_select">';
		echo 	'<option value="SELECT">Select Range</option>';
		foreach($ReportRanges as $Range)
		{
			$Selected = $TypeSelected == $Range['key'] ? 'selected' : '';
			
			echo 	'<option '.$Selected.' value="'.$Range['key'].'">'.$Range['name'].'</option>';
			
		}
		echo '</select>';
		
		foreach($ReportRanges as $Range)
		{
			
			if($Range['key'] == GROUP)
			{
				cntGroupSelectorImproved('Group Selector','Group','GroupID',$Group,$GroupID,'Group');
			}elseif($Range['key'] == REGION)
			{
				cntRegionDropDown('Region','RegionID','id_region','fld_branch_abbreviation','fld_region_name',$Regions,'Region',$RegionID);
			}elseif($Range['key'] == BRANCH)
			{
				cntDropDown('Branch:','Branch','id_branch','fld_branch_name',$arr_branches,'Branch',$Branch);
			}
		}
		
   
   echo '<script type="text/javascript">';
   	foreach($ReportRanges as $Range)
	{
		
		if($Range['key'] == GROUP and $TypeSelected != GROUP )
		{
			echo 'document.getElementById('."'".'Group_parent_div'."'".').style.display ='."'none';"."\n";
		}elseif($Range['key'] == REGION and $TypeSelected != REGION)
		{
			echo 'document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'none';"."\n";
		}elseif($Range['key'] == BRANCH and $TypeSelected != BRANCH)
		{
			echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'none';"."\n";
			
		}
	}
	
	if( $TypeSelected == GROUP )
	{
		echo 'document.getElementById('."'".'Group_parent_div'."'".').style.display ='."'block';"."\n";
	}elseif( $TypeSelected == REGION )
	{
		echo 'document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'block';"."\n";
	}elseif( $TypeSelected == BRANCH )
	{
		echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'block';"."\n";
	}
					
	echo ' function localShowHideRanges(ControlID)'."\n";
	echo '{'."\n";				
	foreach($ReportRanges as $Range)
	{		
			echo 'target = document.getElementById(ControlID).value'."\n";
			if($Range['key'] == GROUP)
			{
				echo 'if(target == "'.GROUP.'")'."\n";
				echo '{document.getElementById('."'".'Group_parent_div'."'".').style.display ='."'block';"."\n";
				echo 'document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'none';"."\n";
				echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'none';}"."\n";
			}elseif($Range['key'] == REGION)
			{
				echo 'if(target == "'.REGION.'")'."\n";
				echo '{document.getElementById('."'".'Group_parent_div'."'".').style.display ='."'none';"."\n";
				echo 'document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'block';"."\n";
				echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'none';}"."\n";
			}elseif($Range['key'] == BRANCH)
			{
				echo 'if(target == "'.BRANCH.'")'."\n";
				echo '{document.getElementById('."'".'Group_parent_div'."'".').style.display ='."'none';"."\n";
				echo 'document.getElementById('."'".'RegionIDparent_div'."'".').style.display ='."'none';"."\n";
				echo 'document.getElementById('."'".'Branchparent_div'."'".').style.display ='."'block';}"."\n";
				
			}
	}
	echo '}'."\n";
   	echo '</script>';
	
}

function cntFindGroup($str_group_name,$int_group_hidden_input)
{
	
	global $full_uri;
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="find_group" />';
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		
		cntGroupSelectorImproved('Select Group','str_group_name','int_group_hidden_input',$str_group_name,$int_group_hidden_input,'Group Selection');
		
		echo '<input class="button" type="submit" value="View Group" />';
   echo '</form>';
}

function cntGroupSelectorImproved($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	global $full_uri;
	
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.grp_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsSelectGroupPopUpImproved(window.grp_sel_popup_container,'."'".$strName."','".$full_uri."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'Top Records: <select onchange="jsSelectGroupPopUpImproved(window.grp_sel_popup_container,'."'".$strName."'".')" id="record_limit">';
	echo funCreateTabs(2).'<option value="10">10</option>';
	echo funCreateTabs(2).'<option value="25">25</option>';
	echo funCreateTabs(2).'<option value="50">50</option>';
	echo funCreateTabs(2).'<option value="100">100</option>';
	echo funCreateTabs(2).'<option value="all">all</option>';
	echo funCreateTabs(1).'</select>';
	echo funCreateTabs(1).'Limit To: <select onchange="jsSelectGroupPopUpImproved(window.grp_sel_popup_container,'."'".$strName."'".')" id="extra">';
	echo funCreateTabs(2).'<option value="all">All Groups</option>';
	echo funCreateTabs(2).'<option value="current">Current Groups</option>';
	echo funCreateTabs(2).'<option value="archive">Archived Groups</option>';
	echo funCreateTabs(1).'</select>';
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.grp_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntStaffSelectImproved($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$Staff = Membership\Staff::LoadStaff($str_hidden_input_def);
		$strDefVal = $Staff->GetFirstName().' '.$Staff->GetLastName();
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsFindStaffPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'Top Records: <select onchange="jsFindStaffPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" id="record_limit">';
	echo funCreateTabs(2).'<option value="10">10</option>';
	echo funCreateTabs(2).'<option value="25">25</option>';
	echo funCreateTabs(2).'<option value="50">50</option>';
	echo funCreateTabs(2).'<option value="100">100</option>';
	echo funCreateTabs(2).'<option value="all">all</option>';
	echo funCreateTabs(1).'</select>';
	echo funCreateTabs(1).'Limit To: <select onchange="jsFindStaffPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" id="extra">';
	echo funCreateTabs(2).'<option value="all">All Staff</option>';
	echo funCreateTabs(2).'<option value="current">Current Staff</option>';
	echo funCreateTabs(2).'<option value="archive">Archived Staff</option>';
	echo funCreateTabs(1).'</select>';
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntFindStaff($str_staff,$int_staff_hidden_input)
{
	
	global $full_uri;
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="find_staff" />';
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		cntStaffSelectImproved('Select Staff','str_staff','int_staff_hidden_input',$str_staff,$int_staff_hidden_input,'Staff Selection','45');
		
		echo '<input class="button" type="submit" value="View Staff" />';
   echo '</form>';
}
		
function cntVolunteerSelectImproved($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$Volunteer = Membership\Staff::LoadStaff($str_hidden_input_def);
		$strDefVal = $Volunteer->GetFirstName().' '.$Volunteer->GetLastname();
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsFindVolunteerPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'Top Records: <select onchange="jsFindVolunteerPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" id="record_limit">';
	echo funCreateTabs(2).'<option value="10">10</option>';
	echo funCreateTabs(2).'<option value="25">25</option>';
	echo funCreateTabs(2).'<option value="50">50</option>';
	echo funCreateTabs(2).'<option value="100">100</option>';
	echo funCreateTabs(2).'<option value="all">all</option>';
	echo funCreateTabs(1).'</select>';
	echo funCreateTabs(1).'Limit To: <select onchange="jsFindVolunteerPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" id="extra">';
	echo funCreateTabs(2).'<option value="all">All Volunteers</option>';
	echo funCreateTabs(2).'<option value="current">Current Volunteers</option>';
	echo funCreateTabs(2).'<option value="archive">Archived Volunteers</option>';
	echo funCreateTabs(1).'</select>';
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntFindVolunteer($str_vol,$int_vol_hidden_input)
{
	
	global $full_uri;
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="find_vol" />';
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		cntVolunteerSelectImproved('Select Volunteer','str_vol','int_vol_hidden_input',$str_vol,$int_vol_hidden_input,'Volunteer Selection','45');
		
		echo '<input class="button" type="submit" value="View Volunteer" />';
   echo '</form>';
}

function cntLoading($strTitle)
{
	global $full_uri;
	echo '<img id="'.$strTitle.'_Loading" src="'.$full_uri.'/images/Loading.gif" style="display:none;margin-right:auto;margin-left:auto;position:relative;top:50%;transform:translateY(-50%);" />';
}

function cntAddEditAttendees($strTitle,$strName,$strDefVal,$strErrName,$width = '6')
{
	global $GroupID;
	global $Date;
	
	global $full_uri;
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="add_edit_group_attendance" />';
		echo '<input type="hidden" name="id_group" value="'.$GroupID.'" />';   
		echo '<input type="hidden" name="date" value="'.$Date.'" />';
		echo '<input type="hidden" name="add" value="externalAttendance" />'; 
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		
		cntNumberWithNotification($strTitle,$strName,$strDefVal,$strErrName,$width);
		
		echo '<input class="button" type="submit" value="Update '.$strTitle.'" />';
   echo '</form>';
}


function cntSelectBranchOrRegion(
									$str_branch_select,$str_branch_key,$str_branch_value,$arr_branch_values,$int_branch_selected,
									$str_region_select,$str_region_key,$str_region_branch_value,$str_region_value,$arr_region_values,$int_region_selected
									)
{
	
	//($str_member_select,$int_member_select,$str_member,$int_member_hidden_input,$str_staff_select,$int_staff_select,$str_staff,$int_staff_hidden_input)
	$div_branch_id = $str_branch_select.'parent_div'; //grab the parent member div to control display
	$div_region_id = $str_region_select.'parent_div';
	
	//grab the arrays
	
	global $str_by_branch_or_region;
	
	$by_region = $str_by_branch_or_region == 'by_region' ? 'selected' : '';
	$by_branch = $str_by_branch_or_region == 'by_branch' ? 'selected' : '';
	
	echo '<div class="form_heading"><h2>Branch / Region</h2></div>';
	echo '<div class="form_item">'."\n";
	echo funCreateTabs(1).'<select onchange="jsShowHideBranchesRegions(this,'."'".$div_branch_id."','".$div_region_id."'".')" id="str_by_branch_or_region" name="str_by_branch_or_region">'."\n";
	echo funCreateTabs(2).'<option '.$by_region.' value="by_region">By Region</option>'."\n";
	echo funCreateTabs(2).'<option '.$by_branch.' value="by_branch">By Branch</option>'."\n";
	echo funCreateTabs(1).'</select>'."\n";
	echo '</div>'."\n";
	
	cntDropDown('By Branch',$str_branch_select,$str_branch_key,$str_branch_value,$arr_branch_values,'Branch Selection',$int_branch_selected);
	cntRegionDropDown('By Region',$str_region_select,$str_region_key,$str_region_branch_value,$str_region_value,$arr_region_values,'Region Selection',$int_region_selected);
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'jsShowHideBranchesRegions(document.getElementById('."'str_by_branch_or_region'".'),'."'".$div_branch_id."','".$div_region_id."'".');'."\n";
	//echo funCreateTabs(1).'document.getElementById("'.$div_member_id.'").style.display = "none" '."\n";
	echo '</script>'."\n";
}

function cntAddEditAutoInserter($User) //Takes a User Object (Staff Or Member)
{
	global $GroupID;
	global $Date;
	global $full_uri;
	
	$Name = $User->GetFirstName().' '.$User->GetLastname();
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="add_edit_group_attendance" />';
		echo '<input type="hidden" name="id_group" value="'.$GroupID.'" />';   
		echo '<input type="hidden" name="date" value="'.$Date.'" />';
		echo '<input type="hidden" name="add" value="staff" />'; 
		echo '<input type="hidden" name="with_user" value="'.$User->GetUserID().'" />'; 
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		echo '<input type="hidden" id="int_staff_hidden_input_'.$User->GetUserID().'" name="int_staff_hidden_input_'.$User->GetUserID().'" value="'.$User->GetUserID().'" />'."\n";
		echo '<div class="form_item_major_block_alternate_color">';
			echo '<div class="form_row">';
				echo '<div class="form_heading"><h2>Name: '.$Name.'</h2></div>';
				echo '<div class="form_item spaced"><span class="label"Gender: </span> '.$User->GetGenderName().'</div>';
				echo '<input class="button" type="submit" value="Add Staff/Volunteer" />';
			echo '</div>';
		echo '</div>';
		
   echo '</form>';
}

function cntAddEditAutoInserterMember($User) //Takes a User Object (Staff Or Member)
{
	global $GroupID;
	global $Date;
	global $full_uri;
	
	$Name = $User->GetFirstName().' '.$User->GetLastname();
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="add_edit_group_attendance" />';
		echo '<input type="hidden" name="id_group" value="'.$GroupID.'" />';   
		echo '<input type="hidden" name="date" value="'.$Date.'" />';
		echo '<input type="hidden" name="add" value="member" />'; 
		echo '<input type="hidden" name="with_user" value="'.$User->GetUserID().'" />'; 
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		echo '<input type="hidden" id="int_member_hidden_input'.$User->GetUserID().'" name="int_member_hidden_input'.$User->GetUserID().'" value="'.$User->GetUserID().'" />'."\n";
		echo '<div class="form_item_major_block_alternate_color">';
			echo '<div class="form_row">';
				echo '<div class="form_heading"><h2>Name: '.$Name.'</h2></div>';
				echo '<div class="form_item spaced"><span class="label">Gender: </span> '.$User->GetGenderName().'</div>';
				echo '<input class="button" type="submit" value="Add Member" />';
			echo '</div>';
		echo '</div>';
		
   echo '</form>';
}

function cntFindMember($str_member,$int_member_hidden_input)
{
	
	
	global $full_uri;
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="find_member" />';
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		cntMemberSelectImproved('Select Member','str_member','int_member_hidden_input',$str_member,$int_member_hidden_input,'Member Selection','45');
		
		echo '<input class="button" type="submit" value="View Member" />';
   echo '</form>';
}


function cntAddEditMember($str_member,$int_member_hidden_input,$id_group,$date)
{
	global $GroupID;
	global $Date;
	
	global $full_uri;
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="add_edit_group_attendance" />';
		echo '<input type="hidden" name="id_group" value="'.$GroupID.'" />';   
		echo '<input type="hidden" name="date" value="'.$Date.'" />';
		echo '<input type="hidden" name="add" value="member" />'; 
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		cntMemberSelectWithExtrasForAttendance('Select Member','str_member','int_member_hidden_input',$str_member,$int_member_hidden_input,'Member Selection','45','off',$id_group,$date);
		echo 'Limit to: <select onchange="jsMemberPopUpForAttendance(window.mem_sel_popup_container,'."'str_member','".$id_group."','".$date."'".')" id="member_search_group_range">';
			echo '<option value="group">Group</option>';
			echo '<option value="region">Region</option>';
			echo '<option value="state">State</option>';
			echo '<option value="all">All</option>';
		echo '</select>';
		echo 'Top Records: <select onchange="jsMemberPopUpForAttendance(window.mem_sel_popup_container,'."'str_member','".$id_group."','".$date."'".')" id="record_limit">';
			echo '<option value="10">10</option>';
			echo '<option value="25">25</option>';
			echo '<option value="50">50</option>';
			echo '<option value="100">100</option>';
			echo '<option value="all">all</option>';
		echo '</select>';
		echo '<div id="bl_committed" style="display:none;">'; 
			echo '<label for="committed">Committed: </label>';
			echo '<input id="chk_committed" type="checkbox" name="committed" value="1"/>';
		echo '</div>';
		echo '<input class="button" type="submit" value="Add Member" />';
   echo '</form>';
}

function cntAddEditStaff($str_staff,$int_staff_hidden_input)
{
	global $GroupID;
	global $Date;
	
	global $full_uri;
	
	echo '<form action="'."$full_uri/index.php".'" method="post">';
		//<!-- submit page_id for submission to self -->
		echo '<input type="hidden" name="page_id" value="add_edit_group_attendance" />';
		echo '<input type="hidden" name="id_group" value="'.$GroupID.'" />';   
		echo '<input type="hidden" name="date" value="'.$Date.'" />';
		echo '<input type="hidden" name="add" value="staff" />';   
		//<!-- capture attempted form submission -->
		echo '<input type="hidden" name="form_submitted" value="1" />';
		cntStaffSelectWithExtrasImproved('Select Staff','str_staff','int_staff_hidden_input',$str_staff,$int_staff_hidden_input,$GroupID,$Date,'Staff Selection','45');
		
		echo '<input class="button" type="submit" value="Add Staff" />';
   echo '</form>';
}

function cntStaffSelectWithExtrasImproved($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$GroupID,$Date,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$Staff = Membership\Staff::LoadStaff($str_hidden_input_def);
		$strDefVal = $Staff->GetFirstName().' '.$Staff->GetLastName();
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsStaffPopUpImproved(window.mem_sel_popup_container,'.$GroupID.",'".$Date."','".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntStaffSelectWithExtras($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$Staff = Membership\Staff::LoadStaff($str_hidden_input_def);
		$strDefVal = $Staff->GetFirstName().' '.$Staff->GetLastName();
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsStaffPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntMemberSelectWithExtrasForAttendance($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off',$id_group,$date)
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$Member = Membership\Member::LoadMember($str_hidden_input_def);
		$strDefVal = $Member->GetFirstName().' '.$Member->GetLastName();
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsMemberPopUpForAttendance(window.mem_sel_popup_container,'."'".$strName."','".$id_group."','".$date."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntMemberSelectWithExtras($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off',$id_group,$date)
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$Member = Membership\Member::LoadMember($str_hidden_input_def);
		$strDefVal = $Member->GetFirstName().' '.$Member->GetLastName();
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsMemberPopUp(window.mem_sel_popup_container,'."'".$strName."','".$id_group."','".$date."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}


function cntDisplayRating($Rating)
{
	if($Rating != 'unrated')
	{
		$Rating = intval($Rating);
		$Stars = '';
		
		for($i = 1; $i <= $Rating; $i++)
		{
			$Stars.= '';
		}
		
		return '<span class="star_rating">'.$Stars.'</span>';
	} else {
		return '<span>'.$Rating.'</span>';
	}
	
}

function cntSelectAttendanceGroups($strTitle,$strName,$arr_groups,$arr_selected_groups)
{
	echo '<div class="form_heading "><h2>'.$strTitle.'</h2></div>';
	
	
	foreach($arr_groups as $group)
	{
		$str_this_name = $group['id'];
		
		if(funIsSelectedGroup($str_this_name,$arr_selected_groups))
		{
			$str_checked = 'checked';
		} else {
			$str_checked = '';
		}
		
		echo '<div class="form_item_block">';
		echo '<input type="checkbox" '.$str_checked.' name="'.$str_this_name.'" value="1">'.$group['name'];
		echo '</div>';
	}
	
}

function cntGroupSelect($strTitle,$arr_groups,$str_group_count_id,$str_group_id)
{
	echo '<div class="form_heading "><h2>'.$strTitle.'</h2></div>';
	
	if(count($arr_groups) == 0) //if there are no groups to select
	{
		echo '<div class="form_item">No Groups fall within the allotted attendance time frame!</div>'."\n";
	} else {
		echo '<input type="hidden" id="'.$str_group_count_id.'" name="'.$str_group_count_id.'" value="'.count($arr_groups).'" />'."\n";
		
		$counter = 1;
		
		foreach( $arr_groups as $group )
		{
			echo '<div class="form_item">'."\n";
			echo funCreateTabs(1).'<input type="checkbox" name="'.$str_group_id.$counter.'" id="'.$str_group_id.$counter.'" value="'.$group['id'].'"/> '.$group['name']."\n";
			echo '</div>'."\n";
			$counter++; //increment counter 
		}
	}
}

function cntSelectRoomBookingDomains($strTitle,$strName,$arrRoomBookingDomains,$arr_selected_room_booking_domains)
{
	echo '<div class="form_heading "><h2>'.$strTitle.'</h2></div>';
	
	
	foreach($arrRoomBookingDomains as $domain)
	{
		$str_this_name = $domain['id_room_booking_domain'];
		
		if(funIsSelectedDomain($str_this_name,$arr_selected_room_booking_domains))
		{
			$str_checked = 'checked';
		} else {
			$str_checked = '';
		}
		
		echo '<div class="form_item_block">';
		echo '<input type="checkbox" '.$str_checked.' name="'.$str_this_name.'" value="1">'.$domain['fld_room_booking_domain'];
		echo '</div>';
	}
	
}

function cntSelectRoom($strTitle,$strName,$arr_available_rooms,$strDefVal,$strErrName)
{
	
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	if($is_error)
	{
		$str_display_on_error = $strTitle.$strErrName.$arrErrors[$strErrName];
		$error_class = 'highlight_error';
		
	} else {
		$str_display_on_error = $strTitle;
		$error_class = '';
		
	}
	
	
	echo '<div class="form_heading '.$error_class.'"><h2>'.$str_display_on_error.'</h2></div>';
	
	if(count($arr_available_rooms) == 0)
	{
		echo '<div class="form_item_block">No rooms available between selected dates!</div>';
	} else {
		
		foreach($arr_available_rooms as $room)
		{
			$str_checked = ( $strDefVal == $room['id_room']) ? 'checked' : '';
			
			echo '<div class="form_item_block">';
			echo '<input type="radio" '.$str_checked.' name="'.$strName.'" value="'.$room['id_room'].'">'.$room['fld_service_name'].' '.$room['fld_room_name'];
			echo '</div>';
		}
	}
	
	
}

function cntSelectStaffOrMember($str_member_select,$int_member_select,$str_member,$int_member_hidden_input,$str_staff_select,$int_staff_select,$str_staff,$int_staff_hidden_input)
{
	$div_member_id = $str_member_select.'_parent_div'; //grab the parent member div to control display
	$div_staff_id = $str_staff_select.'_parent_div';
	
	global $str_by_staff_or_member;
	
	$staff_select = $str_by_staff_or_member == 'by_staff' ? 'selected' : '';
	$member_select = $str_by_staff_or_member == 'about_member' ? 'selected' : '';
	echo '<div class="form_heading"><h2>By Staff / About Member</h2></div>';
	echo '<div class="form_item">'."\n";
	echo funCreateTabs(1).'<select onchange="jsShowHideMemberStaff(this,'."'".$div_member_id."','".$div_staff_id."'".')" id="str_by_staff_or_member" name="str_by_staff_or_member">'."\n";
	echo funCreateTabs(2).'<option '.$staff_select.' value="by_staff">By Staff</option>'."\n";
	echo funCreateTabs(2).'<option '.$member_select.' value="about_member">About Member</option>'."\n";
	echo funCreateTabs(1).'</select>'."\n";
	echo '</div>'."\n";
	
	cntStaffSelectRevised('By Staff',$str_staff_select,$int_staff_select,$str_staff,$int_staff_hidden_input,'Staff Selection','45');
	cntMemberSelect('About Member:',$str_member_select,$int_member_select,$str_member,$int_member_hidden_input,'Member Selection','45');
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'jsShowHideMemberStaff(document.getElementById('."'str_by_staff_or_member'".'),'."'".$div_member_id."','".$div_staff_id."'".');'."\n";
	//echo funCreateTabs(1).'document.getElementById("'.$div_member_id.'").style.display = "none" '."\n";
	echo '</script>'."\n";
}

function cntGroupSelector($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.grp_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsSelectGroupPopUp(window.grp_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.grp_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntGroupSelectorOld($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.grp_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.grp_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onclick="jsSelectGroupPopUp(window.grp_sel_popup_container,window.grp_sel_parent_container,'."'',''".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" readonly="readonly" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.grp_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntMemberSelectOld($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onclick="jsAttendanceMemberPopUp(window.mem_sel_popup_container,window.mem_sel_parent_container,'."'',''".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" readonly="readonly" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntVolunteerSelect($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$Volunteer = Membership\Staff::LoadStaff($str_hidden_input_def);
		$strDefVal = $Volunteer->GetFirstName().' '.$Volunteer->GetLastname();
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsVolunteerPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntMemberSelectImproved($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$member = getMember($str_hidden_input_def)->fetch();
		$strDefVal = $member['fld_first_name'].' '.$member['fld_middle_name'].' '.$member['fld_last_name'];
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsFindMemberPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'Top Records: <select onchange="jsFindMemberPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" id="record_limit">';
	echo funCreateTabs(2).'<option value="10">10</option>';
	echo funCreateTabs(2).'<option value="25">25</option>';
	echo funCreateTabs(2).'<option value="50">50</option>';
	echo funCreateTabs(2).'<option value="100">100</option>';
	echo funCreateTabs(2).'<option value="all">all</option>';
	echo funCreateTabs(1).'</select>';
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntMemberSelect($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$member = getMember($str_hidden_input_def)->fetch();
		$strDefVal = $member['fld_first_name'].' '.$member['fld_middle_name'].' '.$member['fld_last_name'];
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsFindMemberPopUp(window.mem_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntMemberSelectForMultiple($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$member = getMember($str_hidden_input_def)->fetch();
		$strDefVal = $member['fld_first_name'].' '.$member['fld_last_name'];
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.mem_sel_parent_container'.$strName.' = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_popup_container'.$strName.' = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_hidden_input'.$strName.' = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.mem_sel_text_input'.$strName.' = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsFindMemberPopUpForMultiple(window.mem_sel_popup_container'.$strName.','."'".$strName."','".$div_popup_id."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".$strName.').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}


function cntStaffSelectRevised($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	//if member has been selected then capture member name on submit
	if($str_hidden_input_def != '' and !$is_error)
	{
		$staff = getStaffMember($str_hidden_input_def)->fetch();
		$strDefVal = $staff['fld_first_name'].' '.$staff['fld_middle_name'].' '.$staff['fld_last_name'];
	}
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.stf_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onkeyup="jsStaffPopUpRevisedNew(window.stf_sel_popup_container,'."'".$strName."'".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntStaffSelectRevisedOld($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.stf_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_member_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onclick="jsStaffPopUpRevised(window.stf_sel_popup_container,window.stf_sel_parent_container,'."'',''".')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" readonly="readonly" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.mem_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntStaffSelect($strTitle,$strName,$str_hidden_input,$strDefVal,$str_hidden_input_def,$strErrName,$width = '35',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'_parent_div';
	$div_popup_id = $strName.'_popup_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.stf_sel_parent_container = '."'$div_id'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_popup_container = '."'$div_popup_id'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_hidden_input = '."'$str_hidden_input'".';'."\n";
	echo funCreateTabs(1).'window.stf_sel_text_input = '."'$strName'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_staff_select">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="hidden" id="'.$str_hidden_input.'" name="'.$str_hidden_input.'" value="'.$str_hidden_input_def.'" />'."\n";
	echo funCreateTabs(1).'<input onclick="jsStaffPopUp(window.stf_sel_popup_container,window.stf_sel_parent_container'.')" type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" readonly="readonly" />'."\n";
	echo funCreateTabs(1).'<div id="'.$div_popup_id.'" class="form_inner_popup"></div>';
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.stf_sel_popup_container".').style.visibility = "hidden";'."\n";
	echo '</script>';
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntRating($strTitle,$strName,$strDefVal,$strErrName,$lower_limit = '0',$upper_limit = '5',$step = '1',$size = 'sm')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'highlight_error';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_number">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n";
	echo funCreateTabs(1).'<input name="'.$strName.'" id="'.$strName.'" value="'.$strDefVal.'" type="number" class="rating '.$error_class.'" min="'.$lower_limit.'" max="'.$upper_limit.'" step="'.$step.'" data-size="'.$size.'" >'."\n";
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntNumberWithNotification($strTitle,$strName,$strDefVal,$strErrName,$width = '6')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_number">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.': '.$strDefVal.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="text" onkeyup="jsMatchColour('.$strDefVal.','."'".$strName."'".','."'".$strName."'".')" size="'.$width.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">jsMatchColour('.$strDefVal.','."'".$strName."'".','."'".$strName."'".');</script>';
	
}

function cntNumber($strTitle,$strName,$strDefVal,$strErrName,$width = '6')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_number">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="text" size="'.$width.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

function cntEditAtt($int_att_id)
{
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/small_edit.gif" onclick="jsEditAtt('.$int_att_id.')" class="image_right">'."\n";
	echo '</div>'."\n";
}

function cntEditDC($int_dc_id)
{
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/small_edit.gif" onclick="jsEditDC('.$int_dc_id.')" class="image_right">'."\n";
	echo '</div>'."\n";
}

function cntEditRoomBooking($int_room_booking_id)
{
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/small_edit.gif" onclick="jsEditRB('.$int_room_booking_id.')" class="image_right">'."\n";
	echo '</div>'."\n";
}

function cntDeleteGenericImproved($id_string,$jsFunctionName,$id_record_to_del,$warning_message,$custom_styling)
{
	$div_id = $id_string.$id_record_to_del;
	
	
	echo '<div class="parent_confirm_or_cancel float_right" style="position:relative">'."\n";
	echo funCreateTabs(1).'<img style="'.$custom_styling.'" src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(1).'<p>'.$warning_message.'</p>';
	echo funCreateTabs(2).'<div>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Confirm" onclick="'.$jsFunctionName.'('.$id_record_to_del.')"/>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(2).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteGeneric($id_string,$jsFunctionName,$id_record_to_del)
{
	$div_id = $id_string.$id_record_to_del;
	
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(1).'<p>Are you sure you want to delete this region?</p>';
	echo funCreateTabs(2).'<div>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Confirm" onclick="'.$jsFunctionName.'('.$id_record_to_del.')"/>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(2).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteGroupRegion($int_group_id,$int_grp_rgn_id)
{
	$div_id = 'delete_grp_rgn_'.$int_grp_rgn_id;
	
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(1).'<p>Are you sure you want to delete this region?</p>';
	echo funCreateTabs(2).'<div>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Confirm" onclick="jsDelGRPRGN('.$int_group_id.','.$int_grp_rgn_id.')"/>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(2).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteRegion($int_rgn_id)
{
	$div_id = 'delete_rgn_'.$int_rgn_id;
	
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(1).'<p>Are you sure you want to delete this region?</p>';
	echo funCreateTabs(2).'<div>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Confirm" onclick="jsDeleteRGN('.$int_rgn_id.')"/>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(2).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteGroup($int_grp_id,$target_page)
{
	$div_id = 'delete_grp_'.$int_grp_id;
	
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(1).'<p>Are you sure you want to delete this group?</p>';
	echo funCreateTabs(2).'<div>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Confirm" onclick="jsDeleteGRP('.$int_grp_id.',\''.$target_page.'\')"/>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(2).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteDC($int_dc_id)
{
	$div_id = 'delete_dc_'.$int_dc_id;
	
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(1).'<p>Are you sure you want to delete this daily contact record?</p>';
	echo funCreateTabs(2).'<div>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Confirm" onclick="jsDeleteDC('.$int_dc_id.')"/>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(2).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteMemComDtesImproved($id_user,$id_committed,$int_submitted)
{
	$div_id = 'delete_MemComDte_'.$id_committed;
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(2).'<p>Are you sure you want to delete this attendance record?</p>';
	echo funCreateTabs(1).'<div>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Confirm" onclick="jsDeleteMemComDteImproved('.$id_user.','.$id_committed.','.$int_submitted.')"/>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteMemComDtes($id_user,$id_committed,$int_submitted,$return_to)
{
	$div_id = 'delete_MemComDte_'.$id_committed;
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(2).'<p>Are you sure you want to delete this attendance record?</p>';
	echo funCreateTabs(1).'<div>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Confirm" onclick="jsDeleteMemComDte('.$id_user.','.$id_committed.','.$int_submitted.",'".$return_to."'".')"/>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteActivityDate($id_activity,$int_submitted,$id_user)
{
	$div_id = 'delete_att_'.$id_activity;
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(2).'<p>Are you sure you want to delete this activity record?</p>';
	echo funCreateTabs(1).'<div>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Confirm" onclick="jsDeleteActivity('.$id_activity.','.$id_user.','.$int_submitted.')"/>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntHoverMessage($id_message,$message)
{
		
	$div_id = $id_message;
	
	
	echo '<div id="'.$div_id.'" class="hover_message">'."\n";
	echo $message;
	
	echo '</div>'."\n";
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntDeleteAtt($id_attendance,$int_submitted,$id_group,$date)
{
	$div_id = 'delete_att_'.$id_attendance;
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(2).'<p>Are you sure you want to delete this attendance record?</p>';
	echo funCreateTabs(1).'<div>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Confirm" onclick="jsDeleteAttendance('.$id_attendance.','.$id_group.','.$int_submitted.",'".$date."'".')"/>'."\n";
	echo funCreateTabs(2).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

function cntCancelRoomBooking($id_rm_bk)
{
	$div_id = 'delete_rm_bk_'.$id_rm_bk;
	
	
	echo '<div class="parent_confirm_or_cancel float_right">'."\n";
	echo funCreateTabs(1).'<img src="'.BaseExternalURL.'/images/white_close.gif" onclick="jsShow('."'".$div_id."'".')" class="image_right">'."\n";
	echo funCreateTabs(1).'<div class="confirm_or_cancel" id="'.$div_id.'">'."\n";
	echo funCreateTabs(1).'<p>Are you sure you want to cancel this room booking?</p>';
	echo funCreateTabs(2).'<div>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Confirm" onclick="jsCancelBooking('.$id_rm_bk.');"/>'."\n";
	echo funCreateTabs(3).'<input type="button" value="Cancel" onclick="jsHide('."'".$div_id."'".');"/>'."\n";
	echo funCreateTabs(2).'</div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."jsHide('".$div_id."');"."\n";
	echo '</script>'."\n";
}

//CheckBox Control function
function cntCheckBox($strTitle,$strName,$strDefVal,$strErrName)
{
	global $arrErrors;
	global $true;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	$checked = ($strDefVal == $true) ? 'checked' : '';
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_text">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="checkbox" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="1" '.$checked.' />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
	
	
}

//Date Control function
function cntDate($strTitle,$strName,$strDefVal,$strErrName,$strYearRange = '')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script>'."\n";
	echo '$(function() {'."\n";
	echo funCreateTabs(1).'$( "#'.$strName.'" ).datepicker({'."\n";
	echo funCreateTabs(2).'dateFormat: "dd/mm/yy"'."\n";
	echo funCreateTabs(2).',changeYear: true'."\n";
	if($strYearRange != '')
	{
		echo funCreateTabs(2).',yearRange: "'.$strYearRange.'"'."\n";
	}
	echo funCreateTabs(1).'});'."\n";
	echo '});'."\n";
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_date">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n";
	echo funCreateTabs(1).'<input type="text" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
	
}

function cntInterval($strTitle,$strName,$strDefVal,$strErrName)
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_time">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n";
	echo funCreateTabs(1).'<input type="text" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."$('#".$strName."').timepicker({"."\n";
	echo funCreateTabs(2)."showPeriodLabels: false,"."\n";
	echo funCreateTabs(2)."showLeadingZero: false"."\n";
	echo funCreateTabs(1).'});'."\n";
	echo '</script>'."\n";
	
	
}

//Date Control function
function cntTime($strTitle,$strName,$strDefVal,$strErrName)
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_time">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n";
	echo funCreateTabs(1).'<input type="text" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1)."$('#".$strName."').timepicker({"."\n";
	echo funCreateTabs(2)."showPeriodLabels: true,"."\n";
	echo funCreateTabs(2)."showLeadingZero: true"."\n";
	echo funCreateTabs(1).'});'."\n";
	echo '</script>'."\n";
	
	
}

//Generic Control function
function cntText($strTitle,$strName,$strDefVal,$strErrName,$width = '25',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_text">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="text" size="'.$width.'" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
	
}

//a wonderful idea but far too much time required to develop
function cntEmergencyContacts($strContainerID,$arr_emergency_contacts)
{
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.em_con_parent_container = '."'$strContainerID'".';'."\n";
	echo '</script>'."\n";
	
	echo '<div id="'.$strContainerID.'" class="form_control form_emergency_contacts">'."\n";
	echo funCreateTabs(1).'<input type="hidden" name="em_con_count" id="em_con_count" value="0" />'."\n";
	echo funCreateTabs(1).'<input type="button" onclick="jsCreateEmCont()" value="Add Emergency Contact" />'."\n";
	echo '</div>'."\n";
}
function cntFacilitators($strContainerID,$strPopUpID,$arr_staff_facilitators,$arr_member_facilitators)
{
	$str_inner_popup_id = 'inner_popup'; //inner popup id
	
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.staff_ids = new Array();'."\n";
	echo funCreateTabs(1).'window.member_ids = new Array();'."\n";
	echo funCreateTabs(1).'window.pop_up_id = '."'".$strPopUpID."'"."\n";
	echo '</script>'."\n";
	
	echo '<div id="'.$strContainerID.'" class="form_control form_facilitators">'."\n";
	echo funCreateTabs(1).'<div class="button_right">'."\n";
	echo funCreateTabs(2).'<input type="button" onclick="jsFacilitatorPopUp('."'".$strContainerID."','".$strPopUpID."'".')" value="Add Facilitator" />'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	
	echo funCreateTabs(1).'<input type="hidden" id="csv_staff_ids" name="csv_staff_ids" value="" />'."\n"; //hidden input for staff 
	echo funCreateTabs(1).'<input type="hidden" id="csv_member_ids" name="csv_member_ids" value="" />'."\n"; //hidden input for members
	
	echo funCreateTabs(1).'<div class="form_popup" id="'.$strPopUpID.'">'."\n";
	echo funCreateTabs(2).'<input type="button" onclick="jsFacilitatorStaffPopUpRevisedOld('."'".$str_inner_popup_id."','".$strContainerID."','','',window.staff_ids".')" value="Add Staff"/>'."\n";
	echo funCreateTabs(2).'<input type="button" onclick ="jsFacilitatorMemberPopUp('."'".$str_inner_popup_id."','".$strContainerID."','','',window.member_ids".')" value="Add Member"/>'."\n";
	echo funCreateTabs(2).'<img onclick="jsHideFacilitatorPopUp('."'".$strPopUpID."','".$str_inner_popup_id."'".')" src="/images/small_close.gif"/>'."\n";
	echo funCreateTabs(2).'<div id="'.$str_inner_popup_id.'" class="form_inner_popup"></div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	
	
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.pop_up_id".').style.visibility = "hidden";'."\n";
	echo '</script>';
	
	//auto populate staff and member facilitator id's
	if(count($arr_staff_facilitators) > 0 )
	{
		
		echo '<script type="text/javascript">'."\n";
				
			foreach($arr_staff_facilitators as $staff_facilitator)
			{
				echo 'jsAddStaff('."'',".$staff_facilitator['id_person'].",'".$staff_facilitator['fld_first_name'].' '.$staff_facilitator['fld_middle_name'].' '.$staff_facilitator['fld_last_name']."','".$str_inner_popup_id."','".$strContainerID."'".');'."\n";
			}
		echo '</script>'."\n";
	}
	
	if(count($arr_member_facilitators) > 0)
	{
		
		echo '<script type="text/javascript">'."\n";
					
			foreach($arr_member_facilitators as $member_facilitator)
			{
				echo 'jsAddMember('."'',".$member_facilitator['id_person'].",'".$member_facilitator['fld_first_name'].' '.$member_facilitator['fld_middle_name'].' '.$member_facilitator['fld_last_name']."','".$str_inner_popup_id."','".$strContainerID."'".');'."\n";
			}
		echo '</script>'."\n";
	}
	
	echo '</div>'."\n";
}

function cntFacilitatorsOld($strContainerID,$strPopUpID,$arr_staff_facilitators,$arr_member_facilitators)
{
	$str_inner_popup_id = 'inner_popup'; //inner popup id
	
	
	echo '<script type="text/javascript">'."\n";
	echo funCreateTabs(1).'window.staff_ids = new Array();'."\n";
	echo funCreateTabs(1).'window.member_ids = new Array();'."\n";
	echo funCreateTabs(1).'window.pop_up_id = '."'".$strPopUpID."'"."\n";
	echo '</script>'."\n";
	
	echo '<div id="'.$strContainerID.'" class="form_control form_facilitators">'."\n";
	echo funCreateTabs(1).'<div class="button_right">'."\n";
	echo funCreateTabs(2).'<input type="button" onclick="jsFacilitatorPopUp('."'".$strContainerID."','".$strPopUpID."'".')" value="Add Facilitator" />'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	
	echo funCreateTabs(1).'<input type="hidden" id="csv_staff_ids" name="csv_staff_ids" value="" />'."\n"; //hidden input for staff 
	echo funCreateTabs(1).'<input type="hidden" id="csv_member_ids" name="csv_member_ids" value="" />'."\n"; //hidden input for members
	
	echo funCreateTabs(1).'<div class="form_popup" id="'.$strPopUpID.'">'."\n";
	echo funCreateTabs(2).'<input type="button" onclick="jsFacilitatorStaffPopUpRevised('."'".$str_inner_popup_id."','".$strContainerID."','','',window.staff_ids".')" value="Add Staff"/>'."\n";
	echo funCreateTabs(2).'<input type="button" onclick ="jsFacilitatorMemberPopUp('."'".$str_inner_popup_id."','".$strContainerID."','','',window.member_ids".')" value="Add Member"/>'."\n";
	echo funCreateTabs(2).'<img onclick="jsHideFacilitatorPopUp('."'".$strPopUpID."','".$str_inner_popup_id."'".')" src="/images/small_close.gif"/>'."\n";
	echo funCreateTabs(2).'<div id="'.$str_inner_popup_id.'" class="form_inner_popup"></div>'."\n";
	echo funCreateTabs(1).'</div>'."\n";
	
	
	//set the pop up to hidden regardless
	echo '<script type="text/javascript">'."\n";
			//set the hidden property of the pop up
			echo 'document.getElementById('."window.pop_up_id".').style.visibility = "hidden";'."\n";
	echo '</script>';
	
	//auto populate staff and member facilitator id's
	if(count($arr_staff_facilitators) > 0 )
	{
		
		echo '<script type="text/javascript">'."\n";
				
			foreach($arr_staff_facilitators as $staff_facilitator)
			{
				echo 'jsAddStaff('."'',".$staff_facilitator['id_person'].",'".$staff_facilitator['fld_first_name'].' '.$staff_facilitator['fld_middle_name'].' '.$staff_facilitator['fld_last_name']."','".$str_inner_popup_id."','".$strContainerID."'".');'."\n";
			}
		echo '</script>'."\n";
	}
	
	if(count($arr_member_facilitators) > 0)
	{
		
		echo '<script type="text/javascript">'."\n";
					
			foreach($arr_member_facilitators as $member_facilitator)
			{
				echo 'jsAddMember('."'',".$member_facilitator['id_person'].",'".$member_facilitator['fld_first_name'].' '.$member_facilitator['fld_middle_name'].' '.$member_facilitator['fld_last_name']."','".$str_inner_popup_id."','".$strContainerID."'".');'."\n";
			}
		echo '</script>'."\n";
	}
	
	echo '</div>'."\n";
}

function cntPeriodSelect($strTitle,$strName,$str_recurrence_id_default,$int_recurrence_default,$strErrName)
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	$str_period_id = $strName.'str_period_div'; //days,weeks,months,quarters,years
	$int_period_id = $strName.'int_period_div'; //number
	
	//get periods
	$obj_periods = getPeriods();
	$arr_periods = $obj_periods->fetchAll();
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<script>'."\n";
	echo funCreateTabs(1).'window.arr_limits = new Array();'."\n";
	foreach( $arr_periods as $period )
		{
			echo funCreateTabs(1)."arr_limits['".$period['id_period']."'] = '".$period['fld_limit']."'"."\n";
		}
	echo '</script>'."\n";
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_period_select">'."\n";
	
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	
	echo funCreateTabs(1).'<select onChange="jsUpdateSelect('."'".$str_period_id."'".','."'".$int_period_id."'".');" '.$error_class.' id="'.$str_period_id.'" name="'.$str_period_id.'">'."\n";
		foreach( $arr_periods as $period2 )
		{
			if( $period2['id_period'] == $str_recurrence_id_default )
			{
				$str_selected = ' selected';
			} else {
				$str_selected = '';
			}
			echo funCreateTabs(2).'<option value="'.$period2['id_period'].'"'.$str_selected.'>'.$period2['fld_period_name'].'</option>'."\n";
		}
	echo funCreateTabs(1).'</select>';
	
	echo funCreateTabs(1).'<select '.$error_class.' id="'.$int_period_id.'" name="'.$int_period_id.'">'."\n";
	echo funCreateTabs(2).'<option value="1">1</option>'."\n";
	echo funCreateTabs(1).'</select>';
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '<script>'."\n";
	echo 'jsUpdateSelect('."'".$str_period_id."'".','."'".$int_period_id."'".','.$int_recurrence_default.');';
	echo '</script>'."\n";
	
	
	echo '</div>'."\n";;

}

//Generic Control function
function cntLongText($strTitle,$strName,$strDefVal,$strErrName,$cols = '50',$rows = '5')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_text_area">'."\n";
	echo funCreateTabs(1).'<label class="textarea" for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<textarea rows="'.$rows.'" cols="'.$cols.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'">'.$strDefVal.'</textarea>'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";;
	
}

//User Name Control function
function cntUserName($strTitle,$strName,$strDefVal,$strErrName,$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_text">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="text" autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";;
	
}

//container for passwords
function cntPassword($strTitle,$strName,$strDefVal,$strErrName,$strChangeFunction = '',$autoComplete = 'off')
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_password">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<input type="password" '.$strChangeFunction.' autocomplete="'.$autoComplete.'" '.$error_class.' id="'.$strName.'" name="'.$strName.'" value="'.$strDefVal.'" />'."\n";
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";;
	
}

function cntUserNameAndPassword($strUName,$strPWord)
{                   
	global $arrErrors;
	
	$blkMessage = funDspBlkErrorMsg($arrErrors,'Bad');
	$showhide = ($blkMessage == '') ? 'hide' : 'show';
	
	
	echo '<div class="username_container">';
	if($blkMessage != '')
	{
		echo funCreateTabs(1).'<div class="relative_userpassword_container '.$showhide.'">';
		echo funCreateTabs(2).'<div class="floating_password_container_block">'.$blkMessage.'</div>';
		echo funCreateTabs(1).'</div>';
	}
	echo funCreateTabs(1).'<div class="inner_username_container">';
	cntUserName('User Name:','strUName',$strUName,'User Name','on');
    cntPassword('Password:','strPWord',$strPWord,'Password','','on');
	echo funCreateTabs(1).'</div>';
	
	echo '</div>';
}

function cntPassWordsNew($strNPword,$strRPword,$strBlkMsgID)
{
	global $arrErrors;
	$blkMessage = funDspBlkErrorMsg($arrErrors,'Passwords');
	
	$hide = ($blkMessage == '') ? ' hide' : '';
	
	
	echo '<div class="change_password_container">';
	echo funCreateTabs(1).'<div class="inner_passwords_container">';
	cntPassword('New Password:','Password',$strNPword,'Password',"onkeyup=\"jsPWordStrength('Password','".$strBlkMsgID."');\"");
	cntPassword('Re-enter New Password:','CheckPassword',$strRPword,'Re-entered Password',"onkeyup=\"jsStringMatch('Password','CheckPassword','".$strBlkMsgID."','Password');\"");
	echo funCreateTabs(1).'</div>';
	
	echo funCreateTabs(1).'<div class="inner_passwords_container passwords_message">';
	echo funCreateTabs(2).'<div id="'.$strBlkMsgID.'" class="change_password_container_block'.$hide.'">'.$blkMessage.'</div>';
	echo funCreateTabs(1).'</div>';
	echo '</div>';
}

//backup in case I should need to reverse things
function cntPassWordsNewOld($strNPword,$strRPword,$strBlkMsgID)
{
	global $arrErrors;
	$blkMessage = funDspBlkErrorMsg($arrErrors,'Passwords');
	
	echo '<div class="change_password_container">';
	echo funCreateTabs(1).'<div class="inner_passwords_container">';
	cntPassword('New Password:','strNPword',$strNPword,'Password',"onkeyup=\"jsPWordStrength('strNPword','".$strBlkMsgID."');\"");
	cntPassword('Re-enter New Password:','strRPword',$strRPword,'Re-entered Password',"onkeyup=\"jsStringMatch('strNPword','strRPword','".$strBlkMsgID."','Password');\"");
	echo funCreateTabs(1).'</div>';
	echo funCreateTabs(1).'<div class="inner_passwords_container passwords_message">';
	echo funCreateTabs(2).'<div id="'.$strBlkMsgID.'" class="change_password_container_block">'.$blkMessage.'</div>';
	echo funCreateTabs(1).'</div>';
	echo '</div>';
}


// Container for drop down menus

function cntRegionDropDown($strTitle,$strName,$strKey,$strBranch,$strValue,$arrValues,$strErrName,$intSelected)
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_drop_down">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<select '.$error_class.' name="'.$strName.'" id="'.$strName.'">'."\n"; 
	
		foreach( $arrValues as $value )
		{
			$selected = ($intSelected == $value[$strKey]) ? 'selected' : '';
			
			
			echo funCreateTabs(2).'<option '.$selected.' value="'.$value[$strKey].'">'.$value[$strBranch].': '.$value[$strValue].'</option>'."\n"; 
			
		}
		
	
	echo funCreateTabs(1).'</select>'."\n"; 
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
}

function cntDropDown($strTitle,$strName,$strKey,$strValue,$arrValues,$strErrName,$intSelected) 
{
	global $arrErrors;
	
	$is_error = array_key_exists($strErrName,$arrErrors);
	
	$div_id = $strName.'parent_div';
	
	
	if($is_error)
	{
		$error_class = 'class="highlight_error"';
		$call_hover = 'onmouseover="jsHoverError('."'".$div_id."','".$strErrName.' '.$arrErrors[$strErrName]."')".'"';
		$call_hide = 'onmouseout="jsHideHoverError('."'".$div_id."')".'"';
	} else {
		$error_class = '';
		$call_hover = '';
		$call_hide = '';
	}
	
	echo '<div '.$call_hover.' '.$call_hide.' id="'.$div_id.'"'.' class="form_item form_drop_down">'."\n";
	echo funCreateTabs(1).'<label for="'.$strName.'">'.$strTitle.'</label>'."\n"; 
	echo funCreateTabs(1).'<select '.$error_class.' name="'.$strName.'" id="'.$strName.'">'."\n"; 
	
		foreach( $arrValues as $value )
		{
			$selected = ($intSelected == $value[$strKey]) ? 'selected' : '';
			
			
			echo funCreateTabs(2).'<option '.$selected.' value="'.$value[$strKey].'">'.$value[$strValue].'</option>'."\n"; 
			
		}
		
	
	echo funCreateTabs(1).'</select>'."\n"; 
	
	if( $is_error )
	{
		funDspErrorMsg($arrErrors,$strErrName);
	}
	
	echo '</div>'."\n";
}

//Old Generic Control function
function cntGeneric($strTitle,$strType,$strName,$strDefVal,$arrErrName,$strErrName,$arrStyles,$strAdditionalText = '')
{
	global $arrErrors;
	
	//add highlight errors style if applicable
	$strHErr = funHighlightErrors($arrErrors,$arrErrName);
	if(!is_null($strHErr))
	{
		$arrStyles[] = $strHErr;
	}
	
	echo "<div class=\"form_row\">\n";
	echo "	<div class=\"stack\">\n";
	echo "		<label for=\"$strName\">$strTitle</label>\n"; 
	echo "		<input ";
	
	if(count($arrStyles) > 0)
	{
		echo 'class="';
		foreach($arrStyles as $style)
		{
			echo "$style ";
		}
		echo "\"";
	}
	
	echo " type=\"$strType\" id=\"$strName\" name=\"$strName\" value=\"$strDefVal\" />\n";
	if ($strAdditionalText != '')
	{
		echo $strAdditionalText;	
	}
	echo "	</div>\n";
	if( array_key_exists($strErrName,$arrErrors) )
	{
		echo "	<div class=\"stack\">\n";
			funDspErrorMsg($arrErrors,$strErrName);
		echo "	</div>\n";
	}
	
	echo "</div>\n";
}

//Gender Control function
function cntGender($grpGender)
{
	
	global $qryGenders;
	
	echo "<div class=\"form_row\">\n";
	echo "	<label>Your Gender: </label>\n";
	echo "	<div class=\"stack\">\n";
		// Using id to match with label and name to create group
	foreach( $qryGenders as $Gender)
	{
		if($grpGender == $Gender['id_gender'] )
		{
			$chk = 'checked'; //extra whitespace to allow for end tag separation
		} else {
			$chk = '';
		}
		
		echo $Gender['fld_gen_name'].' ';
		echo "<input type=\"radio\" class=\"input_no_border\" name=\"grpGender\" value=\"{$Gender['id_gender']}\" $chk /> ";
	}
			
			
	echo "	</div>\n";
	echo "</div>";
}

//Username Control function
function cntUsernameOld($strTitle,$strName,$strDefVal)
{
	
	global $arrErrors;
	
	//add highlight errors style if applicable
	$strHErr = funHighlightErrors($arrErrors,array('User Name','Duplicate User Name'));
	
	echo "<div class=\"form_row\">\n";
	echo "	<div class=\"stack\">\n";
	echo "		<label for=\"$strName\">$strTitle</label>\n"; 
	echo "		<input";
	if(!is_null($strHErr))
	{
		echo " class=\"$strHErr\"";
	}
	echo " type=\"text\" id=\"$strName\" name=\"$strName\" value=\"$strDefVal\" />\n";
	echo "	</div>\n";
	if( array_key_exists('User Name',$arrErrors) or array_key_exists('Duplicate User Name',$arrErrors) )
	{
		echo "	<div class=\"stack\">\n";
			funDspErrorMsg($arrErrors,'User Name');
			funDspErrorMsg($arrErrors,'Duplicate User Name');
		echo "	</div>\n";
	}
	echo "</div>\n";
}

//Password Control Function
function cntPassWords($strTitle,$strTitle2,$strName,$strName2,$strDefVal,$strDefVal2)
{
	global $arrErrors;
	
	//add highlight errors style if applicable
	$strHErr = funHighlightErrors($arrErrors,array('Password'));
	$strHErr2 = funHighlightErrors($arrErrors,array('Re-entered Password'));
	
	//resolve the javascript message target
	$strMsgTgt = funMsgTarget($arrErrors,'Passwords','pPwdMatch','Passwords');
	
	//begin first password control
	echo "<div class=\"form_row\">\n"; //begin form row
	echo "	<div class=\"stack\">\n";
	echo "		<label for=\"$strName\">$strTitle</label>\n";
	//running match here in case second password is entered first
	//onKeyUp functions: Password strength checker, Password match checker
	echo "		<input";
	if(!is_null($strHErr))
	{
	 	echo " class=\"$strHErr\"";
	}
	echo " type=\"password\" id=\"$strName\" name=\"$strName\" value=\"$strDefVal\" onKeyUp=\"fnPWordStrength('$strName','pPwdStrength');fnStrMatch('$strName','$strName2','$strMsgTgt','Password');\" />\n";
	echo "	</div>\n";
	if( array_key_exists('Password',$arrErrors) )
	{
		echo "	<div class=\"stack\">\n";
		funDspErrorMsg($arrErrors,'Password');
		echo "	</div>\n";
	}
	echo "	<div class=\"stack\">\n";
	// Place holder for form notice messages
	echo "		<p class=\"form_notice_message\" id=\"pPwdStrength\"></p>\n";
	// If form has been submitted (display password strength)
	if(isset($_REQUEST['form_submitted']))
	{
		echo "		<script type=\"text/javascript\">fnPWordStrength('$strName','pPwdStrength');</script>\n"; 
	}
	echo "	</div>\n";
	echo "</div>\n"; //end form row
    //end first password control
	
	//Begin second password
    echo "<div class=\"form_row\">\n"; //begin row
	echo "	<div class=\"stack\">\n";
	echo "		<label for=\"$strName2\">$strTitle2</label>\n";
    //onKeyUp function: Password match checker
    echo "		<input";
	if(!is_null($strHErr2))
	{
	 	echo " class=\"$strHErr2\"";
	}
	echo " type=\"password\" id=\"$strName2\" name=\"$strName2\" value=\"$strDefVal2\" onKeyUp=\"fnStrMatch('$strName','$strName2','$strMsgTgt','Password');\" />\n";
    echo "	</div>\n";
	if( array_key_exists('Re-entered Password',$arrErrors) )
	{
		echo "	<div class=\"stack\">\n";
		funDspErrorMsg($arrErrors,'Re-entered Password');
		echo "	</div>\n";
	}
    echo "	<div class=\"stack\">\n";
	//placeholder for password match control
    echo "		<p class=\"form_notice_message\" id=\"pPwdMatch\"></p>\n";
    // If form has been submitted (display password match)
    funDspErrorMsg($arrErrors,'Passwords','Passwords');

   	echo "	</div>\n";
	echo "</div>\n"; //end form row
    //End second password
}

//Emails Controil
function cntEmails($strTitle,$strTitle2,$strName,$strName2,$strDefVal,$strDefVal2)
{
	global $arrErrors;
	
	//add highlight errors style if applicable
	$strHErr = funHighlightErrors($arrErrors,array('Email'));
	$strHErr2 = funHighlightErrors($arrErrors,array('Re-entered Email'));
	
	//resolve the javascript message target
	$strMsgTgt = funMsgTarget($arrErrors,'Emails','pEmailMatch','Emails');
	
	//begin first email control
	echo "<div class=\"form_row\">\n"; //begin form row
	echo "	<div class=\"stack\">\n";
	echo "		<label for=\"$strName\">$strTitle</label>\n";
	echo "		<input type=\"text\" id=\"$strName\" name=\"$strName\" value=\"$strDefVal\" class=\"text_wider $strHErr\" onKeyUp=\"fnStrMatch('$strName','$strName2','$strMsgTgt','Email');\" />\n";
	echo "	</div>\n";
	if( array_key_exists('Email',$arrErrors) )
	{
		echo "	<div class=\"stack\">\n";
		funDspErrorMsg($arrErrors,'Email');
		echo "	</div>\n";
	}
	echo "</div>\n"; //end form row
	//end first email control
	
	//begin second email control
	echo "<div class=\"form_row\">\n"; //begin form row
	echo "	<div class=\"stack\">\n";
	echo "		<label for=\"$strName2\">$strTitle2</label>\n";
	// onKeyUp function: Email match checker
	echo "		<input type=\"text\" id=\"$strName2\" name=\"$strName2\" value=\"$strDefVal2\" class=\"text_wider $strHErr2\"  onKeyUp=\"fnStrMatch('$strName','$strName2','$strMsgTgt','Email')\" />\n";
	echo "	</div>\n";
	if( array_key_exists('Re-entered Email',$arrErrors) )
	{
		echo "	<div class=\"stack\">\n";
		funDspErrorMsg($arrErrors,'Re-entered Email');
		echo "	</div>\n";
	}
	echo "	<div class=\"stack\">\n";
	echo "		<p class=\"form_notice_message\" id=\"pEmailMatch\"></p>\n";
	//If form has been submitted (display password match)
	funDspErrorMsg($arrErrors,'Emails','Emails');
	echo "	</div>\n";
	echo "</div>\n"; //end form row
	//end second email control
                    
}

?>