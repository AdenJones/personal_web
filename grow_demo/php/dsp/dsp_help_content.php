			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name.' '.$Page->getPageName(); ?> <?php echo $ReturnLink; ?> <?php echo $edit; ?></h1></div>
           			<div class="form_row">
                    	<?php 
						
						if( $secure )
						{
							if( $Page->getPageHelp() == '' )
							{
								echo '<div class="form_heading"><h2>Help page not yet created!</h2></div>';
							} else {
								echo $Page->getPageHelp();
							}
						} else {
							
							if( $Page->getPageHelp() == '' )
							{
								echo '<div class="form_heading"><h2>Help page not yet created!</h2></div>';
							} else {
								echo $Page->getPageHelp();
							}
						}
						
						?>
                    	
                        
                    </div>
                    
                    
			</div><!--End Generic Form -->