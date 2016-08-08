			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name.': '.funAusDateFormat(funSfDateStr($Date)); ?> : <?php echo $url_return_to_group_notes; ?> : <?php echo $url_add_group_note; ?> </h1></div>
           		
                
                <div class="form_row">
                	<h2>Notes</h2>
						<?php
						
						if(count($GroupNotes) == 0)
						{
							echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>Group Notes at this time!</h2></div>';
									echo '</div>';
								echo '</div>';
						} else {
							
							foreach ($GroupNotes as $Note) 
							{
								
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
								
								$CreatorScreenName = Membership\User::LoadUserUnSafe($Note->GetCreator())->GetScreenName();
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>By: '.$this_url.$CreatorScreenName.$this_url_end.' '.$thisNew.'</h2></div>';
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
						}
						?>  
						
				</div> <!-- End Form Row -->
                
                
			</div><!--End Generic Form -->