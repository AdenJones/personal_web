
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                    	<table style="position:relative;background-color:white">
                           		<?php
																		
									foreach ($LabelsFields as $Field) 
                                    {
										echo '<div id="Labels'.$Field->GetIDUserLabelsField().'" class="form_item_major_block">';
										echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Name: '.$Field->GetName().'</h2></div>';
												
												echo '<div class="form_item spaced">'.$Field->GetAddress().'. '.$Field->GetSuburb().', '.$Field->GetState().' '.$Field->GetPostCode().'</div>';
												
										echo '</div>';
										echo '</div>';
										
									}
							?>
                        </table>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->