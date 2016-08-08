		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_region)
					{ include $BaseIncludeURL.'/dsp/dsp_add_edit_region_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Region ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->