		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_soc_ev)
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_social_event_content.php';
					}
					else
					{
						echo '<div class="criticial_error_message">Bad Social Event ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->