		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
           		
                
                <div class="generic_form">
           		<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?> - <?php echo $_SESSION['User']->GetScreenName();?></h1></div>                   
                	
                     <div class="form_row">
                    	<div class="form_heading"><h2>My Profile - <a href="<?php echo $lnk_edit_my_profile; ?>">Edit</a></h2></div>
                        	<div class="form_item spaced"><span class="label">Screen Name:</span> <?php echo $_SESSION['User']->GetScreenName();?></div>
                            <div class="form_item spaced"><span class="label">Email Address:</span> <?php echo $_SESSION['User']->GetEmailAddress();?></div>
                            
                    </div>
                    <div class="form_row">
                    	<div class="form_heading"><h2>Username and Password - <a href="<?php echo $lnk_edit_my_login_details; ?>">Edit</a></h2></div>
                        <div class="form_item spaced"><span class="label">User Name:</span> <?php echo $_SESSION['User']->GetUserName();?></div>
                    
                    </div>
                    
				</div>
            </div><!-- End Content -->
                
        </div> <!--End Body -->