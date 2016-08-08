		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
            	<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?></h1></div>
                   	</div>
                    
                    <div class="form_row">
                    	<form action="<?php echo "$full_uri/index.php"; ?>" method="post" onsubmit="document.getElementById('group_by_group_Loading').style.display = 'block';">
                        	<input type="hidden" name="page_id" value="report_group_by_group_improved" /> <!-- Post to self for validation -->
                            <input type="hidden" name="form_submitted" value="1" />
                            <div class="form_row">
                            	
                                <?php cntGroupRegionBranchSelector() ?>
                            	
                           		<?php cntGroupSelector('Select Group:','Group','GroupID',$Group,$GroupID,'Group',50) ?> 
                            </div>
                           
                            <div class="form_row">
                            	Between <?php cntDate('Start:','StartDate',$StartDate,'Start Date',$bdYRange) ?> and 
                            	<?php cntDate('End:','EndDate',$EndDate,'End Date',$bdYRange) ?>
                            </div>
                            <div class="form_submit">
                                <input class="button" type="submit" value="<?php echo $page_name; ?>" />
                            </div>
                      	</form>
                    </div>
					<?php cntLoading('group_by_group'); ?>
                </div> <!-- End Generic Form -->
            	
            </div><!-- End Content -->
                
        </div> <!--End Body -->