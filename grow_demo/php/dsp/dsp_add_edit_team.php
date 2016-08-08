		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_team)
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_team_content.php';
					}
					else
					{
						echo '<div class="criticial_error_message">Bad Team ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->