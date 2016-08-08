			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name; ?> : <?php echo $url_return_to_group; ?> <?php echo $url_add_group_region; ?></h1></div>
           		
                <div class="form_row">
						<?php
                        //the use of two queries here is due to mysql not supporting total outer joins :(
                        foreach ($GroupsRegions as $GroupRegion) 
                        {
							
							
							$IsBranchOrRegion = $GroupRegion->IsBranchOrRegion();
							
							if($IsBranchOrRegion == GROUP_REGION_REGION )
							{
								//$thisRegion->GetBranch()->GetBranchName()
								$thisRegion = $GroupRegion->GetRegion();
								$thisBranch = $thisRegion->GetBranch();
								
								$thisHeading = $thisBranch->GetBranchName().': '.$thisRegion->GetRegionName().' ';
							}
							else
							{
								$thisBranch = $GroupRegion->GetBranch();
								
								$thisHeading = 'Branch: '.$thisBranch->GetBranchName();
							}
							
                            if($secure)
                            {
                                $this_url = '<a href="'.$lnk_add_edit_branch_region.'&id_group='.$GroupRegion->GetGroupID().'&id_group_region='.$GroupRegion->GetGroupRegionID().'&return_address='.$returnAddress.'">';
                                $this_url_end = '</a>';
								
                            }
                            else
                            {
                                $this_url = '';
                                $this_url_end = '';
                            }
                            
                            echo '<div class="form_item_major_block">';
                                echo '<div class="form_row">';
								cntDeleteGroupRegion($GroupRegion->GetGroupID(),$GroupRegion->GetGroupRegionID());
                                echo '<div class="form_heading"><h2>'.$this_url.$thisHeading.$this_url_end.'</h2></div>';
								echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($GroupRegion->GetStartDate()).'</div>';
								echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($GroupRegion->GetEndDate()).'</div>';
                                echo '</div>';
                            echo '</div>';
                            
                        }
                        
                        
                        ?>  
					</div> <!-- End Form Row -->
                
			</div><!--End Generic Form -->