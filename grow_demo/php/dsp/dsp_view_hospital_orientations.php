		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?> - <?php echo $url_add_hospital_orientation ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                    	
                    
                        <script>
						$(function() {
						$( "#tabs" ).tabs();
						});
						</script>
                        <div id="tabs" style="background: none repeat scroll 0% 0% transparent; font-family:inherit; font-size:inherit;">
                            <ul style="height: 30px;">
                            	<li><a href="#tabs-1">Active Teams</a></li>
                            <?php if($secure)
								{
                                	echo '<li><a href="#tabs-2">Archived Teams</a></li>';
								}
                           ?>
                           </ul>
                           <div id="tabs-1">
                           		<?php
									foreach ($ActiveHosOrs as $HosOr) 
                                    {
										$thisGroupType = $HosOr->GetGroupType();
										
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_hospital_orientation.'&id_team='.$HosOr->GetGroupID().'">';
											$this_url_end = '</a>';
											
											$edit_schedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$HosOr->GetGroupID().'">View / Edit Complete Schedule</a>';
																						
											if( $HosOr->TeamHasBranchRegion() )
											{
												
												$thisBranchRegion = $HosOr->LoadTeamsCurrentBranchRegion();
												
												if( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_BRANCH )
												{
													$thisBranch = $thisBranchRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$HosOr->GetGroupID().'">Branch: '.$thisBranch->GetBranchName().'</a>';
												}
												elseif( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_REGION )
												{
													$thisRegion = $thisBranchRegion->GetRegion();
													$thisBranch = $thisRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$HosOr->GetGroupID().'">'.$thisBranch->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												}
												
												
												
											} else {
												$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$HosOr->GetGroupID().'">'.'Set Branch Region!'.'</a>';
											}
											
											if( $HosOr->GroupHasVenue() )
											{
												$thisVenue = $HosOr->LoadGroupsCurrentVenue()->GetVenue();
												$thisGroupVenue = '<a href="'.$lnk_view_groups_venues_secure.'&id_group='.$HosOr->GetGroupID().'">'.$thisVenue->GetName().'</a>';
											} else {
												$thisGroupVenue = '<a href="'.$lnk_view_groups_venues_secure.'&id_group='.$HosOr->GetGroupID().'">Add Venue</a>';
											}
											
											if( $HosOr->GroupHasSchedule() or $HosOr->GroupHasScheduleDates())
											{
												
												if( $HosOr->GroupHasSchedule() )
												{
													$thisSchedule = $HosOr->LoadGroupsCurrentSchedule();
												
													$thisName = 'Every: '.$thisSchedule->GetRecurrencyInt().' '.$thisSchedule->GetRecurrencyString(). ' from '.funAusDateFormat($thisSchedule->GetStartDate());
													
													$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$HosOr->GetGroupID().'">'.$thisName.'</a>';
												} else {
													$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$HosOr->GetGroupID().'">Add Schedule</a>';
												}
													$thisAttendance = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$HosOr->GetGroupID().'">View Attendance</a>';
													$thisNotes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$HosOr->GetGroupID().'">Team Notes</a>';
												
											} else {
												$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$HosOr->GetGroupID().'">Add Schedule</a>';
												
												$thisAttendance = 'No Schedule or Dates set!';
												$thisNotes = 'No Schedule or Dates set!';
											}
											
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											$edit_schedule = ''; //may want to add a view schedule option here
																						
											
											
											
											if( $HosOr->TeamHasBranchRegion() )
											{
												
												$thisBranchRegion = $HosOr->LoadTeamsCurrentBranchRegion();
												
												if( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_BRANCH )
												{
													$thisBranch = $thisBranchRegion->GetBranch();
													
													$thisGroupRegion = 'Branch: '.$thisBranch->GetBranchName();
												}
												elseif( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_REGION )
												{
													$thisRegion = $thisBranchRegion->GetRegion();
													$thisBranch = $thisRegion->GetBranch();
													
													$thisGroupRegion = $thisBranch->GetBranchName().': '.$thisRegion->GetRegionName();
												}
												
											} else {
												$thisGroupRegion = 'Not yet set!';
											}
											
											if( $HosOr->GroupHasVenue() )
											{
												$thisVenue = $HosOr->LoadGroupsCurrentVenue()->GetVenue();
												$thisGroupVenue = $thisVenue->GetName();
											} else {
												$thisGroupVenue = 'Not yet set';
											}
											
											if( $HosOr->GroupHasSchedule() or $HosOr->GroupHasScheduleDates() )
											{
												
												if($HosOr->GroupHasSchedule())
												{
													$thisSchedule = $HosOr->LoadGroupsCurrentSchedule();
													
													$thisName = 'Every: '.$thisSchedule->GetRecurrencyInt().' '.$thisSchedule->GetRecurrencyString(). ' from '.funAusDateFormat($thisSchedule->GetStartDate());
													
													$thisGroupSchedule = $thisName;
												}
												else
												{
													$thisGroupSchedule = 'Dates Only!';
												}
												
												$thisAttendance = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$HosOr->GetGroupID().'">View Attendance</a>';
												$thisNotes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$HosOr->GetGroupID().'">Group Notes</a>';
											} else {
												$thisGroupSchedule = 'Not yet set';
												
												$thisAttendance = 'No Schedule or Dates set!';
												$thisNotes = 'No Schedule or Dates set!';
											}
											
											
										}
										
										
										
										echo '<div id="group_'.$HosOr->GetGroupID().'" class="form_item_major_block">';
											if($allow_deletes)
											{
												cntDeleteGroup($HosOr->GetGroupID(),$lnk_view_hospital_orientations_secure);
											}
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Team Name: '.$this_url.$HosOr->GetGroupName().$this_url_end.'</h2></div>';
												echo '<div class="form_item spaced"><span class="label">Start Date: </span> '.funAusDateFormat($HosOr->GetStartDate()).'</div>';
												echo '<div class="form_item spaced"><span class="label">End Date: </span> '.funAusDateFormat($HosOr->GetEndDate()).'</div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Current Region: '.$thisGroupRegion.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Current Venue: '.$thisGroupVenue.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Team Schedule: '.$thisGroupSchedule.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Team Attendance: '.$thisAttendance.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Team Notes: '.$thisNotes.'</h2></div>';
											echo '</div>';
										echo '</div>';
									}
								?>
                           		
                            </div> <!-- End Current Staff -->
                           <?php if($secure)
								{
                            echo '<div id="tabs-2">';
                            	
									foreach ($ArchivedHosOrs as $HosOr) 
                                    {
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_hospital_orientation.'&id_team='.$HosOr->GetGroupID().'">';
											$this_url_end = '</a>';
											
											if( $HosOr->TeamHasBranchRegion() )
											{
												$thisBranchRegion = $HosOr->LoadTeamsCurrentBranchRegion();
												
												if( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_BRANCH )
												{
													$thisBranch = $thisBranchRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$HosOr->GetGroupID().'">Branch: '.$thisBranch->GetBranchName().'</a>';
												}
												elseif( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_REGION )
												{
													$thisRegion = $thisBranchRegion->GetRegion();
													$thisBranch = $thisRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$HosOr->GetGroupID().'">'.$thisBranch->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												}
												
											} else {
												$thisGroupRegion = '';
											}
											
											$thisGroupType = $HosOr->GetGroupType();
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											
											if( $HosOr->TeamHasBranchRegion() )
											{
												$thisBranchRegion = $HosOr->LoadTeamsCurrentBranchRegion();
												
												if( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_BRANCH )
												{
													$thisBranch = $thisBranchRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions.'&id_group='.$HosOr->GetGroupID().'">Branch: '.$thisBranch->GetBranchName().'</a>';
												}
												elseif( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_REGION )
												{
													$thisRegion = $thisBranchRegion->GetRegion();
													$thisBranch = $thisRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions.'&id_group='.$HosOr->GetGroupID().'">'.$thisBranch->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												}
												
												
											} else {
												$thisGroupRegion = '';
											}
											
											$thisGroupType = $HosOr->GetGroupType();
										}
										
										echo '<div class="form_item_major_block">';
										if($allow_deletes)
											{
												cntDeleteGroup($HosOr->GetGroupID(),$lnk_view_hospital_orientations_secure);
											}
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Team Name: '.$this_url.$HosOr->GetGroupName().$this_url_end.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Region:</span>'.$thisGroupRegion.'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($HosOr->GetStartDate()).'</div>';
										echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($HosOr->GetEndDate()).'</div>';
										echo '</div>';
										echo '</div>';
									}
								
                                
                            echo '</div>';// <!-- End Archived Staff -->
								}
							?>
                        </div><!-- End Tabs Div -->
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->