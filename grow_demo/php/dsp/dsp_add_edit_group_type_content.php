			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_group_type" />
                                   
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                    <?php if($GroupTypeID != ''){ echo '<input type="hidden" name="id_group_type" value="'.$GroupTypeID.'" />'; } ?>
                    
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Group Type Details</h2></div>
						<?php cntText('Group Type Name:','GroupTypeName',$GroupTypeName,'Group Type Name','35'); ?>
                    </div>
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->