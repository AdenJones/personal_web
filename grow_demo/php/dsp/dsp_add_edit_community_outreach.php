		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_com_out)
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_community_outreach_content.php';
					}
					else
					{
						echo '<div class="criticial_error_message">Bad Community Outreach ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->