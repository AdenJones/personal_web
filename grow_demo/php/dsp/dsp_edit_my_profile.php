		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
            	
              <div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?> - <?php echo $_SESSION['User']->GetScreenName();?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="edit_my_profile" />
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                	<div class="form_row">
                    	<div class="form_heading"><h2>Heading</h2></div>
						<?php cntText('Screen Name:','ScreenName',$ScreenName,'Screen Name'); ?>
						<?php cntText('Email:','Email',$Email,'Email'); ?>
                        
                    </div>
                    
                    <div class="form_submit">
                    	<input class="button" type="submit" value="Update Profile" />
                    </div>
                </form>
			</div><!--End Generic Form -->
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->