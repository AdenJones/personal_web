			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?><?php echo $url_jump_to_member; ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_staff" />
                                   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($UserID != ''){ echo '<input type="hidden" name="id_user" value="'.$UserID.'" />'; } ?>
                    <?php if($CreateNew == 1){ echo '<input type="hidden" name="create_new" value="'.$CreateNew.'" />'; } ?>
                    
                     
                	<div class="form_row">
                    	<div class="form_heading"><h2>Staff Name</h2></div>
						<?php cntText('First Name:','FirstName',$FirstName,'First Name'); ?>
                        <?php cntText('Last Name:','LastName',$LastName,'Last Name'); ?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Personal Details</h2></div>
						<?php cntDate('Date of Birth:','BirthDate',$BirthDate,'Date of Birth',$bdYRange) ?>
                        <?php cntDropDown('Gender:','Gender','id_gender','fld_gender',$arr_genders,'Gender',$Gender);?>
                    </div>
                    
                    <?php if( $UserID == '' or $CreateNew == 1)
						{
							echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>Employment Dates</h2></div>';
								cntDate('Start Date:','StartDate',$StartDate,'Start Date');
								cntDate('End Date:','EndDate',$EndDate,'End Date');
								cntDropDown('Job Classification:','JobClassification','id_staff_role','fld_role_name',$arr_roles,'Job Classification',$JobClassification);
							echo '</div>';
                    	}
                    ?>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Employment Details</h2></div>
                        <div class="form_row">
                        <?php cntDropDown('Branch:','Branch','id_branch','fld_branch_name',$arr_branches,'Branch',$Branch);?>
                        </div>
                        <div class="form_row">
                        <?php cntText('Work Phone:','WorkPhone',$WorkPhone,'Work Phone'); ?>
                        <?php cntText('Work Email:','WorkEmail',$WorkEmail,'Work Email','60'); ?>
                        </div>
                    </div>
                    <?php if($UserID == '')
						{
							echo '<div class="form_row">';
							echo '<div class="form_heading"><h2>Expiry Dates</h2></div>';
							cntDate('Police Check:','PoliceCheckDate',$PoliceCheckDate,'Police Check');
							echo '</div>';
						}
                    ?>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Contact Details</h2></div>
                        <?php cntText('Email:','Email',$Email,'Email','60'); ?>
                        <div class="form_row">
                        <?php cntText('Personal Mobile:','Mobile',$Mobile,'Mobile'); ?>
                        <?php cntText('Home Phone:','HomePhone',$HomePhone,'Home Phone'); ?>
                        </div>
                        <div class="form_row">
                        <?php cntText('Address:','Address',$Address,'Address','50'); ?>
                        <?php cntText('Suburb:','Suburb',$Suburb,'Suburb'); ?>
                        <?php cntDropDown('State:','State','id_state','fld_state_abbreviation',$arr_states,'State',$State);?>
                        <?php cntText('Postcode:','PostCode',$PostCode,'Postcode','8'); ?>
                        </div>
                    </div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Other Details</h2></div>
						 <?php cntLongText('Other Employment Details:','OtherEmploymentDetails',$OtherEmploymentDetails,'Other Employment Details') ?>
                    </div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Emergency Contact Details</h2></div>
                        <div class="form_row">
                       	<?php cntText('First Name:','EmConFName',$EmConFName,'Emergency Contact First Name'); ?>
                        <?php cntText('Last Name:','EmConLName',$EmConLName,'Emergency Contact Last Name'); ?>
                        </div>
                        <div class="form_row">
                        <?php cntText('Address:','EmConAddress',$EmConAddress,'Emergency Contact Address','50'); ?>
                        <?php cntText('Suburb:','EmConSuburb',$EmConSuburb,'Emergency Contact Suburb'); ?>
                        <?php cntDropDown('State:','EmConState','id_state','fld_state_abbreviation',$arr_states,'Emergency Contact State',$EmConState);?>
                        <?php cntText('Postcode:','EmConPostCode',$EmConPostCode,'Emergency Contact Postcode','8'); ?>
                        </div>
                        <div class="form_row">
                        <?php cntText('Mobile Phone:','EmConMobile',$EmConMobile,'Emergency Contact Mobile'); ?>
                        <?php cntText('Home Phone:','EmConHomePhone',$EmConHomePhone,'Emergency Contact Home Phone'); ?>
                        </div>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->