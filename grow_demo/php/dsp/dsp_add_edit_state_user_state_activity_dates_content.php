			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $staff->GetFirstName().' '.$staff->GetLastname(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_state_user_state_activity_dates" />
                    <input type="hidden" name="id_user" value="<?php echo $user_id; ?>" />      
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($state_activity_date_id != ''){ echo '<input type="hidden" name="state_activity_date_id" value="'.$state_activity_date_id.'" />'; } ?>
                    
							<div class="form_row">
								<div class="form_heading"><h2>Employment Details</h2></div>
                                <?php cntDropDown('Branches:','branch','id_branch','fld_branch_abbreviation',$arr_branches,'Branch',$branch);?>
								<?php cntDate('Start Date:','start_date',$start_date,'Start Date'); ?>
								<?php cntDate('End Date:','end_date',$end_date,'End Date'); ?>
							</div>
                    
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->