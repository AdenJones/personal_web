			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $staff->GetFirstName().' '.$staff->GetLastname(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_staff_volunteer_dates" />
                    <input type="hidden" name="id_user" value="<?php echo $UserID; ?>" />      
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($ActivityID != ''){ echo '<input type="hidden" name="id_user_activity" value="'.$ActivityID.'" />'; } ?>
                    
							<div class="form_row">
								<div class="form_heading"><h2>Employment Details</h2></div>
                                <?php cntDropDown('Job Classification:','JobClassification','id_staff_role','fld_role_name',$arr_roles,'Job Classification',$JobClassification);?>
								<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date'); ?>
								<?php cntDate('End Date:','EndDate',$EndDate,'End Date'); ?>
							</div>
                    
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->