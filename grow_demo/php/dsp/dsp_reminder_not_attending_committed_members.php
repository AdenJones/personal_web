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
							if(count($LapsedCommittedGrowers) == 0 )
							{
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>No Lapsed Committed Growers at this time!</h2></div>';
									echo '</div>';
								echo '</div>';
							} else {
														
								foreach( $LapsedCommittedGrowers as $thisMember )
								{
									$LastGroupAttended = Business\getLastGroupAttended($thisMember->GetUserID(),date('Y-m-d'))->fetch();
									
									if( $LastGroupAttended == NULL )
									{
										$LastGroup = 'No attendances so far!';
										$LastAttendedGroupDate = '';
									} else {
										$LastGroup = $LastGroupAttended['fld_group_name'];
										
										$LastAttendedGroupDate = $LastGroupAttended['last_attended'];
										
										if( $LastAttendedGroupDate == NULL )
										{
											$LastAttendedGroupDate = 'Error!';
										} 
									}
									
									$lnk_goto_committed = '<a class="inline" href="'.$lnk_add_edit_member_committed_dates.'&id_user='.$thisMember->GetUserID().'&id_committed='.$thisMember->GetMostRecentCommittedRecord()->GetCommittedID().'&return_to='.$lnk_reminder_not_attending_committed_members.'&return_immediate=1'.'">Go to Committed Record</a>';
									
									echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>'.$thisMember->GetFirstName().' '.$thisMember->GetLastName().': '.$lnk_goto_committed.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo 'Last attended '.$LastGroup.': '.$LastAttendedGroupDate;
										echo '</div>';
									echo '</div>';
								}
							}
						?>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->