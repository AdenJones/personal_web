		<!--Main body content -->
        <div id="body">
            <!-- Content -->
            <div id="content">
           		<div class="generic_form">
                    <div class="form_major_heading"><h1><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?> - <?php echo $go_to_add_member; ?></h1></div>
                    
                        <?php cntFindMember($str_member,$int_member_hidden_input) ?>
                     
            	</div><!--End Generic Form -->
            </div><!-- End Content -->
        </div> <!--End Body -->