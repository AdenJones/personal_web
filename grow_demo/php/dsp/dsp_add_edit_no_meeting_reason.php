		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_group)
					{
						echo '<div class="criticial_error_message">Bad Group ID submitted!</div>'; 
					}
					elseif( $bad_date)
					{
						echo '<div class="criticial_error_message">Bad Group Date submitted!</div>'; 
					}
					elseif( $bad_no_meeting_record)
					{
						echo '<div class="criticial_error_message">Date is detected as having a meeting yet no record could be found!</div>'; 
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_no_meeting_reason_content.php';
						
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->