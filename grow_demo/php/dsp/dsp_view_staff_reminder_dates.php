		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_staff)
					{ include $BaseIncludeURL.'/dsp/dsp_view_staff_reminder_dates_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Staff ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->