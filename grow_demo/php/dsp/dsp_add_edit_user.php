		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_user)
					{ include $BaseIncludeURL.'/dsp/dsp_add_edit_user_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Person ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->