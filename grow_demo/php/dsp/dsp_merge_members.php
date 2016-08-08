		<!--Main body content -->
        <div id="body">
         
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?><?php if($_SESSION['user']['member']['members_selected'] == true){echo ' - '.$Back; } ?></h1></div>
                   	</div>
                    <?php
						if($_SESSION['user']['member']['members_selected'] == false)
						{
							echo '<form action="'.$full_uri.'/index.php" method="post">';
							echo '<input type="hidden" name="page_id" value="merge_members" />';
							echo '<input type="hidden" name="form_submitted" value="1" />';
								echo '<div class="form_row">';
								echo 	'<div class="form_heading"><h2>Select Members to Merge!</h2></div>';
								echo	'<div class="form_item_block">';
										cntMemberSelectForMultiple('Select First Member','str_member_1','int_member_hidden_input_1',$str_member_1,$int_member_hidden_input_1,'First Member Selection','45');
										cntMemberSelectForMultiple('Select Second Member','str_member_2','int_member_hidden_input_2',$str_member_2,$int_member_hidden_input_2,'Second Member Selection','45');
								echo '</div>';
								echo '<div class="form_submit">';
								echo 	'<input class="button" type="submit" value="'.$page_name.'" />';
								echo '</div>';
								
							echo '</div>';
						echo '</form>'; 
						} else {
							
							if( $intSubmitted == 1 )
							{
								echo '<form action="'.$full_uri.'/index.php" method="post">';
								echo '<input type="hidden" name="page_id" value="merge_members" />';
								echo '<input type="hidden" name="form_submitted" value="2" />';
								echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>Select Member to keep!</h2></div>';
									echo '<div class="bordered_box">';
										echo '<input type="radio" checked name="member" value="'.$Member1->GetUserID().'">'.$Member1->GetFirstName().' '.$Member1->GetLastName();
										echo '<br/>Gender: '.$Member1->GetGenderName();
									echo '</div>';
									echo '<div class="bordered_box">';
										echo '<input type="radio" name="member" value="'.$Member2->GetUserID().'">'.$Member2->GetFirstName().' '.$Member2->GetLastName();
										echo '<br/>Gender: '.$Member2->GetGenderName();
									echo '</div>';
								echo '</div>';
								if( $two_staff )
								{
									echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Select Staff Record to keep!</h2></div>';
										echo '<div class="bordered_box">';
											echo '<input type="radio" checked name="staff" value="'.$Staff1->GetUserID().'">'.$Staff1->GetFirstName().' '.$Staff1->GetLastName();
											echo '<br/>Gender: '.$Staff1->GetGenderName();
										echo '</div>';
										echo '<div class="bordered_box">';
											echo '<input type="radio" name="staff" value="'.$Staff2->GetUserID().'">'.$Staff2->GetFirstName().' '.$Staff2->GetLastName();
											echo '<br/>Gender: '.$Staff2->GetGenderName();
										echo '</div>';
									echo '</div>';
								} elseif( $one_staff )
								{
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>Staff Record to be kept!</h2></div>';
										echo '<div class="bordered_box">';
											echo '<input type="radio" checked name="staff" value="'.$sole_staff->GetUserID().'">'.$sole_staff->GetFirstName().' '.$sole_staff->GetLastName();
											echo '<br/>Gender: '.$sole_staff->GetGenderName();
										echo '</div>';
									echo '</div>';
								} else {
									echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>No Staff Records!</h2></div>';
									echo '</div>';
								}
								
								echo '<div class="form_submit">';
									echo '<input class="button" type="submit" value="'.$page_name.'" />';
								echo '</div>';
								
								echo '</form>';
							} elseif( $intSubmitted == 2 )
							{
								if($blnIsGood)
								{
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>Member Records were successfully merged!</h2></div>';
									echo '<h3>'.$final_member->GetGenderName().': '.$final_member->GetFirstName().' '.$final_member->GetLastName().'</h3>';
									echo '</div>';
									if( $final_staff != false )
									{
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Final Staff Record!</h2></div>';
										echo '<h3>'.$final_staff->GetGenderName().': '.$final_staff->GetFirstName().' '.$final_staff->GetLastName().'</h3>';
										echo '</div>';
									}
									
									//reset variables
									$_SESSION['user']['member']['members_selected'] = false;
									
									
								} else {
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>The following errors were encountered!</h2></div>';
									if( array_key_exists('Member to keep',$arrErrors) )
									{
										echo '<h3>'.'Member to keep'.' '.$arrErrors['Member to keep'].'</h3>';
									}
									if( array_key_exists('Member to destroy',$arrErrors) )
									{
										echo '<h3>'.'Member to destroy'.' '.$arrErrors['Member to destroy'].'</h3>';
									}
									if( array_key_exists('Staff to keep',$arrErrors) )
									{
										echo '<h3>'.'Staff to keep'.' '.$arrErrors['Staff to keep'].'</h3>';
									}
									echo '</div>';
								}
							}
						}
					?>
               
             	</div><!-- End Generic Form -->
            </div><!-- End Content -->
                
        </div> <!--End Body -->