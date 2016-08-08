		
        <!--Main body content -->
        <div id="body">
            
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_labels)
					{ 
						echo '<div class="criticial_error_message">Bad Labels ID submitted!</div>';
					}
					elseif($bad_user)
					{
						echo '<div class="criticial_error_message">You do not have access to these labels!</div>';
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_view_labels_content.php';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->
        