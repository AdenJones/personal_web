		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
           		
                
                <?php 
					if(!$bad_user)
					{ include $BaseIncludeURL.'/dsp/dsp_edit_user_type_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad User ID submitted!</div>';
					}
				
				?>
            </div><!-- End Content -->
                
        </div> <!--End Body -->