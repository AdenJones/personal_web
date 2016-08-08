<?php
	
		
	$MyLabels = $_SESSION['User']->LoadMyLabels();
	
	if(count($MyLabels) == 0)
	{
		$Heading = "No Labels at this time!";
	} else {
		$Heading = count($MyLabels)." Labels!";
	}
	
	header("Refresh:30");
	
?>