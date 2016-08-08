			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $ReturnString; ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_audit_date" />     
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($AuditDateID != ''){ echo '<input type="hidden" name="id_audit_date" value="'.$AuditDateID.'" />'; } ?>
                    <?php if($VenueID != ''){ echo '<input type="hidden" name="id_venue" value="'.$VenueID.'" />'; } ?>
                    
							<div class="form_row">
								<div class="form_heading"><h2>Audit Date</h2></div>
								<?php cntDate('Audit Date:','DateAuditDate',$DateAuditDate,'Audit Date'); ?>
                                <?php cntCheckBox('Complete:','Complete',$Complete,'Complete'); ?>
							</div>
                    		<div class="form_row">
								<div class="form_heading"><h2>Notes</h2></div>
                                <?php cntLongText('Notes:','Notes',$Notes,'Notes'); ?>
							</div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->