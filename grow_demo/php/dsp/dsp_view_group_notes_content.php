			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_group; ?> : <?php echo $url_add_group_note; ?> </h1></div>
           		
                <div class="form_row">
                	<div class="form_heading"><h2>Notes Dates</h2></div>
                	<ul>
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
								foreach($Dates as $Date)
								{
									$this_month = funGetMonth($Date);
									
									if($month == '' or $month != $this_month)
									{
										$month = $this_month;
										
										echo '<li><a href="#tabs-'.$counter.'">'.funMonthYearDateFormat($Date).'</a></li>'."\n";
										$counter++;
									}
								}
								?>
                            </ul>
                        	<?php
							$month = '';
							$total_counter = 1;
							$counter = 1;
								foreach($Dates as $Date)
								{
									$this_month = funGetMonth($Date);
									
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
									
									$link = $lnk_view_group_notes_by_date.'&Date='.funDateFormat($Date,'d/m/Y').'&id_group='.$GroupID; //link for add functionality. Sends Date in Y-m-d format
									
									if( $Group->HasGroupNoteOnDate($Date,$_SESSION['User']->GetSecurityLevel()) )
									{
										$str_class = 'edit';
										
									} else {
										
										if( $Group->HasAttendanceReasonRecordOnDate($Date) )
										{
											$str_class = 'reason';
											
										} else {
											$str_class = 'add';
										}
										
										
										
									}
									echo funCreateTabs(1).'<div class="form_item_block padded"><a class="'.$str_class.'" href="'.$link.'">'.funAusDateFormat($Date).'</a></div>'."\n";
									
									if($total_counter == count($Dates))
									{
										echo '</div><!-- End tab -->'."\n";
									}
									
									
									$total_counter++;
								} // end master loop
								
								
							?>
                       	</div> <!-- End Tabs div --> 
                     </ul>   
				</div> <!-- End Form Row -->
                <div class="form_row">
                	<!-- Non group attendance date note -->
                    <div class="form_heading"><h2>Other Notes</h2></div>
                    
                   <ul>
						<script>
                        $(function() {
                        $( "#tabs2" ).tabs();
                        });
                        </script>
                        <div id="tabs2" style="background: none repeat scroll 0% 0% transparent; font-family:inherit; font-size:inherit;">
                            <ul>
                            	<?php
								$month = '';
								$counter = 1;
								foreach($OtherDates as $Date)
								{
									$this_month = funGetMonth($Date);
									
									if($month == '' or $month != $this_month)
									{
										$month = $this_month;
										
										echo '<li><a href="#tabs-'.$counter.'">'.funMonthYearDateFormat($Date).'</a></li>'."\n";
										$counter++;
									}
								}
								?>
                            </ul>
                        	<?php
							$month = '';
							$total_counter = 1;
							$counter = 1;
								foreach($OtherDates as $Date)
								{
									$this_month = funGetMonth($Date);
									
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
									
									$link = $lnk_view_group_notes_by_date.'&Date='.funDateFormat($Date,'d/m/Y').'&id_group='.$GroupID; //link for add functionality. Sends Date in Y-m-d format
									
									if( $Group->HasGroupNoteOnDate($Date,$_SESSION['User']->GetSecurityLevel()) )
									{
										$str_class = 'edit';
										
									} else {
										
										if( $Group->HasAttendanceReasonRecordOnDate($Date) )
										{
											$str_class = 'reason';
											
										} else {
											$str_class = 'add';
										}
										
										
										
									}
									echo funCreateTabs(1).'<div class="form_item_block padded"><a class="'.$str_class.'" href="'.$link.'">'.funAusDateFormat($Date).'</a></div>'."\n";
									
									if($total_counter == count($Dates))
									{
										echo '</div><!-- End tab -->'."\n";
									}
									
									
									$total_counter++;
								} // end master loop
								
								
							?>
                       	</div> <!-- End Tabs div --> 
                     </ul>   
                </div> <!-- End Form Row -->
			</div><!--End Generic Form -->