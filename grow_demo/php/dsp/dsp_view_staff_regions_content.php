			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_staff; ?> <?php echo $url_add_staff_region; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        
						if( count($UserRegions) == 0 )
						{
							echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$StaffName.' does not have any regions at present!</h2></div>';
								echo '</div>';
							echo '</div>';
						} else {
							
							foreach ($UserRegions as $UserRegion) 
							{
								
								$Region = $UserRegion->GetRegion();
								
								$RegionName = $Region->GetBranch()->GetBranchName().': '.$Region->GetRegionName();
								
								if($secure)
								{
									$this_url = '<a href="'.$lnk_add_edit_staff_region.'&id_user='.$UserRegion->GetUserID().'&id_staff_region='.$UserRegion->GetStaffRegionID().'">';
									$this_url_end = '</a>';
									
								}
								else
								{
									$this_url = '';
									$this_url_end = '';
								}
								
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$this_url.$RegionName.$this_url_end.'</h2></div>';
									echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($UserRegion->GetStartDate()).'</div>';
									echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($UserRegion->GetEndDate()).'</div>';
									echo '</div>';
								echo '</div>';
								
							}
						}
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->