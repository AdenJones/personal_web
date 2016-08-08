		
        <!--Main body content -->
        <div id="body">
            
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_report)
					{ 
						echo '<div class="criticial_error_message">Bad Report ID submitted!</div>';
					}
					elseif($bad_user)
					{
						echo '<div class="criticial_error_message">You do not have access to this report!</div>';
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_view_report_content.php';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->
        