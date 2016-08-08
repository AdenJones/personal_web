		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?></h1></div>
                   	</div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2><?php echo $JobsPendingHeading ?>! <a href="<?php echo $lnk_seeker_view_my_jobs; ?>">Go to Jobs Pending</a></h2> </div>
                        <div class="form_item_block">
                        	<h3><?php echo $JobsWithAcceptedBidCount ?> Jobs with accepted bids!</h3>
                        </div>
                        <div class="form_item_block">
                        	<h3><?php echo $JobsWithoutAcceptedBidsCount ?> Jobs still awaiting bids!</h3>
                        </div>
						
                   	</div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2><?php echo $USOCJHeading ?>! <a href="<?php echo $lnk_seeker_jobs_complete; ?>">Go to Jobs Pending Approval</a></h2> </div>
                        
						
                   	</div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2><?php echo $ArchivedHeading ?>! <a href="<?php echo $lnk_seeker_view_jobs_archive; ?>">Go to Archived Jobs</a></h2> </div>
                       						
                   	</div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2><?php echo $UnresolvedDisputedHeading ?>! <a href="<?php echo $lnk_seeker_view_jobs_disputed; ?>">Go to Disputed Jobs</a></h2> </div>
                       						
                   	</div>
                    
                 	<div class="form_row">
                    	<div class="form_heading"><h2><?php echo $ResolvedDisputedHeading ?>! <a href="<?php echo $lnk_seeker_view_jobs_disputed; ?>">Go to Disputed Jobs</a></h2> </div>
                       						
                   	</div>
                
                </div>
            </div><!-- End Content -->
                
        </div> <!--End Body -->