			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $staff->GetFirstName().' '.$staff->GetLastname(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_police_check_reminder_dates" />
                    <input type="hidden" name="id_user" value="<?php echo $UserID; ?>" />      
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    <?php if($PCRMID != ''){ echo '<input type="hidden" name="id_pcrmid" value="'.$PCRMID.'" />'; } ?>
                    
							<div class="form_row">
								<div class="form_heading"><h2>Staff Police Check Date</h2></div>
								<?php cntDate('Date :','thisDate',$thisDate,'Date'); ?>
							</div>
                    
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->