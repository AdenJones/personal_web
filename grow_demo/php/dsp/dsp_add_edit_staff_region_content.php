			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $User->GetFirstName().' '.$User->GetLastname(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_staff_region" />
                    <input type="hidden" name="id_user" value="<?php echo $UserID;?>" />   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($StaffRegionID != ''){ echo '<input type="hidden" name="id_staff_region" value="'.$StaffRegionID.'" />'; } ?>
                    
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Group Details</h2></div>
                        <?php cntRegionDropDown('Region','RegionID','id_region','fld_branch_abbreviation','fld_region_name',$arr_regions,'Region',$RegionID);?>
                        
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Group Dates</h2></div>
						<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date',$bdYRange) ?>
                        <?php cntDate('End Date:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->