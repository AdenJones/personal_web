		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                    	
                    
                       
                           		<?php
									foreach ($MyLabels as $Labels) 
                                    {
										if( $Labels->GetLabelsStatus() == STATUS_COMPLETE )
										{
											$view_html = '<a class="inline_block" href="'.$lnk_view_labels.'&LabelsID='.$Labels->GetIDUserLabels().'">View HTML</a>';
											
											$view_csv = '<a class="inline_block" href="'.$lnk_csv_generic_labels.'&LabelsID='.$Labels->GetIDUserLabels().'">View Excel</a>';
											
											$view_L7163 = '<a class="inline_block" href="'.$lnk_volunteer_labels_L7163.'&LabelsID='.$Labels->GetIDUserLabels().'">Avery L7163</a>';
											
											$view_L7160 = '<a class="inline_block" href="'.$lnk_volunteer_labels_L7160.'&LabelsID='.$Labels->GetIDUserLabels().'">Avery L7160</a>';
										} else {
											$view_html = "Awaiting Completion";
											
											$view_csv = '';
											
											$view_L7163 = '';
											
											$view_L7160 = '';
										}
										echo '<div class="paginate">';
										echo '<div id="Labels'.$Labels->GetIDUserLabels().'" class="form_item_major_block">';
											
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Labels Type: '.$Labels->GetLabelsType().'</h2></div>';
												echo '<div class="form_heading"><h2>Labels Name: '.$Labels->GetLabelsName().'</h2></div>';
												echo '<div class="form_heading"><h2>Labels Dates: '.$Labels->GetLabelsDates().'</h2></div>';
												echo '<div class="form_row">'.$view_html.$view_csv.$view_L7163.$view_L7160.'</div>';
												echo '<div class="form_item spaced"><span class="label">Created: </span> '.funAusDateFormat($Labels->GetCreated()).'</div>';
												echo '<div class="form_item spaced"><span class="label">Labels Status: </span> '.$Labels->GetLabelsStatus().'</div>';
												
											echo '</div>';
											
										echo '</div>';
										echo '</div>';
									}
								
							?>
                            <div id="pagination-div"></div>
                             <script>
					   
					   jQuery(function($) {
						// Grab whatever we need to paginate
						var pageParts = $(".paginate");
					
						// How many parts do we have?
						var numPages = pageParts.length;
						// How many parts do we want per page?
						var perPage = 3;
					
						// When the document loads we're on page 1
						// So to start with... hide everything else
						pageParts.slice(perPage).hide();
						// Apply simplePagination to our placeholder
						$("#pagination-div").pagination({
							items: numPages,
							itemsOnPage: perPage,
							cssStyle: "light-theme",
							// We implement the actual pagination
							//   in this next function. It runs on
							//   the event that a user changes page
							onPageClick: function(pageNum) {
								// Which page parts do we show?
								var start = perPage * (pageNum - 1);
								var end = start + perPage;
					
								// First hide all page parts
								// Then show those just for our page
								pageParts.hide()
										 .slice(start, end).show();
							}
						});
					});
					   
                       
					</script>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->