<?php 
	//Refresh Users Topics
	$_SESSION['User']->LoadMyIncompleteSeekerJobs();
	
	$MyJobs = $_SESSION['User']->GetMyIncompleteSeekerJobs();
	
	$JobCount = count($MyJobs);
	
	$JobsPendingHeading = ( $JobCount > 0 ? $JobCount.' Jobs Pending!' : 'No Jobs Pending.');
	
    $JobsWithoutAcceptedBidsCount = 0;
	$JobsWithAcceptedBidCount = 0;
	
	foreach( $MyJobs as $Job )
	{
		if( $Job->HasAcceptedBid() )
		{
			$JobsWithAcceptedBidCount++;
		} else {
			$JobsWithoutAcceptedBidsCount++;
		}
	}
	
	$CompleteJobs = $_SESSION['User']->GetMySeekerUnsignedOffCompleteJobs();
	
	$USOCJJobCount = count($CompleteJobs);
	
	$USOCJHeading = ( $USOCJJobCount > 0 ? $USOCJJobCount.' Complete Jobs Pending Approval!' : 'No Complete Jobs Pending Approval!');
	
	
	
	$ArchivedJobs = $_SESSION['User']->GetMySeekerSignedOffCompleteJobs();
	
	$RecentArchivedJobs = array();
	
	$Today = date_create(date('Y-m-d H:i:s'));
	$TwoWeeks = new DateInterval('P14D');
	
	foreach( $ArchivedJobs as $Job )
	{
		$ThisInterval = date_diff($Today,date_create($Job->GetCreated()));
		
		if( $ThisInterval->format('%d') <= $TwoWeeks->format('%d') )
		{
			$RecentArchivedJobs[] = $Job;
		}
		
	}
	
	$ArchivedJobCount = count($RecentArchivedJobs);
	
	$ArchivedHeading = ( $ArchivedJobCount > 0 ? $ArchivedJobCount.' Recently Archived Jobs!' : 'You have no recently archived jobs!');
	
    
	
	$UnresolvedDisputedJobs = $_SESSION['User']->GetMySeekerUnresolvedDisputedJobs();
	
	$UnresolvedDisputedJobCount = count($UnresolvedDisputedJobs);
	
	$UnresolvedDisputedHeading = ( $UnresolvedDisputedJobCount > 0 ? $UnresolvedDisputedJobCount.' Unresolved Disputed Jobs!' : 'You have no unresolved disputed jobs!');
	
	
	$ResolvedDisputedJobs = $_SESSION['User']->GetMySeekerResolvedDisputedJobs();
	
	$RecentResolvedDisputedJobs = array();
	
	foreach( $ResolvedDisputedJobs as $Job )
	{
		$ThisInterval = date_diff($Today,date_create($Job->GetCreated()));
		
		if( $ThisInterval->format('%d') <= $TwoWeeks->format('%d') )
		{
			$RecentResolvedDisputedJobs[] = $Job;
		}
	}
	
	$JobCount2 = count($RecentResolvedDisputedJobs);
	
	$ResolvedDisputedHeading = ( $JobCount2 > 0 ? $JobCount2.' Recent Resolved Disputed Jobs!' : 'You have no recent resolved disputed jobs!');
	
	
	
	
?>