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
						
						if( $_SESSION['User']->GetUserTypeName() != $StateUser )
						{
							if(count($StaffDuePoliceChecks) == 0 )
							{
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>No Staff Police Checks due at this time!</h2></div>';
									echo '</div>';
								echo '</div>';
								
							} else {
									
								echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.count($StaffDuePoliceChecks).' Staff Due Police Checks at this time!</h2></div>';
														
								foreach( $StaffDuePoliceChecks as $thisStaff )
								{
									$lnk_goto_staff_reminders = '<a class="inline" href="'.$lnk_view_staff_reminder_dates.'&id_user='.$thisStaff->GetUserID().'">Go to Reminders</a>';
									
									$Reminder = $thisStaff->GetLastPoliceCheck();
									
									$Reminder_text = ( $Reminder == NULL ? 'never checked!' :  funAusDateFormat($Reminder->GetDate()) );
									
									echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>'.$thisStaff->GetFirstName().' '.$thisStaff->GetLastName().': '.$lnk_goto_staff_reminders.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo 'Last Checked: '.$Reminder_text;
										echo '</div>';
									echo '</div>';
								}
								
								echo '</div>';
								echo '</div>';
							}
						}
							
						
						?>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->