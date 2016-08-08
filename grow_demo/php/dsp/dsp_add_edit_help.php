		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_page)
					{ include $BaseIncludeURL.'/dsp/dsp_add_edit_help_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Page ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->