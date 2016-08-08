		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_group)
					{
						echo '<div class="criticial_error_message">Bad Team ID submitted!</div>'; 
					}
					elseif( $bad_branch_region)
					{
						echo '<div class="criticial_error_message">Bad Branch Region ID submitted!</div>'; 
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_branch_region_content.php';
						
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->