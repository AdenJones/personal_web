		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                    	<?php //var_dump($CommittedGrowers) ?>
                        
                        <?php
							foreach($CommittedGrowers as $Commie)
							{
								echo '<p>'.$Commie['Total'].': '.$Commie['this_year'].' '.$Commie['this_month'].'</p>';
							}
							
							for($i = 0; $i < count($CommittedGrowers); $i++)
							{
								echo '<p>'.$CommittedGrowers[$i]['Total'].': '.$CommittedGrowers[$i]['this_year'].' '.$CommittedGrowers[$i]['this_month'].'</p>';
							}
						?>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->