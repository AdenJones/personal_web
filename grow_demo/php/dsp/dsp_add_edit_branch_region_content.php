			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $Group->GetGroupName(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_branch_region" />
                    <input type="hidden" name="id_group" value="<?php echo $GroupID;?>" />   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($GroupRegionID != ''){ echo '<input type="hidden" name="id_group_region" value="'.$GroupRegionID.'" />'; } ?>
                    
                    
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Details:</h2></div>
                        <?php cntSelectBranchOrRegion(
									'BranchID','id_branch','fld_branch_name',$arr_branches,$BranchID,
									'RegionID','id_region','fld_branch_abbreviation','fld_region_name',$arr_regions,$RegionID
									);
						?>
                        
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Dates</h2></div>
						<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date',$bdYRange) ?>
                        <?php cntDate('End Date:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->