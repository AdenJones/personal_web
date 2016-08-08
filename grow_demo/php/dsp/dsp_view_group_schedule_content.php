			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_group; ?> </h1><h1> <?php echo $url_add_group_schedule_date; ?> <?php echo $url_add_group_schedule; ?>  <?php echo $url_add_group_recess; ?></h1></div>
           		
                <div class="form_row">
                	<h2>Individual Dates</h2>
						<div class="form_row extra_padding">
					
						<script>
                        $(function() {
                        $( "#tabs" ).tabs();
                        });
                        </script>
                        <div id="tabs" style="background: none repeat scroll 0% 0% transparent; font-family:inherit; font-size:inherit;">
                            <ul>
                            	<?php
								$month = '';
								$counter = 1;
								foreach($GroupScheduleDates as $Date)
								{
									$this_month = funGetMonth($Date->GetDate());
									
									if($month == '' or $month != $this_month)
									{
										$month = $this_month;
										
										echo '<li><a href="#tabs-'.$counter.'">'.funMonthYearDateFormat($Date->GetDate()).'</a></li>'."\n";
										$counter++;
									}
									
									
									
									
								}
								?>
                            </ul>
                        	<?php
							$month = '';
							$total_counter = 1;
							$counter = 1;
								foreach($GroupScheduleDates as $Date)
								{
									$this_month = funGetMonth($Date->GetDate());
									
									if($month == '' or $month != $this_month)
									{
										$month = $this_month;
										
										if( $counter != 1 )
										{
											echo '</div><!-- End tab -->'."\n";
										}
										
										echo '<div id="tabs-'.$counter.'">'."\n";
										
										$counter++;
										
									}
									
									if($secure)
									{
										
										$link = '<a href="'.$lnk_add_edit_group_schedule_dates.'&id_group='.$GroupID.'&id_grp_sch_date='.$Date->GetGroupScheduleDateID().'">'.funAusDateFormat($Date->GetDate()).'</a>'; //link for edit functionality
									} else {
										
										$link = funAusDateFormat($Date->GetDate());
									}
									
									echo funCreateTabs(1).'<div class="form_item_block padded">'.$link.'</div>'."\n";
									
									if($total_counter == count($GroupScheduleDates))
									{
										echo '</div><!-- End tab -->'."\n";
									}
									
									
									$total_counter++;
								} // end master loop
								
								
							?>
                       	</div> <!-- End Tabs div -->
				</div> <!-- End Form Row -->
                
                <div class="form_row">
                	<h2>Recurring Schedules</h2>
						<?php
						
						if(count($GroupsSchedules) == 0)
						{
							echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>No Schedule Records at this time!</h2></div>';
									echo '</div>';
								echo '</div>';
						} else {
							
							foreach ($GroupsSchedules as $Schedule) 
							{
								
								if($secure)
								{
									$this_url = '<a href="'.$lnk_add_edit_group_schedule.'&id_group='.$GroupID.'&id_group_schedule='.$Schedule->GetGroupScheduleID().'">';
									$this_url_end = '</a>';
									
								}
								else
								{
									$this_url = '';
									$this_url_end = '';
								}
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$this_url.'Recurrency: Every '.$Schedule->GetRecurrencyInt().' '.$Schedule->GetRecurrencyString().$this_url_end.'</h2></div>';
									echo '</div>';
									echo '<div class="form_row">';
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($Schedule->GetStartDate()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($Schedule->GetEndDate()).'</div>';
									echo '</div>';
									echo '<div class="form_row">';
									echo '<div class="form_item spaced"><span class="label">Start Time:</span> '.funTimeFormat($Schedule->GetStartTime()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Time:</span> '.funTimeFormat($Schedule->GetEndTime()).'</div>';
									echo '</div>';
								echo '</div>';
								
							}
						}
						?>  
						
				</div> <!-- End Form Row -->
                
                <div class="form_row">
                	<h2>Recess Periods</h2>
						<?php
						
						if(count($GroupRecesses) == 0)
						{
							echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>No Recess Periods at this time!</h2></div>';
									echo '</div>';
								echo '</div>';
						} else {
							
							foreach ($GroupRecesses as $GroupRecess) 
							{
								
								if($secure)
								{
									$this_url = '<a href="'.$lnk_add_edit_group_recess.'&id_group='.$GroupID.'&id_group_recess='.$GroupRecess->GetGroupRecessID().'">';
									$this_url_end = '</a>';
									
								}
								else
								{
									$this_url = '';
									$this_url_end = '';
								}
								
								echo '<div class="form_item_major_block">';
									
									echo '<div class="form_row">';
									echo $this_url;
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($GroupRecess->GetStartDate()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($GroupRecess->GetEndDate()).'</div>';
									echo $this_url_end;
									echo '</div>';
									
								echo '</div>';
								
							}
						}
						?>  
						
                        
				</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->