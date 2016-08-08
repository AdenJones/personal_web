			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_staff; ?> <?php echo $url_add_state_user_state_activity_date; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        
						if( count($state_activity_dates) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$StaffName.' does not have any State Activity Dates at present!</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							foreach ($state_activity_dates as $state_activity_date) 
							{
								
								if($secure)
								{
									$this_url = '<a href="'.$lnk_add_edit_state_user_state_activity_dates.'&id_user='.$state_activity_date->get_user_id().'&state_activity_date_id='.$state_activity_date->get_state_activity_date_id().'">';
									$this_url_end = '</a>';
									
								}
								else
								{
									$this_url = '';
									$this_url_end = '';
								}
								
								$this_branch = $state_activity_date->get_branch();
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$this_url.$this_branch->GetBranchName().$this_url_end.'</h2></div>';
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($state_activity_date->get_start_date()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($state_activity_date->get_end_date()).'</div>';
									echo '</div>';
								echo '</div>';
								
							}
						}
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->