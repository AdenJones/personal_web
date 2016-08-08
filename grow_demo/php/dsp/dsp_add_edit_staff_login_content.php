            <div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?> - <?php echo $staff->GetFirstName().' '.$staff->GetLastname() ?></h1></div>
           		<form id="sign_up" action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_staff_login" />
                    <input type="hidden" name="id_user" value="<?php echo $UserID; ?>" />
                    
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Log In Details</h2></div>
                        <?php cntUserName('User Name:','UserName',$UserName,'User Name'); ?>
                        <?php cntPassWordsNew($Password,$CheckPassword,'BlockID'); ?>
						
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Personal Details</h2></div>
						<?php cntUserName('Screen Name:','ScreenName',$ScreenName,'Screen Name'); ?>
                        <?php cntDropDown('User Type:','UserType','id_user_type','fld_user_type',$arr_staff_user_types,'User Type',$UserType);?>
                    </div>
                    
                    <div class="form_row">
                    <a style="margin-top:5px;" href="#" onclick="document.getElementById('sign_up').submit();"><?php echo $page_name ?></a>
                    </div>
                    
                </form>
			</div><!--End Generic Form -->
