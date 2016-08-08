			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $return_url; ?> : <?php echo $url_add_member_activity_date; ?> : <?php echo $url_add_member_committed_dates; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        
						if( count($UserActivityDates) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$MemberName.' does not have any Activity Dates at present!</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>Member Activity Dates</h2></div>';
								echo '</div>';
							
							foreach ($UserActivityDates as $ActivityDate) 
							{
								$this_url = '<a href="'.$lnk_add_edit_member_dates.'&id_user='.$ActivityDate->GetUserID().'&id_user_activity='.$ActivityDate->GetUserActivityID().'&return_to='.urlencode($ReturnTo).'">';
								$this_url_end = '</a>';
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$this_url.'Edit Activity Record'.$this_url_end.'</h2></div>';
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($ActivityDate->GetStartDate()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($ActivityDate->GetEndDate()).'</div>';
									echo '</div>';
								echo '</div>';
								
							}
							echo '</div>';
						
						}
						
						if( count($MemberCommittedDates) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$MemberName.' does not have any Committed Dates at present!</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>Member Committed Dates</h2></div>';
								echo '</div>';
							
							foreach ($MemberCommittedDates as $CommittedDate) 
							{
								$this_url = '<a href="'.$lnk_add_edit_member_committed_dates.'&id_user='.$CommittedDate->GetUserID().'&id_committed='.$CommittedDate->GetCommittedID().'&return_to='.urlencode($ReturnTo).'">';
								$this_url_end = '</a>';
								
								echo '<div class="form_item_major_block">';
									cntDeleteMemComDtes($CommittedDate->GetUserID(),$CommittedDate->GetCommittedID(),1,urlencode($ReturnTo));
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$this_url.'Edit Committed Record'.$this_url_end.'</h2></div>';
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($CommittedDate->GetStartDate()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($CommittedDate->GetEndDate()).'</div>';
									echo '</div>';
									
								echo '</div>';
								
							}
							echo '</div>';
						
						}
						
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->