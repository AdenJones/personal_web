		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?> - <?php echo $url_add_community_outreach ?></h1></div>
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
									foreach ($ActiveComOut as $ComOut) 
                                    {
										$thisGroupType = $ComOut->GetGroupType();
										
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_community_outreach.'&id_team='.$ComOut->GetGroupID().'">';
											$this_url_end = '</a>';
											
											$edit_schedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$ComOut->GetGroupID().'">View / Edit Complete Schedule</a>';
																						
											if( $ComOut->TeamHasBranchRegion() )
											{
												
												$thisBranchRegion = $ComOut->LoadTeamsCurrentBranchRegion();
												
												if( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_BRANCH )
												{
													$thisBranch = $thisBranchRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$ComOut->GetGroupID().'">Branch: '.$thisBranch->GetBranchName().'</a>';
												}
												elseif( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_REGION )
												{
													$thisRegion = $thisBranchRegion->GetRegion();
													$thisBranch = $thisRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$ComOut->GetGroupID().'">'.$thisBranch->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												}
												
												
												
											} else {
												$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$ComOut->GetGroupID().'">'.'Set Branch Region!'.'</a>';
											}
											
											if( $ComOut->GroupHasVenue() )
											{
												$thisVenue = $ComOut->LoadGroupsCurrentVenue()->GetVenue();
												$thisGroupVenue = '<a href="'.$lnk_view_groups_venues_secure.'&id_group='.$ComOut->GetGroupID().'">'.$thisVenue->GetName().'</a>';
											} else {
												$thisGroupVenue = '<a href="'.$lnk_view_groups_venues_secure.'&id_group='.$ComOut->GetGroupID().'">Add Venue</a>';
											}
											
											if( $ComOut->GroupHasSchedule() or $ComOut->GroupHasScheduleDates())
											{
												
												if( $ComOut->GroupHasSchedule() )
												{
													$thisSchedule = $ComOut->LoadGroupsCurrentSchedule();
												
													$thisName = 'Every: '.$thisSchedule->GetRecurrencyInt().' '.$thisSchedule->GetRecurrencyString(). ' from '.funAusDateFormat($thisSchedule->GetStartDate());
													
													$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$ComOut->GetGroupID().'">'.$thisName.'</a>';
												} else {
													$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$ComOut->GetGroupID().'">Add Schedule</a>';
												}
													$thisAttendance = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$ComOut->GetGroupID().'">View Attendance</a>';
													$thisNotes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$ComOut->GetGroupID().'">Team Notes</a>';
												
											} else {
												$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$ComOut->GetGroupID().'">Add Schedule</a>';
												
												$thisAttendance = 'No Schedule or Dates set!';
												$thisNotes = 'No Schedule or Dates set!';
											}
											
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											$edit_schedule = ''; //may want to add a view schedule option here
																						
											
											
											
											if( $ComOut->TeamHasBranchRegion() )
											{
												
												$thisBranchRegion = $ComOut->LoadTeamsCurrentBranchRegion();
												
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
											
											if( $ComOut->GroupHasVenue() )
											{
												$thisVenue = $ComOut->LoadGroupsCurrentVenue()->GetVenue();
												$thisGroupVenue = $thisVenue->GetName();
											} else {
												$thisGroupVenue = 'Not yet set';
											}
											
											if( $ComOut->GroupHasSchedule() or $ComOut->GroupHasScheduleDates() )
											{
												
												if($ComOut->GroupHasSchedule())
												{
													$thisSchedule = $ComOut->LoadGroupsCurrentSchedule();
													
													$thisName = 'Every: '.$thisSchedule->GetRecurrencyInt().' '.$thisSchedule->GetRecurrencyString(). ' from '.funAusDateFormat($thisSchedule->GetStartDate());
													
													$thisGroupSchedule = $thisName;
												}
												else
												{
													$thisGroupSchedule = 'Dates Only!';
												}
												
												$thisAttendance = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$ComOut->GetGroupID().'">View Attendance</a>';
												$thisNotes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$ComOut->GetGroupID().'">Group Notes</a>';
											} else {
												$thisGroupSchedule = 'Not yet set';
												
												$thisAttendance = 'No Schedule or Dates set!';
												$thisNotes = 'No Schedule or Dates set!';
											}
											
											
										}
										
										
										
										echo '<div id="group_'.$ComOut->GetGroupID().'" class="form_item_major_block">';
											if($allow_deletes)
											{
												cntDeleteGroup($ComOut->GetGroupID(),$lnk_view_community_outreach_secure);
											}
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Team Name: '.$this_url.$ComOut->GetGroupName().$this_url_end.'</h2></div>';
												echo '<div class="form_item spaced"><span class="label">Start Date: </span> '.funAusDateFormat($ComOut->GetStartDate()).'</div>';
												echo '<div class="form_item spaced"><span class="label">End Date: </span> '.funAusDateFormat($ComOut->GetEndDate()).'</div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Current Region: '.$thisGroupRegion.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Current Venue: '.$thisGroupVenue.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Event Schedule: '.$thisGroupSchedule.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Event Attendance: '.$thisAttendance.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Event Notes: '.$thisNotes.'</h2></div>';
											echo '</div>';
										echo '</div>';
									}
								?>
                           		
                            </div> <!-- End Current Staff -->
                           <?php if($secure)
								{
                            echo '<div id="tabs-2">';
                            	
									foreach ($ArchivedComOut as $ComOut) 
                                    {
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_social_event.'&id_team='.$ComOut->GetGroupID().'">';
											$this_url_end = '</a>';
											
											if( $ComOut->TeamHasBranchRegion() )
											{
												$thisBranchRegion = $ComOut->LoadTeamsCurrentBranchRegion();
												
												if( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_BRANCH )
												{
													$thisBranch = $thisBranchRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$ComOut->GetGroupID().'">Branch: '.$thisBranch->GetBranchName().'</a>';
												}
												elseif( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_REGION )
												{
													$thisRegion = $thisBranchRegion->GetRegion();
													$thisBranch = $thisRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions_secure.'&id_group='.$ComOut->GetGroupID().'">'.$thisBranch->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												}
												
											} else {
												$thisGroupRegion = '';
											}
											
											$thisGroupType = $ComOut->GetGroupType();
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											
											if( $ComOut->TeamHasBranchRegion() )
											{
												$thisBranchRegion = $ComOut->LoadTeamsCurrentBranchRegion();
												
												if( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_BRANCH )
												{
													$thisBranch = $thisBranchRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions.'&id_group='.$ComOut->GetGroupID().'">Branch: '.$thisBranch->GetBranchName().'</a>';
												}
												elseif( $thisBranchRegion->IsBranchOrRegion() == GROUP_REGION_REGION )
												{
													$thisRegion = $thisBranchRegion->GetRegion();
													$thisBranch = $thisRegion->GetBranch();
													
													$thisGroupRegion = '<a href="'.$lnk_view_branches_regions.'&id_group='.$ComOut->GetGroupID().'">'.$thisBranch->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												}
												
												
											} else {
												$thisGroupRegion = '';
											}
											
											$thisGroupType = $ComOut->GetGroupType();
										}
										
										echo '<div class="form_item_major_block">';
										if($allow_deletes)
											{
												cntDeleteGroup($ComOut->GetGroupID(),$lnk_view_community_outreach_secure);
											}
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Event Name: '.$this_url.$ComOut->GetGroupName().$this_url_end.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Region:</span>'.$thisGroupRegion.'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($ComOut->GetStartDate()).'</div>';
										echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($ComOut->GetEndDate()).'</div>';
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