		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_user)
					{
						echo '<div class="criticial_error_message">Bad User ID submitted!</div>'; 
					}
					elseif( $bad_staff_region)
					{
						echo '<div class="criticial_error_message">Bad Staff Region ID submitted!</div>'; 
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_staff_region_content.php';
						
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->