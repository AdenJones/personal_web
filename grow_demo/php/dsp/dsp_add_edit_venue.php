		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_venue)
					{ include $BaseIncludeURL.'/dsp/dsp_add_edit_venue_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Venue ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->