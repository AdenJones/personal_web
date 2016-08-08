		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?> - <?php echo $url_add_venue ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                        <script>
						$(function() {
						$( "#tabs" ).tabs();
						});
						</script>
                        <div id="tabs" style="background: none repeat scroll 0% 0% transparent; font-family:inherit; font-size:inherit;">
                            <ul style="height: 30px;">
                                <li><a href="#tabs-1">Active Venues</a></li>
                                <li><a href="#tabs-2">Archived Venues</a></li>
                                
                           </ul>
                           <div id="tabs-1">
                           		<?php
									foreach ($ActiveVenues as $Venue) 
                                    {
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_venue.'&id_venue='.$Venue->GetVenueID().'">';
											$this_url_end = '</a>';
											
											$ViewAllAuditDates = '<a href="'.$lnk_view_audit_dates_secure.'&id_venue='.$Venue->GetVenueID().'">Venue Audit</a>';
											
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											
											$ViewAllAuditDates = '<a href="'.$lnk_view_audit_dates.'&id_venue='.$Venue->GetVenueID().'">Venue Audit</a>';
										}
										
										$LastAudit = $Venue->LastAudited();
										
										if( $LastAudit == NULL)
										{
											$LastAuditText = 'Not yet audited!';
										} else {
											$LastAuditText = funAusDateFormat($LastAudit->GetAuditDate());
										}
										
										$NextAudit = $Venue->NextAudit();
										
										if( $NextAudit == NULL)
										{
											$NextAuditText = 'Not yet set!';
										} else {
											$NextAuditText = funAusDateFormat($NextAudit->GetAuditDate());
										}
										
										echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Venue Name: '.$this_url.$Venue->GetName().$this_url_end.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Address</h3></div>';
										echo '<div class="form_item spaced"><span class="label">Address: </span>'.$Venue->GetAddress().'</div>';
										echo '<div class="form_item spaced"><span class="label">Suburb: </span> '.$Venue->GetSuburb().'</div>';
										echo '<div class="form_item spaced"><span class="label">State: </span>'.$Venue->GetStateAbbreviation().'</div>';
										echo '<div class="form_item spaced"><span class="label">PostCode: </span>'.$Venue->GetPostCode().'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Active</h3></div>';
										echo '<div class="form_item spaced"><span class="label">Start Date: </span> '.funAusDateFormat($Venue->GetStartDate()).'</div>';
										echo '<div class="form_item spaced"><span class="label">End Date: </span> '.funAusDateFormat($Venue->GetEndDate()).'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>'.$ViewAllAuditDates.'</h3></div>';
										echo '<div class="form_item spaced"><span class="label">Last Audited: </span> '.$LastAuditText.'</div>';
										echo '<div class="form_item spaced"><span class="label">Next Audit Date: </span> '.$NextAuditText.'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Other</h3></div>';
										echo '<div class="form_item spaced"><span class="label">Contract: </span>'.funBoolToString($Venue->GetContract()).'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Notes</h3></div>';
										echo '<div class="form_item">'.$Venue->GetComments().'</div>';
										echo '</div>';
										echo '</div>';
									}
								?>
                           		
                            </div> <!-- End Current Staff -->
                            <div id="tabs-2">
                            	<?php
									foreach ($ArchivedVenues as $Venue) 
                                    {
										if($secure)
										{
											$this_url = '<a href="'.$lnk_add_edit_venue.'&id_venue='.$Venue->GetVenueID().'">';
											$this_url_end = '</a>';
											
										}
										else
										{
											$this_url = '';
											$this_url_end = '';
											
										}
										
										$LastAudit = $Venue->LastAudited();
										
										if( $LastAudit == NULL)
										{
											$LastAuditText = 'Not yet audited!';
										} else {
											$LastAuditText = funAusDateFormat($LastAudit->GetAuditDate());
										}
										
										echo '<div class="form_item_major_block">';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h2>Venue Name: '.$this_url.$Venue->GetName().$this_url_end.'</h2></div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Address</h3></div>';
										echo '<div class="form_item spaced"><span class="label">Address: </span>'.$Venue->GetAddress().'</div>';
										echo '<div class="form_item spaced"><span class="label">Suburb: </span> '.$Venue->GetSuburb().'</div>';
										echo '<div class="form_item spaced"><span class="label">State: </span>'.$Venue->GetStateAbbreviation().'</div>';
										echo '<div class="form_item spaced"><span class="label">PostCode: </span>'.$Venue->GetPostCode().'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Active</h3></div>';
										echo '<div class="form_item spaced"><span class="label">Start Date: </span> '.funAusDateFormat($Venue->GetStartDate()).'</div>';
										echo '<div class="form_item spaced"><span class="label">End Date: </span> '.funAusDateFormat($Venue->GetEndDate()).'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Other</h3></div>';
										echo '<div class="form_item spaced"><span class="label">Safety Audit: </span> '.$LastAuditText.'</div>';
										echo '<div class="form_item spaced"><span class="label">Contract: </span>'.funBoolToString($Venue->GetContract()).'</div>';
										echo '</div>';
										echo '<div class="form_row">';
										echo '<div class="form_heading"><h3>Venue Notes</h3></div>';
										echo '<div class="form_item">'.$Venue->GetComments().'</div>';
										echo '</div>';
										echo '</div>';
									}
								?>
                                
                            </div> <!-- End Archived Staff -->
                        </div><!-- End Tabs Div -->
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->