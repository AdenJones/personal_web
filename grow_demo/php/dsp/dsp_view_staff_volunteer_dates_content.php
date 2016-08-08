			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_staff; ?> <?php echo $url_add_staff_activity_date; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        
						if( count($UserStaffActivityDates) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$StaffName.' does not have any Activity Dates at present!</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							foreach ($UserStaffActivityDates as $ActivityDate) 
							{
								
								if($secure)
								{
									$this_url = '<a href="'.$lnk_add_edit_staff_volunteer_dates.'&id_user='.$ActivityDate->GetUserID().'&id_user_activity='.$ActivityDate->GetUserActivityID().'">';
									$this_url_end = '</a>';
									
								}
								else
								{
									$this_url = '';
									$this_url_end = '';
								}
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$this_url.'Edit Activity Record'.$this_url_end.'</h2></div>';
									echo '<div class="form_item spaced"><span class="label">Role:</span> '.$ActivityDate->GetStaffRole()->GetRoleName().'</div>';
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($ActivityDate->GetStartDate()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($ActivityDate->GetEndDate()).'</div>';
									echo '</div>';
								echo '</div>';
								
							}
						}
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->