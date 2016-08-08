		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_person)
					{ include $BaseIncludeURL.'/dsp/dsp_add_edit_staff_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Person ID submitted!</div>';
						echo '<div class="criticial_error_message">Branch ID:'.$staff->GetBranchID().'; UserID: '.$UserStaff->GetUserID().'</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->