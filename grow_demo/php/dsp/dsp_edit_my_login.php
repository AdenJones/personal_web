		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
            	
              <div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?> - <?php echo $_SESSION['User']->GetScreenName();?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="edit_my_login" />
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>User Name</h2></div>
						<?php cntUserName('User Name:','UserName',$UserName,'User Name'); ?>
                    </div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Password</h2></div>
                        <?php cntPassword('Old Password:','OldPassword',$OldPassword,'Old Password'); ?>
						<?php cntPassWordsNew($Password,$CheckPassword,'BlockID'); ?>
                        
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="Update LogIn" />
                    </div>
                </form>
			</div><!--End Generic Form -->
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->