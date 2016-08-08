		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_help_page)
					{
						echo '<div class="criticial_error_message">Bad Page ID submitted!</div>'; 
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_help_content.php';
						
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->