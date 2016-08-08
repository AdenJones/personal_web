		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?></h1></div>
                   	</div>
                    
                    <div class="form_row">
                    	<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                        	<input type="hidden" name="page_id" value="view_change_log" /> <!-- Post to self for validation -->
                            <input type="hidden" name="form_submitted" value="1" />
                            
                            <div class="form_row">
                            	Between <?php cntDate('Start:','StartDate',$StartDate,'Start Date',$bdYRange) ?> and 
                            	<?php cntDate('End:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                            </div>
                            <div class="form_submit">
                                <input class="button" type="submit" value="<?php echo $page_name; ?>" />
                            </div>
                      	</form>
                    </div>
					
                </div> <!-- End Generic Form -->
            	 <div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1><?php echo count($thisLog); ?> Records</h1></div>
                   	</div> 
                    
                   
                    <div class="form_row">
                    	
                        	<?php
                            foreach ($thisLog as $Log) 
							{			
								 
								$thisUser = Membership\User::LoadUserUnSafe($Log->GetChangedBy());
								
								if( $Log->GetChangeType() == CHANGE_DELETE )
								{
									$message = $Log->GetChangeType().'d a record from: '.$Log->GetPageID();
								} else if($Log->GetChangeType() == CHANGE_INSERT) 
								{
									$message = $Log->GetChangeType().'ed a record into: '.$Log->GetPageID();
								} else if($Log->GetChangeType() == CHANGE_UPDATE) 
								{
									$message = $Log->GetChangeType().'d a record in: '.$Log->GetPageID();
								}
								
								$timestampDetails = ' on '.funDateFormat($Log->GetDated(),'Y-m-d').' at '.funDateFormat($Log->GetDated(),'h:i A');
								
								if( $thisUser == NULL )
								{
									$UserName = 'Deleted or archived user';
								} else {
									if( $thisUser->HasStaffInterface() )
									{
										$thisStaff = \Membership\Staff::LoadStaff($thisUser->GetUserID());
										
										$UserName = $thisStaff->GetFirstName().' '.$thisStaff->GetLastname();
									} elseif ($thisUser->HasMemberInterface()) {
										$thisMember = \Membership\Member::LoadMember($thisUser->GetUserID());
										
										$UserName = $thisMember->GetFirstName().' '.$thisMember->GetLastname();
									} else {
										$UserName = $thisUser->GetScreenName();
									}
								}
								
								echo '<div class="paginate">';
								echo '<div class="form_item_major_block">';
								echo '<div class="form_row">';
								echo '<div class="form_heading"><h2>'.$UserName.' '.$message.$timestampDetails.'</h2></div>';
								echo '</div>';
								echo '</div>';
								echo '</div>';	
								
							}
							?>
                        <div id="pagination-div"></div>
                    	<!-- <div class="form_heading"><h2>Insert User Name here!</h2></div> -->
                                                
                       <script>
					   
					   jQuery(function($) {
						// Grab whatever we need to paginate
						var pageParts = $(".paginate");
					
						// How many parts do we have?
						var numPages = pageParts.length;
						// How many parts do we want per page?
						var perPage = 10;
					
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
                   	</div>
                    
                </div>
            </div><!-- End Content -->
                
        </div> <!--End Body -->