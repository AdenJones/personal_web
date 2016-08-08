<?php
	
		
	$MyReports = $_SESSION['User']->LoadMyReports();
	
	if(count($MyReports) == 0)
	{
		$Heading = "No Reports at this time!";
	} else {
		$Heading = count($MyReports)." Reports!";
	}
	
	header("Refresh:30");
	
?>