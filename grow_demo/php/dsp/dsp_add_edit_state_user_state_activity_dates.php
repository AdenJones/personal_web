		<!--Main body content -->
        <div id="body">
            
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_person)
					{ 
						echo '<div class="criticial_error_message">Bad Person ID submitted!</div>';
					}
					elseif($bad_activity)
					{
						echo '<div class="criticial_error_message">Bad Activity ID submitted!</div>';
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_state_user_state_activity_dates_content.php';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->