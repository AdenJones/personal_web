		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?> - <?php echo $url_add_group ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                    	
                    
                        <script>
						$(function() {
						$( "#tabs" ).tabs();
						});
						</script>
                        <div id="tabs" style="background: none repeat scroll 0% 0% transparent; font-family:inherit; font-size:inherit;">
                            <ul style="height: 30px;">
                            	<li><a href="#tabs-1">Active Groups</a></li>
                            <?php if($secure)
								{
                                	echo '<li><a href="#tabs-2">Archived Groups</a></li>';
								}
                           ?>
                           </ul>
                           <div id="tabs-1">
                           		<?php
									foreach ($ActiveGroups as $Group) 
                                    {
										$thisGroupType = $Group->GetGroupType();
										
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_group.'&id_group='.$Group->GetGroupID().'">';
											$this_url_end = '</a>';
											
											$edit_schedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$Group->GetGroupID().'">View / Edit Complete Schedule</a>';
																						
											if( $Group->GroupHasRegion() )
											{
												$thisRegion = $Group->LoadGroupsCurrentRegion()->GetRegion();
												$thisGroupRegion = '<a href="'.$lnk_view_groups_regions_secure.'&id_group='.$Group->GetGroupID().'">'.$thisRegion->GetBranch()->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												
											} else {
												$thisGroupRegion = '<a href="'.$lnk_view_groups_regions_secure.'&id_group='.$Group->GetGroupID().'">'.'Set Region!'.'</a>';
											}
											
											if( $Group->GroupHasVenue() )
											{
												$thisVenue = $Group->LoadGroupsCurrentVenue()->GetVenue();
												$thisGroupVenue = '<a href="'.$lnk_view_groups_venues_secure.'&id_group='.$Group->GetGroupID().'">'.$thisVenue->GetName().'</a>';
											} else {
												$thisGroupVenue = '<a href="'.$lnk_view_groups_venues_secure.'&id_group='.$Group->GetGroupID().'">Add Venue</a>';
											}
											
											if( $Group->GroupHasSchedule() or $Group->GroupHasScheduleDates())
											{
												
												if( $Group->GroupHasSchedule() )
												{
													$thisSchedule = $Group->LoadGroupsCurrentSchedule();
												
													$thisName = 'Every: '.$thisSchedule->GetRecurrencyInt().' '.$thisSchedule->GetRecurrencyString(). ' from '.funAusDateFormat($thisSchedule->GetStartDate());
													
													$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$Group->GetGroupID().'">'.$thisName.'</a>';
												} else {
													$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$Group->GetGroupID().'">Add Schedule</a>';
												}
													$thisAttendance = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$Group->GetGroupID().'">View Attendance</a>';
													$thisNotes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$Group->GetGroupID().'">Group Notes</a>';
												
											} else {
												$thisGroupSchedule = '<a href="'.$lnk_view_group_schedule_secure.'&id_group='.$Group->GetGroupID().'">Add Schedule</a>';
												
												$thisAttendance = 'No Schedule or Dates set!';
												$thisNotes = 'No Schedule or Dates set!';
											}
											
											$thisLeaders = '<a href="'.$lnk_view_groups_leaders_secure.'&id_group='.$Group->GetGroupID().'">Group Leaders</a>';
											
											
											
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											$edit_schedule = ''; //may want to add a view schedule option here
																						
											if( $Group->GroupHasRegion() )
											{
												$thisRegion = $Group->LoadGroupsCurrentRegion()->GetRegion();
												$thisGroupRegion = $thisRegion->GetBranch()->GetBranchName().': '.$thisRegion->GetRegionName();
											} else {
												$thisGroupRegion = 'Not yet set!';
											}
											
											if( $Group->GroupHasVenue() )
											{
												$thisVenue = $Group->LoadGroupsCurrentVenue()->GetVenue();
												$thisGroupVenue = $thisVenue->GetName();
											} else {
												$thisGroupVenue = 'Not yet set';
											}
											
											if( $Group->GroupHasSchedule() or $Group->GroupHasScheduleDates() )
											{
												
												if($Group->GroupHasSchedule())
												{
													$thisSchedule = $Group->LoadGroupsCurrentSchedule();
													
													$thisName = 'Every: '.$thisSchedule->GetRecurrencyInt().' '.$thisSchedule->GetRecurrencyString(). ' from '.funAusDateFormat($thisSchedule->GetStartDate());
													
													$thisGroupSchedule = $thisName;
												}
												else
												{
													$thisGroupSchedule = 'Dates Only!';
												}
												
												$thisAttendance = '<a href="'.$lnk_view_group_attendance_secure.'&id_group='.$Group->GetGroupID().'">View Attendance</a>';
												$thisNotes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$Group->GetGroupID().'">Group Notes</a>';
											} else {
												$thisGroupSchedule = 'Not yet set';
												
												$thisAttendance = 'No Schedule or Dates set!';
												$thisNotes = 'No Schedule or Dates set!';
											}
											
											if($_SESSION['User']->GetUserTypeName() == $GroupUser)
											{
												$thisLeaders = '';
											} else {
												$thisLeaders = '<a href="'.$lnk_view_groups_leaders.'&id_group='.$Group->GetGroupID().'">Group Leaders</a>';
											}
											
											
										}
										
										
										
										echo '<div id="group_'.$Group->GetGroupID().'" class="form_item_major_block">';
											if($allow_deletes)
											{
												cntDeleteGroup($Group->GetGroupID(),$lnk_view_groups_secure);
											}
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Group Name: '.$this_url.$Group->GetGroupName().$this_url_end.'</h2></div>';
												echo '<div class="form_item spaced"><span class="label">Start Date: </span> '.funAusDateFormat($Group->GetStartDate()).'</div>';
												echo '<div class="form_item spaced"><span class="label">End Date: </span> '.funAusDateFormat($Group->GetEndDate()).'</div>';
												echo '<div class="form_item spaced"><span class="label">Group Type: </span> '.$thisGroupType->GetGroupTypeName().'</div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Current Region: '.$thisGroupRegion.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Current Venue: '.$thisGroupVenue.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Group Schedule: '.$thisGroupSchedule.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Group Leaders: '.$thisLeaders.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Group Attendance: '.$thisAttendance.'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Group Notes: '.$thisNotes.'</h2></div>';
											echo '</div>';
										echo '</div>';
									}
								?>
                           		
                            </div> <!-- End Current Staff -->
                           <?php if($secure)
								{
                            echo '<div id="tabs-2">';
                            	
									foreach ($ArchivedGroups as $Group) 
                                    {
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_group.'&id_group='.$Group->GetGroupID().'">';
											$this_url_end = '</a>';
											
											if( $Group->GroupHasRegion() )
											{
												$thisRegion = $Group->LoadGroupsCurrentRegion()->GetRegion();
												$thisGroupRegion = '<a href="'.$lnk_view_groups_regions_secure.'&id_group='.$Group->GetGroupID().'">'.$thisRegion->GetBranch()->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												
											} else {
												$thisGroupRegion = '';
											}
											
											$thisGroupType = $Group->GetGroupType();
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											
											if( $Group->GroupHasRegion() )
											{
												$thisRegion = $Group->LoadGroupsCurrentRegion()->GetRegion();
												$thisGroupRegion = '<a href="'.$lnk_view_groups_regions.'&id_group='.$Group->GetGroupID().'">'.$thisRegion->GetBranch()->GetBranchName().': '.$thisRegion->GetRegionName().'</a>';
												
											} else {
												$thisGroupRegion = '';
											}
											
											$thisGroupType = $Group->GetGroupType();
										}
										
										echo '<div class="form_item_major_block">';
										if($allow_deletes)
											{
												cntDeleteGroup($Group->GetGroupID(),$lnk_view_groups_secure);
											}
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Group Name: '.$this_url.$Group->GetGroupName().$this_url_end.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Region:</span>'.$thisGroupRegion.'</div>';
										echo '<div class="form_item spaced"><span class="label">Group Type:</span> '.$thisGroupType->GetGroupTypeName().'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($Group->GetStartDate()).'</div>';
										echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($Group->GetEndDate()).'</div>';
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