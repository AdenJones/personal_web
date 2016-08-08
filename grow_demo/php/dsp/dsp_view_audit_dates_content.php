			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> - <?php echo $Back; ?> - <?php echo $add_audit_date; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        
						if( count($VenueAuditDates) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$Venue->GetName().' does not have any Audit Dates at present!</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$Venue->GetName().' Audit Dates</h2></div>';
								echo '</div>';
							
							foreach ($VenueAuditDates as $AuditDate) 
							{
								
								if( $secure )
								{
									$AuditDateName = '<a href="'.$lnk_add_edit_audit_date.'&id_audit_date='.$AuditDate->GetVenueAuditID().'">Edit Audit Record</a>';
								} else {
									$AuditDateName = '';
								}
								
								if( funBoolToString($AuditDate->GetComplete()) == 'true')
								{
									$Complete = 'Audit Complete!';
								} else {
									$Complete = 'Audit Not Yet Complete!';
								}
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.funAusDateFormat($AuditDate->GetAuditDate()).' '.$AuditDateName.'</h2></div>';
									echo '<div class="form_item spaced"><span class="label">Status:</span> '.$Complete.'</div>';
									echo '<div class="form_item spaced"><span class="label">Notes:</span> '.$AuditDate->GetNotes().'</div>';
									echo '</div>';
								echo '</div>';
								
							}
							echo '</div>';
						
					}
						
						
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->