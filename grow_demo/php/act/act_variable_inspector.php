<?php
	$BranchObject = \Business\Branch::LoadBranch(1);
	
	$StartDate = '2015-1-1';
	
	$EndDate = '2015-6-30';
	
	$CommittedGrowers = $BranchObject->TotalCommittedGrowersInPeriodByMonthDates($StartDate,$EndDate);
	
	$CGIDX = 0;
	
	
?>