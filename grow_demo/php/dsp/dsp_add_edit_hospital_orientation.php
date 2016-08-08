		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_hos_or)
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_hospital_orientation_content.php';
					}
					else
					{
						echo '<div class="criticial_error_message">Bad Hospital Orientation ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->