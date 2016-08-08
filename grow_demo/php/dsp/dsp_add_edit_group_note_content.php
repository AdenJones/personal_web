			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?> - <?php echo $Group->GetGroupName(); ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_group_note" />
                                   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    <input type="hidden" name="id_group" value="<?php echo $GroupID?>" />
                    
                    <?php if($GroupNoteID != ''){ echo '<input type="hidden" name="id_group_note" value="'.$GroupNoteID.'" />'; } ?>
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Note Details</h2></div>
						<?php cntDate('Note Date:','Date',$Date,'Note Date',$bdYRange) ?>
                         
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Security</h2></div>
                        <?php cntCheckBox('Incident Report','IncidentReport',$IncidentReport,'Incident Report'); ?>
                        <?php cntDropDown('Importance:','Importance','id_importance','fld_importance',$arrImportances,'Importance',$Importance);?>
                        <?php cntDropDown('Viewable By:','SecurityLevel','fld_security_level','fld_user_type',$arrSecurityLevels,'Security Level',$SecurityLevel);?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Note</h2></div>
						<?php cntLongText('Note:','Note',$Note,'Note') ?>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->