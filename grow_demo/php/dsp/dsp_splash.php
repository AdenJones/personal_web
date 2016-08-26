		<!--Main body content -->
        <div id="body">
            
             <!-- Content -->
            <div id="content">
            	<img id="grow_log" src="/images/grow_logo.png" width="370" height="150" alt="Log In" />           	  <!-- Old form submit <form onsubmit="return fnValSignUp();"> -->
                <form id="log_in" action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="splash" />
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                	
                    <div class="extra_wide_form <?php echo funHighlightBlkErrors($arrErrors,array('Bad'))?>">
                    	<?php cntUserNameAndPassword($strUName,$strPWord); ?>
                        
                        	<a style="margin-top:5px;" href="#" onclick="document.getElementById('log_in').submit();">Log Me In!!</a>
                    		<!--<input class="button" type="submit" value="Log Me In!" /> -->
                    	
                        
                   </div><!-- End Form Container -->
                    
                    
                    
                    
                </form>
            </div><!-- End Content -->
        	
            
                
        </div> <!--End Body -->