			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_group; ?> <?php echo $url_add_group_venue; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        foreach ($GroupsVenues as $GroupVenue) 
                        {
							$thisVenue = $GroupVenue->GetVenue();
							
                            if($secure)
                            {
                                $this_url = '<a href="'.$lnk_add_edit_group_venue.'&id_group='.$GroupVenue->GetGroupID().'&id_group_venue='.$GroupVenue->GetGroupVenueID().'">';
                                $this_url_end = '</a>';
								
                            }
                            else
                            {
                                $this_url = '';
                                $this_url_end = '';
                            }
                            
                            echo '<div class="form_item_major_block">';
                                echo '<div class="form_row">';
                                echo '<div class="form_heading"><h2>Venue: '.$this_url.$thisVenue->GetName().$this_url_end.'</h2></div>';
								echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($GroupVenue->GetStartDate()).'</div>';
								echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($GroupVenue->GetEndDate()).'</div>';
                                echo '</div>';
                            echo '</div>';
                            
                        }
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->