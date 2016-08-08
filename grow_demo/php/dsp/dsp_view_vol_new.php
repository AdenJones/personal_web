		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                        <?php
							if($this_staff == false)
								{
									echo '<div class="criticial_error_message">Bad Staff ID submitted!</div>';
									
								} elseif( !$this_staff->IsVolunteer() )
								{
									echo '<div class="criticial_error_message">Non volunteer ID submitted!</div>';
								} elseif( $_SESSION['User']->GetUserTypeName() == $StateUser and !$LoggedInStaff->has_branch_by_branch_id($this_staff->GetBranchID()) )
								{
									echo '<div class="criticial_error_message">You do not have access to the given volunteer!</div>';
								} else {
									
									
									
									if($secure)
									{
										$this_url = '<a href="'.$lnk_add_edit_staff.'&id_user='.$this_staff->GetUserID().'&return_to=view_vol_new'.'">';
										$this_url_end = '</a>';
										
										$str_class = ($this_staff->HasLogin()) ? 'edit' : 'add';
										$this_add_edit = ($this_staff->HasLogin()) ? 'Edit' : 'Add';
										
										$this_edit_login =  '<a class="'.$str_class.'" href="'.$lnk_add_edit_staff_login.'&id_user='.$this_staff->GetUserID().'&return_to=view_vol_new'.'">'.$this_add_edit.' Login</a>';
										
										$this_view_dates = '<div class="form_item spaced"><a href="'.$lnk_view_staff_volunteer_dates_secure.'&id_user='.$this_staff->GetUserID().'&return_to=view_vol_new'.'">View All Activity Dates</a></div>';
										
										if( $this_staff->HasLogin() and $this_staff->GetUserTypeName() == 'Field Worker' )
										{
											//will be good at add 'add/edit' text control once regions functionality has been added.
											
											$this_url_regions = $lnk_view_staff_regions_secure.'&id_user='.$this_staff->GetUserID().'&return_to=view_vol_new';
											
											$this_view_regions = '<div class="form_item spaced"><span class="label">Regions: </span><a href="'.$this_url_regions.'">View Regions</a></div>';
										} else {
											$this_view_regions = '';
										}
										
										$this_reminder_dates = '<div class="form_item spaced"><span class="label">Reminder Dates: </span><a href="'.$lnk_view_staff_reminder_dates.'&id_user='.$this_staff->GetUserID().'&return_to=view_vol_new'.'">View Reminder Dates</a></div>';
										if( $this_staff->GetUserTypeName() == $StateUser )
										{
											$this_state_activity_dates = '<div class="form_item spaced"><span class="label">State Activity Dates: </span><a href="'.$lnk_view_state_user_states_secure.'&id_user='.$this_staff->GetUserID().'&return_to=view_vol_new'.'">View State Activity Dates</a></div>';
										}
										else 
										{
											$this_state_activity_dates = '';
										}
										
									}
									else
									{
										$this_url = '';
										$this_url_end = '';
										$this_edit_login = '';
										$this_reminder_dates = '';
									}
									
									echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									
									
									echo '<div class="form_heading"><h2> Name: '.$this_url.($this_staff->GetUserTypeName() == '' ? 'No Login' : $this_staff->GetUserTypeName()).': '.$this_staff->GetFirstName().' '.$this_staff->GetLastname().$this_url_end.'</h2></div>';
									echo '</div>';
									echo '<div class="form_row">';
									echo '<div class="form_item spaced"><span class="label">Birth Date:</span> '.funAusDateFormat($this_staff->GetBirthDate()).'</div>';
									
									if($this_staff->GetCurrentActivity() == NULL)
									{
										$this_start = 'not set';
										$this_end = 'not set';
									} else {
										$this_start = funAusDateFormat($this_staff->GetCurrentActivity()->GetStartDate());
										$this_end = funAusDateFormat($this_staff->GetCurrentActivity()->GetEndDate());
									}
									
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.$this_start.'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.$this_end.'</div>';
									echo $this_view_dates;
									echo '</div>';
									echo '<div class="form_row">';
									echo '<div class="form_item spaced"><span class="label">Work Mobile:</span> '.$this_staff->GetWorkMobile().'</div>';
									echo '<div class="form_item spaced"><span class="label">Login:</span> '.$this_edit_login.'</div>';
									echo $this_view_regions;
									echo $this_reminder_dates;
									echo $this_state_activity_dates;
									echo '</div>';
									echo '</div>';
								}
							
                        ?>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->