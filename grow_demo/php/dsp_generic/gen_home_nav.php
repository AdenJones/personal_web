			<!-- Menu -->
            <!-- Uses a purely CSS based rollover styling
                 and list based structure to allow for older
                 browsers and disabling of javascript. -->
          	            
            <div class="menu">
                <div id="navigation">
                    <ul class="top-level">
                    	<li id="main_logo"><a href="<?php echo $lnk_default_page; ?>"><img src="/images/grow_logo.png" width="100" height="30" alt="Home" /></a></li>
                        <?php
							
							//reload the nav pages
							$_SESSION['User']->LoadNavPages();
							
							$old_category = '';
							$old_sub_cat = '';
							
							foreach ($_SESSION['User']->GetNavPages() as $value)
							{
								$this_category = $value->getMenuCategoryName();
								$this_sub_cat = $value->getSubMenuCategoryName();
								
								if( $value->getPageID() == 'help' or $value->getPageID() == 'help&access=secure' )
								{
									//
									$GenNavReturnTo = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
									$escaped_link = urlencode($GenNavReturnTo);
									
									$thisURI = $full_uri.'/index.php?page_id='.$value->getPageID().'&help_for='.$page_id.'&return_to='.$escaped_link;
									$PageName = $value->getPageName().' this page';
								} else {
									$thisURI = $full_uri.'/index.php?page_id='.$value->getPageID();
									$PageName = $value->getPageName();
								}
								
								if( $old_category == '' )
								{
									$old_category = $this_category;
									echo funCreateTabs(6).'<li class="menu_category_header"><a href="#">'.$value->getMenuCategoryName().'</a>'.$newLine;
										echo funCreateTabs(7).'<ul class="sub-level">'.$newLine;
										echo funCreateTabs(8).'<li class="menu_category_item"><a href="'.$thisURI.'">'.$PageName.'</a></li>'.$newLine;
								}elseif($this_sub_cat != '' and $old_sub_cat != $this_sub_cat )
								{
									$old_sub_cat = $this_sub_cat;
									echo funCreateTabs(7).'<li class="menu_category_item"><a href="#">'.$value->getMenuCategoryName().'</a>'.$newLine;
									echo funCreateTabs(8).'<ul class="sub-sub-level">'.$newLine;
									echo funCreateTabs(9).'<li class="sub_menu_category_item"><a class="sub_sub" href="'.$thisURI.'">'.$PageName.'</a></li>'.$newLine;
									
								}elseif($this_sub_cat != '' and $old_sub_cat == $this_sub_cat )
								{
									echo funCreateTabs(9).'<li class="sub_menu_category_item"><a class="sub_sub" href="'.$thisURI.'">'.$PageName.'</a></li>'.$newLine;
								}
								elseif( $this_category == $old_category )
								{
									echo funCreateTabs(8).'<li class="menu_category_item"><a href="'.$thisURI.'">'.$PageName.'</a></li>'.$newLine;
									
								} else {
									
									if( $this_sub_cat == '' and $old_sub_cat != $this_sub_cat )
									{
										$old_sub_cat = $this_sub_cat;
										
										echo funCreateTabs(8).'</ul>'.$newLine;
										echo funCreateTabs(7).'</li>'.$newLine;
									}
									
									$old_category = $this_category;
									//output the category
									echo funCreateTabs(7).'</ul>'.$newLine.funCreateTabs(6).'</li>'.$newLine;
									echo funCreateTabs(6).'<li class="menu_category_header"><a href="#">'.$value->getMenuCategoryName().'</a>'.$newLine;
										echo funCreateTabs(7).'<ul class="sub-level">'.$newLine;
										echo funCreateTabs(8).'<li class="menu_category_item"><a href="'.$thisURI.'">'.$PageName.'</a></li>'.$newLine;
								}
									
							} //end for each
							
							echo funCreateTabs(7).'</ul>'.$newLine.funCreateTabs(6).'</li>'.$newLine;
						
						 ?>
                    </ul>
                </div>
          	</div><!-- End Menu -->