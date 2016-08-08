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
						
						if( count($Venues) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>No Venue Audits due at this time!</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							echo '<div class="form_item_major_block">';
							echo '<div class="form_row">';
							echo '<div class="form_heading"><h2>'.count($Venues).' Venues Due Audits at this time!</h2></div>';
													
							foreach( $Venues as $Venue )
							{
								$lnk_goto_venue_audits = '<a class="inline" href="'.$lnk_view_audit_dates_secure.'&id_venue='.$Venue->GetVenueID().'">Go to Venues Audit Dates</a>';
								
								$Reminder = $Venue->NextAudit();
								
								$Reminder_text = ( $Reminder == NULL ? 'never audited!' :  funAusDateFormat($Reminder->GetAuditDate()) );
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$Venue->GetName().': '.$lnk_goto_venue_audits.'</h2></div>';
									echo '</div>';
									echo '<div class="form_row">';
									echo 'Next Due: '.$Reminder_text;
									echo '</div>';
								echo '</div>';
							}
							
							echo '</div>';
							echo '</div>';
						}
						
						
						?>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->