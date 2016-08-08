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
									foreach ($MyReports as $Report) 
                                    {
										if( $Report->GetReportStatus() == STATUS_COMPLETE )
										{
											$view_html = '<a class="inline_block" href="'.$lnk_view_report.'&ReportID='.$Report->GetIDUserReport().'">View HTML</a>';
											$view_pdf = '<a class="inline_block" href="'.$lnk_pdf_generic_report.'&ReportID='.$Report->GetIDUserReport().'">View PDF</a>';
											$view_csv = '<a class="inline_block" href="'.$lnk_csv_generic_report.'&ReportID='.$Report->GetIDUserReport().'">View Excel</a>';
										} else {
											$view_html = "Awaiting Completion";
											$view_pdf = '';
											$view_csv = '';
										}
										echo '<div class="paginate">';
										echo '<div id="report'.$Report->GetIDUserReport().'" class="form_item_major_block">';
											
											echo '<div class="form_row">';
												echo '<div class="form_heading"><h2>Report Type: '.$Report->GetReportType().'</h2></div>';
												echo '<div class="form_heading"><h2>Report Name: '.$Report->GetReportName().'</h2></div>';
												echo '<div class="form_heading"><h2>Report Dates: '.$Report->GetReportDates().'</h2></div>';
												echo '<div class="form_row">'.$view_html.$view_pdf.$view_csv.'</div>';
												echo '<div class="form_item spaced"><span class="label">Created: </span> '.funAusDateFormat($Report->GetCreated()).'</div>';
												echo '<div class="form_item spaced"><span class="label">Report Status: </span> '.$Report->GetReportStatus().'</div>';
												
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