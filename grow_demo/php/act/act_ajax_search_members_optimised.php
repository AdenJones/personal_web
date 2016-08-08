<?php 
	
	$str_text = trim(funRqScpVar('str_text',''));
	$search_scope = trim(funRqScpVar('search_scope',''));
	$id_group = intval(funRqScpVar('id_group',''));
	$this_date = trim(funRqScpVar('this_date',''));
	$record_limit = intval(funRqScpVar('record_limit',''));
	
	if( $search_scope == 'group' )
	{
		$arrMembers = Membership\Member::LoadMembersBySearchStringGroupOptimised($str_text,$id_group,$this_date,$record_limit,false);
		
	} elseif( $search_scope == 'region' )
	{
		$arrMembers = Membership\Member::LoadMembersBySearchStringRegionOptimised($str_text,$id_group,$this_date,$record_limit,false);
		
	} elseif( $search_scope == 'state' )
	{
		$arrMembers = Membership\Member::LoadMembersBySearchStringStateOptimised($str_text,$id_group,$this_date,$record_limit,false);
		
		
	} elseif( $search_scope == 'all' )
	{
		$arrMembers = Membership\Member::LoadMembersBySearchStringOptimised($str_text,$this_date,$record_limit,false);
	}
	
?>

