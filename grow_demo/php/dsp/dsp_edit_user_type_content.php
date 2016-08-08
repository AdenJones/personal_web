			            
            <div class="generic_form">
           		<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?></h1></div>
                <form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="edit_user_type" />
                    
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    <input type="hidden" name="id_user" value="<?php echo $UserID ?>" />
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>User Details</h2></div>
						<div class="form_item spaced"><span class="label">Screen Name:</span> <?php echo $User->GetScreenName();?></div>
                        <div class="form_item spaced"><span class="label">User Name:</span> <?php echo $User->GetUserName();?></div>
                        <?php cntDropDown('User Type:','id_user_type','id_user_type','fld_user_type',$UserTypes,'User Type',$UserTypeID);?> 
                    </div>
                    <div class="form_submit">
                    	<input class="button" type="submit" value="Update User Type" />
                    </div>
                </form>
			</div><!--End Generic Form -->