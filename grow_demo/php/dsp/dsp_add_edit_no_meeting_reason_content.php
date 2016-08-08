			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_group; ?> : <?php echo $lnk_back_to_attendance; ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_no_meeting_reason" />
                    <input type="hidden" name="id_group" value="<?php echo $GroupID;?>" />
                    <input type="hidden" name="date" value="<?php echo $Date;?>" /> 
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Reason</h2></div>
                         <?php cntDropDown('Reason:','Reason','id_reason','fld_reason',$arr_reasons,'Reason',$Reason);?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Notes</h2></div>
                          <?php cntLongText('Notes:','Notes',$Notes,'Notes') ?>
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->