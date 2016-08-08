		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_group)
					{ include $BaseIncludeURL.'/dsp/dsp_view_all_group_notes_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Group ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->