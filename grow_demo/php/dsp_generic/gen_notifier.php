<?php 
/*
	Dyanmically creates notification sidebar content  
*/
	
?>
    

    <div id="generic_notifier" class="<?php echo ($msg_general_notifier == 'No Notifications!') ? 'white_trans_background' : 'highlight_blk_error'; ?>">
      <h1>Notifications</h1>
        <div class="notifier_sidebar_block">
            <!--<div class="sidebar_text_item_heading">Notifications</div> -->
            <div class="sidebar_text_item_bold"><?php echo $msg_general_notifier; ?></div>
        </div>
    	
    </div>