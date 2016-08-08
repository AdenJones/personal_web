			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?></h1></div>
           		
                
                <div class="form_row">
                	<h2>Notes</h2>
						<?php
						
						if(count($GroupNotes) == 0)
						{
							echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>No Group Notes at this time!</h2></div>';
									echo '</div>';
								echo '</div>';
						} else {
						
						echo	'<ul>
						<script>
                        $(function() {
                        $( "#tabs2" ).tabs();
                        });
                        </script>
                        <div id="tabs2" style="background: none repeat scroll 0% 0% transparent; font-family:inherit; font-size:inherit;">
                            <ul>';
                            	
								$month = '';
								$counter = 1;
								foreach($GroupNotes as $Note)
								{
									$this_month = funGetMonth($Note->GetDated());
									
									if($month == '' or $month != $this_month)
									{
										$month = $this_month;
										
										echo '<li><a href="#tabs-'.$counter.'">'.funMonthYearDateFormat($Note->GetDated()).'</a></li>'."\n";
										$counter++;
									}
								}
                            
							echo '</ul>';
                        	
							$month = '';
							$total_counter = 1;
							$counter = 1;
								foreach($GroupNotes as $Note)
								{
									$this_month = funGetMonth($Note->GetDated());
									
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
									
										if($Note->GetCreator() == $_SESSION['User']->GetUserID())
										{
											$this_url = '<a href="'.$lnk_add_edit_group_note.'&id_group_note='.$Note->GetNoteID().'&return_address='.$returnAddress.'">';
											$this_url_end = '</a>';
											
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
										}
										
										if($Note->ViewedByUserToday($_SESSION['User']->GetUserID()))
										{
											$thisNew = 'NEW!';
										} else {
											$thisNew = '';
										}
										
										$ViewGroupsNotes = '<a href="'.$lnk_view_group_notes_secure.'&id_group='.$Note->GetGroupID().'">'.$Note->GetGroupName().'</a>'; 
										
										$CreatorScreenName = Membership\User::LoadUserUnSafe($Note->GetCreator())->GetScreenName();
										
										echo '<div class="form_item_major_block">';
											echo '<div class="form_row">';
											echo '<div class="form_heading"><h2>By: '.$this_url.$CreatorScreenName.$this_url_end.' '.$thisNew.' Group: '.$ViewGroupsNotes.' '.funAusDateFormat($Note->GetDated()).'</h2></div>';
											echo '</div>';
											echo '<div class="form_row">';
											echo '<div class="form_item spaced"><span class="label">Importance:</span> '.$Note->GetImportanceName().'</div>';
											echo '<div class="form_item spaced"><span class="label">Incident Report:</span> '.funBoolToString($Note->GetIncidentReport()).'</div>';
											echo '<div class="form_item spaced"><span class="label">Creator:</span> '.$CreatorScreenName.'</div>';
											echo '</div>';
											echo '<div class="form_row">';
											echo '<div class="form_item spaced"><span class="label">Note:</span> '.$Note->GetNote().'</div>';
											echo '</div>';
										echo '</div>';
										
									}
										
									if($total_counter == count($GroupNotes))
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