		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_group)
					{
						echo '<div class="criticial_error_message">Bad Group ID submitted!</div>'; 
					}
					elseif( $bad_group_region)
					{
						echo '<div class="criticial_error_message">Bad Group Region ID submitted!</div>'; 
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_group_region_content.php';
						
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->