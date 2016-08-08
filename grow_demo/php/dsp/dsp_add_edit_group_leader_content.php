			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $Group->GetGroupName(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_group_leader" />
                    <input type="hidden" name="id_group" value="<?php echo $GroupID;?>" />   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($GroupLeaderID != ''){ echo '<input type="hidden" name="id_group_leader" value="'.$GroupLeaderID.'" />'; } ?>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Group Leader Member</h2></div>
							<?php cntVolunteerSelectImproved('Select Volunteer:','str_volunteer','int_leader_hidden_input',$str_volunteer,$int_leader_hidden_input,'Volunteer Selection','45'); ?>
                                    <script type="text/javascript">
                                        document.getElementById('str_volunteer').focus();
                                    </script>
                    </div>
                	<div class="form_row">
                    	<div class="form_heading"><h2>Group Role Details</h2></div>
                        <?php cntDropDown('Role','RoleID','id_group_role','fld_group_role',$arr_roles,'Role',$RoleID) ?>
						<?php cntCheckBox('Acting','Acting',$Acting,'Acting')?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Group Role Dates</h2></div>
						<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date',$bdYRange) ?>
                        <?php cntDate('End Date:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->