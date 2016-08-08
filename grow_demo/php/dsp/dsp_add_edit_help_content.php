			<div class="generic_form">
            	<div class="form_major_heading"><h1><?php echo $page_name ?> for <?php echo $Page->getPageName() ?></h1></div>
           		<form action="<?php echo "$full_uri/index.php"; ?>" method="post">
                	<!-- submit page_id for submission to self -->
                	<input type="hidden" name="page_id" value="add_edit_help" />
                    <input type="hidden" name="help_for" value="<?php echo $PageID ?>" />
                    <input type="hidden" name="return_to" value="<?php echo urlencode($ReturnTo) ?>" />
                    <!-- capture attempted form submission -->
                    <input type="hidden" name="form_submitted" value="1" />
                    
                	<div class="form_row">
                        <?php cntLongText('Page Help','PageHelp',$PageHelp,'Page Help');?>
                        
                    </div>
                   
                    <div class="form_submit">
                    	<input class="button" type="submit" value="<?php echo $page_name; ?>" />
                    </div>
                </form>
			</div><!--End Generic Form -->