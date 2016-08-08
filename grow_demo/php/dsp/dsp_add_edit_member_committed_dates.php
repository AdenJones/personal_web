		<!--Main body content -->
        <div id="body">
            
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($bad_person)
					{ 
						echo '<div class="criticial_error_message">Bad Person ID submitted!</div>';
					}
					elseif($bad_committed)
					{
						echo '<div class="criticial_error_message">Bad Committed ID submitted!</div>';
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_member_committed_dates_content.php';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->