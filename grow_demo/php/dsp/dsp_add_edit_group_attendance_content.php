			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_group; ?> : <?php echo funAusDateFormat($Date);?> : <?php echo $url_no_meeting_reason; ?></h1></div>
           			
                	<div class="form_row">
                    	<div class="form_heading"><h2>Other Attendances (<?php echo count($OtherAttendances) ?>) <?php echo $lnkAddStaff; ?></h2></div>
                        
                        <?php
							foreach($AutoInserters as $User)
							{
								
								//var_dump($User);
								
								cntAddEditAutoInserter($User);
							}
						?>
                        
                        <?php
							if($Add == 'staff')
							{
								cntAddEditStaff($str_staff,$int_staff_hidden_input);
							}
						?>
						
						<?php 
							foreach( $OtherAttendances as $Attendance )
							{
								$thisStaff = \Membership\Staff::LoadStaff($Attendance->GetUserID());
								//could add some instanceof detection here to be safe.
								$Name = $thisStaff->GetFirstName().' '.$thisStaff->GetLastName();
								
								
								
								echo '<div class="form_item_major_block" id="outer_container_'.$Attendance->GetAttendanceID().'" ';
								if($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR )
								{
									echo 'onmouseover="jsFollowTheMouse(event,'."'".'message_'.$Attendance->GetAttendanceID()."'".')" onmouseout="jsHide('."'".'message_'.$Attendance->GetAttendanceID()."'".')"';
									
								}
								echo '>';
									echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Name: '.$Name.'</h2></div>';
										echo '<div class="form_item spaced"><span class="label"Gender: </span> '.$thisStaff->GetGenderName().'</div>';
										if($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR )
										{
											
											$UserName = \Membership\User::GetFunctionalUserName($Attendance->GetEnteredBy());
											
											$Message = 'Attendance entered by: '.$UserName.'!';
											
											echo cntHoverMessage('message_'.$Attendance->GetAttendanceID(),$Message);
										}
										cntDeleteAtt($Attendance->GetAttendanceID(),1,$GroupID,$Date);
									echo '</div>';
									
								echo '</div>';
							}
						?>                     
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Member Attendances (<?php echo count($MemberAttendances) ?>) <?php echo $lnkAddMember; ?> <?php echo $lnkCreateMember ?></h2></div>
						
                        <?php
							foreach($MemberAutoInserters as $User)
							{
								cntAddEditAutoInserterMember($User);
							}
						?>
                        
                        <?php
							if($Add == 'member')
							{
								cntAddEditMember($str_member,$int_member_hidden_input,$GroupID,$Date);
							}
						?>
						
						<?php 
							foreach( $MemberAttendances as $Attendance )
							{
								$thisMember = Membership\Member::LoadMember($Attendance->GetUserID());
								//could add some instanceof detection here to be safe.
								$Name = $thisMember->GetFirstName().' '.$thisMember->GetLastName();
								
								echo '<div class="form_item_major_block" id="outer_container_'.$Attendance->GetAttendanceID().'" ';
								if($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR )
								{
									echo 'onmouseover="jsFollowTheMouse(event,'."'".'message_'.$Attendance->GetAttendanceID()."'".')" onmouseout="jsHide('."'".'message_'.$Attendance->GetAttendanceID()."'".')"';
									
								}
								echo '>';
									echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Name: '.$Name.'</h2></div>';
										echo '<div class="form_item spaced"><span class="label"Gender: </span> '.$thisMember->GetGenderName().'</div>';
										if($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR )
										{
											
											$UserName = \Membership\User::GetFunctionalUserName($Attendance->GetEnteredBy());
											
											$Message = 'Attendance entered by: '.$UserName.'!';
											
											echo cntHoverMessage('message_'.$Attendance->GetAttendanceID(),$Message);
										}
										cntDeleteAtt($Attendance->GetAttendanceID(),1,$GroupID,$Date);
									echo '</div>';
									
								echo '</div>';
							}
						?>
                    </div>
                  <?php
				  	if($HasExternalAttendance)
					{
						if( $non_group_type == '' )
						{
							$Heading = 'Total Community Observers: ';
							$Title = 'Community Observers';
						} else {
							$Heading = 'Total External Attendees: ';
							$Title = 'Attendees';
						}
						
						echo '<div class="form_row">';
							echo '<div class="form_heading"><h2>'.$Heading.'</h2></div>';
							cntAddEditAttendees($Title,'int_attendees',$int_attendees,$Title);
						echo '</div>';
					}
				  ?>
                  
                   <div class="form_row">
                    	<div class="form_heading"><h2>Total Attendances: <?php echo $TotalAttendances; ?></h2></div>
                   </div>
                    
			</div><!--End Generic Form -->