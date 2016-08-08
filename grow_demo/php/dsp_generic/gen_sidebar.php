<?php 
/*
	Dyanmically creates sidebar content  
*/

?>

    <div id="generic_sidebar">
        <div class="sidebar_block">
            <div class="sidebar_text_item_heading">Current location</div>
            <div class="sidebar_text_item"><?php echo $page_name ?></div>
        </div>
        <div class="sidebar_block">
            <div class="sidebar_text_item_heading">Welcome</div>
            <div class="sidebar_text_item"><?php echo $_SESSION['User']->GetUserTypeName(); ?></div>
            <div class="sidebar_text_item"><?php echo $_SESSION['User']->GetScreenName(); ?></div>
        </div>
        <div class="sidebar_block">
        	<div class="sidebar_link"><a href="<?php echo $lnk_log_out;?>">Log Out</a></div>
		</div>
    
    </div>