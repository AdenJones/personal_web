<?php
	
	$months_since_last_attended = 12;
	
	$LapsedCommittedGrowers = \Membership\Member::LoadLapsedCommittedGrowers($months_since_last_attended)
	
?>