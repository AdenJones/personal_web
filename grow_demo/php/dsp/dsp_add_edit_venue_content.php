			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?></h1></div>
           		<form id="sign_up" action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_venue" />
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    <?php if($VenueID != ''){ echo '<input type="hidden" name="id_venue" value="'.$VenueID.'" />'; } ?>
                	<div class="form_row">
                    	<div class="form_heading"><h2>Venue Details</h2></div>
                        <div class="form_row">
                        <?php cntText('Name:','Name',$Name,'Name'); ?>
                        </div>
						<div class="form_row">
                        <?php cntText('Address:','Address',$Address,'Address','50'); ?>
                        <?php cntText('Suburb:','Suburb',$Suburb,'Suburb'); ?>
                        <?php cntDropDown('State:','State','id_state','fld_state_abbreviation',$arr_states,'State',$State);?>
                        <?php cntText('PostCode:','PostCode',$PostCode,'PostCode','8'); ?>
                        </div>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Other Details</h2></div>
                        <?php cntCheckBox('Contract','Contract',$Contract,'Contract')?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Venue Dates</h2></div>
						<?php cntDate('Start Date:','StartDate',$StartDate,'Start Date',$bdYRange) ?>
                        <?php cntDate('End Date:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Comments</h2></div>
						<?php cntLongText('','Comments',$Comments,'Comments'); ?>
                    </div>
                     <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                    
                </form>
			</div><!--End Generic Form -->