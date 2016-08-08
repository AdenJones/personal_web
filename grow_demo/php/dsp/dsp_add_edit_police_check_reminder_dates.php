		<!--Main body content -->
        <div id="body">
            
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_person)
					{ 
						echo '<div class="criticial_error_message">Bad Person ID submitted!</div>';
						
					}
					elseif($bad_pcrmid)
					{
						echo '<div class="criticial_error_message">Bad Police Committed Reminder ID submitted!</div>';
						
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_police_check_reminder_dates_content.php';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->