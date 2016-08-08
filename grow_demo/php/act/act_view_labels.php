<?php 

	$bad_labels = false;
	$bad_user = false;
	
	$LabelsID = funRqScpVar('LabelsID','');
	
	$Labels = Business\UserLabels::LoadUserLabels($LabelsID);
	
	if( $Labels == NULL )
	{
		$bad_labels = true;
	} elseif( $_SESSION['User']->GetUserID() != $Labels->GetIDUser()) {
		$bad_user = true;
	} else {
		//perform all other logic
		
		//headcing stripper is depeendant upon the correct format of heading
		
		$LabelsType = $Labels->GetLabelsType();
		$GroupName = $Labels->GetLabelsName();
		$Dates = $Labels->GetLabelsDates();
		
		$Heading = html_entity_decode($LabelsType.' : '.$GroupName.' : '.$Dates);
		
		$HeadingStripped = preg_replace('/\s+/', '', $Heading);
		
		$LabelsFields = $Labels->LoadUserLabelsFields();
		
		
	}

?>