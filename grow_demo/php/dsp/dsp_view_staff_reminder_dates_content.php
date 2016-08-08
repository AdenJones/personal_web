			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_staff; ?></h1></div>
           		
                <div class="form_row">
               		<div class="form_item_major_block">
						<div class="form_row">
						<?php if( count($PoliceCheckDates) == 0)
                            {
                                echo '<div class="form_heading"><h2>No Police Check Records: '.$url_add_police_check_date.'</h2></div>';
                            } else {
                                echo '<div class="form_heading"><h2>'.count($PoliceCheckDates).' Police Check Records: '.$url_add_police_check_date.'</h2></div>';
									echo '<div class="form_row">';
										foreach($PoliceCheckDates as $thisDate)
										{
											
											$this_url = $lnk_add_edit_police_check_reminder_dates.'&id_user='.$UserID.'&id_pcrmid='.$thisDate->GetReminderID();
											
											echo '<div class="form_item spaced"><a href="'.$this_url.'">'.funAusDateFormat($thisDate->GetDate()).'</a></div>';
										}
									echo '</div>';
                            }
                        ?>
                        </div>
                    </div> <!-- End Major Block -->
				</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->