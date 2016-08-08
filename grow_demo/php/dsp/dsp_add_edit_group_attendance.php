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
						echo '<div class="criticial_error_message">Bad Date submitted!</div>'; 
						
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_group_attendance_content.php';
						
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->