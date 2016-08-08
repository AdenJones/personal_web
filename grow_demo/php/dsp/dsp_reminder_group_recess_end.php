		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> </h1></div>
                   	</div>
                    
					<div class="form_row">
						<?php
							if(count($displayGroups) == 0 )
							{
								echo '<div class="form_item_major_block">';
								echo 	'<div class="form_row">';
								echo 		'<div class="form_heading"><h2>No Groups at this time!</h2></div>';
								echo 	'</div>';
								echo '</div>';
							} else {
														
								foreach( $displayGroups as $thisGroup )
								{
									
									$thisUser = $_SESSION['User'];
									
									if($thisUser->CheckUserPage('view_groups&access=secure'))
									{
										$this_url = '<a href="'.$lnk_view_groups_secure.'#group_'.$thisGroup->GetGroupID().'">'.$thisGroup->GetGroupName().'</a>';
									} elseif($thisUser->CheckUserPage('view_groups'))
									{
										$this_url = '<a href="'.$lnk_view_groups.'#group_'.$thisGroup->GetGroupID().'">'.$thisGroup->GetGroupName().'</a>';
									} else {
										$this_url = $thisGroup->GetGroupName();
									}
																			
											if( $thisGroup->GroupHasRegion() )
											{
												$thisRegion = $thisGroup->LoadGroupsCurrentRegion()->GetRegion();
												$thisGroupRegion = $thisRegion->GetBranch()->GetBranchName().': '.$thisRegion->GetRegionName();
											} else {
												$thisGroupRegion = 'Not yet set!';
											}
											
											if( $thisGroup->GroupHasVenue() )
											{
												$thisVenue = $thisGroup->LoadGroupsCurrentVenue()->GetVenue();
												$thisGroupVenue = $thisVenue->GetName();
											} else {
												$thisGroupVenue = 'Not yet set';
											}
											
											
									echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										echo 	'<div class="form_heading"><h2>'.$this_url.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo 	'<div class="form_heading"><h2>Current Region: '.$thisGroupRegion.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo 	'<div class="form_heading"><h2>Current Venue: '.$thisGroupVenue.'</h2></div>';
										echo '</div>';
									echo '</div>';
								}
							}
						?>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->