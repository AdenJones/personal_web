		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?> - <?php echo $url_add_region ?></h1></div>
                   	</div>
                    
					<div class="form_row">
						<?php
                        //the use of two queries here is due to mysql not supporting total outer joins :(
                        foreach ($Regions as $Region) 
                        {
                            if($secure)
                            {
                                $this_url = '<a href="'.$lnk_add_edit_region.'&id_region='.$Region->GetRegionID().'">';
                                $this_url_end = '</a>';
                                
                            }
                            else
                            {
                                $this_url = '';
                                $this_url_end = '';
                            }
                            
                            echo '<div class="form_item_major_block">';
                                echo '<div class="form_row">';
								cntDeleteRegion($Region->GetRegionID());
                                echo '<div class="form_heading"><h2> Region: '.$this_url.$Region->GetRegionName().' '.$this_url_end.'</h2></div>';
								echo '<div class="form_item spaced"><span class="label">Branch:</span> '.$Region->GetBranch()->GetBranchName().'</h2></div>';
								echo '<div class="form_item spaced"><span class="label">Start Date:</span> '.funAusDateFormat($Region->GetStartDate()).'</h2></div>';
								echo '<div class="form_item spaced"><span class="label">End Date:</span> '.funAusDateFormat($Region->GetEndDate()).'</h2></div>';
                                echo '</div>';
                            echo '</div>';
                            
                        }
                        
                        
                        ?>  
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->