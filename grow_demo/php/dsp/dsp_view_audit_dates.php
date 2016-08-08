		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$BadVenue)
					{ include $BaseIncludeURL.'/dsp/dsp_view_audit_dates_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Venue ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->