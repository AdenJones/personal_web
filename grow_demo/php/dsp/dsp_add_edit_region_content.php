			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_region" />
                                   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($RegionID != ''){ echo '<input type="hidden" name="id_region" value="'.$RegionID.'" />'; } ?>
                    
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Region Details</h2></div>
						<?php cntText('Region Name:','RegionName',$RegionName,'Region Name'); ?>
                        <?php cntDropDown('Branch:','Branch','id_branch','fld_branch_name',$arr_branches,'Branch',$Branch);?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Region Dates</h2></div>
						<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date',$bdYRange) ?>
                        <?php cntDate('End Date:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->