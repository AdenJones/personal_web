			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $Group->GetGroupName(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_group_schedule" />
                    <input type="hidden" name="id_group" value="<?php echo $GroupID;?>" />   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($GroupScheduleID != ''){ echo '<input type="hidden" name="id_group_schedule" value="'.$GroupScheduleID.'" />'; } ?>
                    
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Schedule Details</h2></div>
                        <?php cntPeriodSelect('Recurrence:','mxd_recurrence',$mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div,'Recurrence'); ?>
						                        
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Schedule Dates</h2></div>
						<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date',$bdYRange) ?>
                        <?php cntDate('End Date:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                    </div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Schedule Times</h2></div>
						 <?php cntTime('Start Time:','StartTime',$StartTime,'Start Time') ?>
                        <?php cntTime('End Time:','EndTime',$EndTime,'End Time') ?>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->