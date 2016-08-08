		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name.': '.$UserName ?></h1><?php 
							if($BadUser or ($BadMember and $BadStaffVol))
							{  }
							else 
							{ 
								if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin)
								{
									cntDeleteGenericImproved('del_user','jsDelUser',$UserID,'Are you sure you want to delete this user?','position:absolute;top:-30px;left:-37px;'); 
								}
							}
								
						?></div>
                   	</div>
                    <div class="form_row">
                        <?php
							if($BadUser or ($BadMember and $BadStaffVol))
							{ echo '<div class="criticial_error_message">Bad User ID submitted!</div>'; }
							else 
							{
								if($BadMember == true)
									{
										$url_jump_to_member = '<a href="'.$lnk_add_edit_member.'&id_user='.$User->GetUserID().'&create_new=1'.$return_to.'">Create Member Record</a>';
										
										echo '<div class="form_item_major_block">';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>'.$url_jump_to_member.'</h2></div>';
											echo '</div>';
										echo '</div>';
										
									} else {
										
										$url_jump_to_member = ' - <a href="'.$lnk_add_edit_member.'&id_user='.$User->GetUserID().$return_to.'">Go To Member Record</a>';
										
										echo '<div class="form_item_major_block">';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>'.$Member->GetFirstName().' '.$Member->GetLastName().$url_jump_to_member.'</h2></div>';
											echo '</div>';
										echo '</div>';
										
										if(count($MemberCommittedDates) == 0)
										{
											$MemComString = ' doesn\'t have any committed dates at present!';
										} else {
											$MemComString = ' has '.count($MemberCommittedDates).' Committed Date records!';
										}
										
										echo '<div class="form_item_major_block">';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>'.$Member->GetFirstName().' '.$Member->GetLastName().$MemComString.'</h2></div>';
												echo '<div class="form_row">'.$url_add_member_committed_dates.'</div>';
											echo '</div>';
										echo '</div>';
										
										if(count($MemberCommittedDates) > 0)
										{
											foreach ($MemberCommittedDates as $CommittedDate) 
											{
												$this_url = '<a href="'.$lnk_add_edit_member_committed_dates.'&id_user='.$CommittedDate->GetUserID().'&id_committed='.$CommittedDate->GetCommittedID().'&return_to=view_user">';
												$this_url_end = '</a>';
												
												echo '<div class="form_item_major_block">';
													if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin or $_SESSION['User']->GetUserTypeName() == $StateUser)
													{
														cntDeleteMemComDtesImproved($CommittedDate->GetUserID(),$CommittedDate->GetCommittedID(),1);
													}
													echo '<div class="form_row">';
													echo '<div class="form_heading"><h2>'.$this_url.'Edit Committed Record'.$this_url_end.'</h2></div>';
													echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($CommittedDate->GetStartDate()).'</div>';
													echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($CommittedDate->GetEndDate()).'</div>';
													echo '</div>';
													
												echo '</div>';
												
											} //end for each record
										} //end if has records
										
									}// end else bad memmber
							}
							
                        ?>
					</div> <!-- End Form Row -->
					<div class="form_row">
                        <?php
							if($BadUser or ($BadMember and $BadStaffVol))
							{
								
							} else {
								if($BadStaffVol)
									{
										
										$url_jump_to_staff = '<a href="'.$lnk_add_edit_staff.'&create_new=1&id_user='.$User->GetUserID().$return_to.'">Create Staff / Volunteer Record</a>';
										
										echo '<div class="form_item_major_block">';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>'.$url_jump_to_staff.'</h2></div>';
											echo '</div>';
										echo '</div>';
										
									} elseif( $RestrictedStaff ) {
										
										$url_jump_to_staff = 'Restricted due to Staff Member history';
										
										echo '<div class="form_item_major_block">';
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>'.$url_jump_to_staff.'</h2></div>';
											echo '</div>';
										echo '</div>';
										
										
									} else { // else we are dealing wtih a secure login with full access
										$this_url = '<a href="'.$lnk_add_edit_staff.'&id_user='.$User->GetUserID().$return_to.'">';
										$this_url_end = '</a>';
											
										$str_class = ($StaffVol->HasLogin()) ? 'edit' : 'add';
										$this_add_edit = ($StaffVol->HasLogin()) ? 'Edit' : 'Add';
										
										$this_edit_login =  '<a class="'.$str_class.'" href="'.$lnk_add_edit_staff_login.'&id_user='.$User->GetUserID().$return_to.'">'.$this_add_edit.' Login</a>';
										
										
										if( $StaffVol->HasLogin() and $StaffVol->GetUserTypeName() == 'Field Worker' )
										{
											//will be good at add 'add/edit' text control once regions functionality has been added.
											
											$this_url_regions = $lnk_view_staff_regions_secure.'&id_user='.$User->GetUserID().$return_to;
											
											$this_view_regions = '<div class="form_item spaced"><span class="label">Regions: </span><a href="'.$this_url_regions.'">View Regions</a></div>';
										} else {
											$this_view_regions = '';
										}
										
										$this_reminder_dates = '<div class="form_item spaced"><span class="label">Reminder Dates: </span><a href="'.$lnk_view_staff_reminder_dates.'&id_user='.$User->GetUserID().$return_to.'">View Reminder Dates</a></div>';
										if( $StaffVol->GetUserTypeName() == $StateUser )
										{
											$this_state_activity_dates = '<div class="form_item spaced"><span class="label">State Activity Dates: </span><a href="'.$lnk_view_state_user_states_secure.'&id_user='.$User->GetUserID().$return_to.'">View State Activity Dates</a></div>';
										}
										else 
										{
											$this_state_activity_dates = '';
										}
										
										echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										
										
										echo '<div class="form_heading"><h2> Name: '.$this_url.($StaffVol->GetUserTypeName() == '' ? 'No Login' : $StaffVol->GetUserTypeName()).': '.$StaffVol->GetFirstName().' '.$StaffVol->GetLastname().$this_url_end.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Birth Date:</span> '.funAusDateFormat($StaffVol->GetBirthDate()).'</div>';
										
										if($StaffVol->GetCurrentActivity() == NULL)
										{
											$this_start = 'not set';
											$this_end = 'not set';
										} else {
											$this_start = funAusDateFormat($StaffVol->GetCurrentActivity()->GetStartDate());
											$this_end = funAusDateFormat($StaffVol->GetCurrentActivity()->GetEndDate());
										}
										
										echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.$this_start.'</div>';
										echo '<div class="form_item spaced"><span class="label">End Date:</span> '.$this_end.'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_item spaced"><span class="label">Work Mobile:</span> '.$StaffVol->GetWorkMobile().'</div>';
										echo '<div class="form_item spaced"><span class="label">Login:</span> '.$this_edit_login.'</div>';
										echo $this_view_regions;
										echo $this_reminder_dates;
										echo $this_state_activity_dates;
										echo '</div>';
										echo '</div>';
									
									}
							}
							
                        ?>
                     </div> <!-- End Form Row -->
					<div class="form_row">
                        <?php
							if($BadUser or ($BadMember and $BadStaffVol))
							{
								
							} else {
								
								
								if( count($AllActivityDates) == 0 )
								{
									echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>'.$UserName.' does not have any Activity Dates at present!</h2></div>';
										echo '<div class="form_row">'.$url_add_member_activity_date.'</div>';
										echo '</div>';
									echo '</div>';
								} else {
									
									echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>'.$UserName.' has '.count($AllActivityDates).' activity records!</h2></div>';
										echo '<div class="form_row">'.$url_add_staff_activity_date.'</div>';
										echo '<div class="form_row">'.$url_add_member_activity_date.'</div>';
										echo '</div>';
									echo '</div>';
									
									foreach ($AllActivityDates as $ActivityDate) 
									{
										
										if( $ActivityDate->GetUserTypeString() == $StaffVolunteer )
										{
											$RoleDetails = '<div class="form_item spaced"><span class="label">Role:</span> '.$ActivityDate->GetStaffRole()->GetRoleName().'</div>';
											
											$StaffRole = $ActivityDate->GetStaffRole();
											
											//Membership\StaffRole::LoadStaffRole($StaffRoleID);
											
											if( $StaffRole->GetRoleName() == $UserTypeCatStaff )
											{
												if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin )
												{
													$this_url = '<a href="'.$lnk_add_edit_staff_volunteer_dates.'&id_user='.$ActivityDate->GetUserID().'&id_user_activity='.$ActivityDate->GetUserActivityID().$return_to.'">';
													$this_url_end = '</a>';
												} else {
													$this_url = '';
													$this_url_end = '';
												}
											} 
											else
											{
												$this_url = '<a href="'.$lnk_add_edit_staff_volunteer_dates.'&id_user='.$ActivityDate->GetUserID().'&id_user_activity='.$ActivityDate->GetUserActivityID().$return_to.'">';
												$this_url_end = '</a>';
											}
										} elseif(  $ActivityDate->GetUserTypeString() == $MemberString) {
											
											$RoleDetails = '';
											
											$this_url = '<a href="'.$lnk_add_edit_member_dates.'&id_user='.$ActivityDate->GetUserID().'&id_user_activity='.$ActivityDate->GetUserActivityID().$return_to.'">';
											$this_url_end = '</a>';
										}
										
										
										echo '<div class="form_item_major_block">';
											echo '<div class="form_row">';
											echo '<div class="form_heading"><h2>'.$this_url.'Edit Activity Record'.$this_url_end.'</h2></div>';
											echo $RoleDetails;
											echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($ActivityDate->GetStartDate()).'</div>';
											echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($ActivityDate->GetEndDate()).'</div>';
											if($_SESSION['User']->GetUserTypeName() == $Admin or $_SESSION['User']->GetUserTypeName() == $StaffAdmin)
											{
												cntDeleteActivityDate($ActivityDate->GetUserActivityID(),1,$UserID);
											}
											echo '</div>';
										echo '</div>';
										
									}
								}
							}
                        ?>
					</div> <!-- End Form Row -->
                    <div class="form_row">
                    	<?php 
						if($BadUser or ($BadMember and $BadStaffVol))
						{ echo '<div class="criticial_error_message">Bad User ID submitted!</div>'; }
						else 
						{
							$LastGroupAttHeading = ($UserLastAttendance == NULL) ? ' has not attended a group yet!' : ' last attended: '.$UserLastGroup->GetGroupName().' on '.funAusDateFormat($UserLastAttendance->GetDate());
							$UserName = $BadMember ? $StaffVol->GetUserTypeName() : $Member->GetFirstName().' '.$Member->GetLastName();
							
							
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$UserName.$LastGroupAttHeading.'</h2></div>';
							echo '</div>';
						
						}
						?>
                    </div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->