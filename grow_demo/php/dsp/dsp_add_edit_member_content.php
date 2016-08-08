			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?><?php if($UserID != ''){echo ' - '.$url_view_activity_dates;}?><?php echo $url_jump_to_staff; ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_member" />
                                   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    <?php if($ReturnTo != ''){echo '<input type="hidden" name="return_to" value="'.$ReturnTo.'" />'; }?>
                    <?php if($UserID != ''){ echo '<input type="hidden" name="id_user" value="'.$UserID.'" />'; } ?>
                    <?php 
					if($GroupID != '')
					{
						echo '<input type="hidden" name="id_group" value="'.$GroupID.'" />';
						echo '<input type="hidden" name="date" value="'.$Date.'" />';
						echo '<input type="hidden" name="add" value="'.$Add.'" />';
					}
					 ?>
                    <?php if($CreateNew == 1){ echo '<input type="hidden" name="create_new" value="'.$CreateNew.'" />'; } ?>
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Member Name</h2></div>
						<?php cntText('First Name:','FirstName',$FirstName,'First Name'); ?>
                        <?php cntText('Last Name:','LastName',$LastName,'Last Name'); ?>
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Personal Details</h2></div>
                        <?php cntDropDown('Gender:','Gender','id_gender','fld_gender',$arr_genders,'Gender',$Gender);?>
                    </div>
                    
                    <div class="form_row">
                    	<?php if( $UserID == '' or $CreateNew == 1 ){ echo '<div class="form_heading"><h2>Membership Dates</h2></div>'; } ?>
                        <?php if($UserID == '') 
						{
							cntDate('Committed Date:','CommittedDate',$CommittedDate,'Committed Date');
						}
						?>
                    	<?php if( $UserID == '' or $CreateNew == 1 )
						{
							cntDate('Start Date:','StartDate',$StartDate,'Start Date');
							cntDate('End Date:','EndDate',$EndDate,'End Date');
                    	}
                    ?>
                    </div>
                    
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->