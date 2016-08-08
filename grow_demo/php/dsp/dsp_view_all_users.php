	<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?> - <?php echo $AddUser; ?></h1></div>
                   	</div>
                    
                    <div class="form_row">
                    	<div class="form_heading"><h2>Welcome <?php echo $_SESSION['User']->GetScreenName(); ?>!</h2></div>
                        
                        <?php 
						
							foreach ($AllUsers as $User) 
							{			
							
								$link_edit_this_user = '<a href="'.$lnk_add_edit_user.'&id_user='.$User->GetUserID().'">Edit</a>';
												
													
								echo '<div class="form_item_major_block">';
									echo '<div class="form_row">';
									echo '<div class="form_heading"><h2>'.$User->GetUserTypeName().': '.$User->GetScreenName().' - '.$link_edit_this_user.'</h2></div>';
									echo '</div>';
								echo '</div>';
								
							} //end for each job
						 
					  ?>
						
                   	</div>
                    
                </div>
            </div><!-- End Content -->
                
        </div> <!--End Body -->