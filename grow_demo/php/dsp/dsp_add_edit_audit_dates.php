		<!--Main body content -->
        <div id="body">
            
            <!-- Content -->
            <div id="content">
            	
                <?php 
					if($BadVenue)
					{ 
						echo '<div class="criticial_error_message">Bad Venue ID submitted!</div>';
					}
					elseif($BadAuditDate)
					{
						echo '<div class="criticial_error_message">Bad Audit Date ID submitted!</div>';
					}
					else
					{
						include $BaseIncludeURL.'/dsp/dsp_add_edit_audit_dates_content.php';
					}
				
				?>
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->