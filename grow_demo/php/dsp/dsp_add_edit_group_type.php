		<!--Main body content -->
        <div id="body">
            
            
        
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if(!$bad_group_type)
					{ include $BaseIncludeURL.'/dsp/dsp_add_edit_group_type_content.php';}
					else
					{
						echo '<div class="criticial_error_message">Bad Group Type ID submitted!</div>';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->