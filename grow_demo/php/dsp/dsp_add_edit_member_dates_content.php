			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $member->GetFirstName().' '.$member->GetLastname(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_member_dates" />
                    <input type="hidden" name="id_user" value="<?php echo $UserID; ?>" />      
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    <input type="hidden" name="return_to" value="<?php echo $ReturnTo; ?>" />
                    
                    <?php if($ActivityID != ''){ echo '<input type="hidden" name="id_user_activity" value="'.$ActivityID.'" />'; } ?>
                    
							<div class="form_row">
								<div class="form_heading"><h2>Employment Dates</h2></div>
								<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date'); ?>
								<?php cntDate('End Date:','EndDate',$EndDate,'End Date'); ?>
							</div>
                    
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->