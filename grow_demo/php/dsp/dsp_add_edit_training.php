		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_training)
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_training_content.php';
					}
					else
					{
						echo '<div class="criticial_error_message">Bad Training Team ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->