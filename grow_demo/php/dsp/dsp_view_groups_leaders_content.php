			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_group; ?> <?php echo $url_add_group_leader; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        
						if( count($GroupsLeaders) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>Group does not have any leaders at present</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							foreach ($GroupsLeaders as $GroupLeader) 
							{
								$thisStaff = $GroupLeader->GetLeader();  //returns a staff record
								
								$StaffName = $thisStaff->GetFirstName().' '.$thisStaff->GetLastname();
								
								$Role = $GroupLeader->GetGroupRoleName();
								
								if($secure)
								{
									$this_url = '<a href="'.$lnk_add_edit_group_leader.'&id_group='.$GroupLeader->GetGroupID().'&id_group_leader='.$GroupLeader->GetGroupsRolesID().'">';
									$this_url_end = '</a>';
									
								}
								else
								{
									$this_url = '';
									$this_url_end = '';
								}
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$Role.': '.$this_url.$StaffName.$this_url_end.' '.funActingtoString($GroupLeader->GetActing()).'</h2></div>';
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($GroupLeader->GetStartDate()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($GroupLeader->GetEndDate()).'</div>';
									echo '</div>';
								echo '</div>';
								
							}
						}
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->