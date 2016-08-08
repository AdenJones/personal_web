
           		<div class="generic_form">
               		<div class="form_row">
                    	<div class="form_major_heading"><h1> <?php echo $page_name ?> - <?php echo $Heading ?></h1></div>
                   	</div>
                    
					<div class="form_row">
                    	<table style="position:relative;background-color:white">
                           		<?php
									//var_dump($ReportFields);
									
									$fields = 0;
									$FieldName = '';
									$Column = 0;
									$Totals = array();
									$HasTotals = false;
									
									foreach ($ReportFields as $Field) 
                                    {
										$fields++;
										
										if($Field->GetColumn() == '')
										{
										echo '<tr><td>'.$Field->GetFieldName().'</td><td>'.($Field->GetFieldValue() + 0).'</td></tr>'.$newLine;
										} else {
											
											$HasTotals = true;
											
											if( $Field->GetFieldName() != 'Heading' )
											{
												if( !array_key_exists( $Field->GetColumn() , $Totals ))
												{
													$Totals[$Field->GetColumn() ] = ($Field->GetFieldValue() + 0);
												} else {
													$Totals[$Field->GetColumn()] += ($Field->GetFieldValue() + 0);
												}
											}
											
											if( $Field->GetFieldName() == 'Heading' and $fields == 1 )
											{
												$FieldName == $Field->GetFieldName();
												
												echo '<tr><th>Groups: </th><th>'.$Field->GetFieldValue().'</th>';
											}elseif($Field->GetFieldName() == 'Heading')
											{
												echo '<th>'.$Field->GetFieldValue().'</th>';
											}elseif(($Field->GetFieldName() != 'Heading' and $FieldName == 'Heading'))
											{
												
												echo '</tr>'.$newLine; //end headings row
												echo '<tr><td>'.$Field->GetFieldName().'</td><td>'.$Field->GetFieldValue().'</td>';
												
												$FieldName == $Field->GetFieldName();
												
												
											}elseif($Field->GetColumn() == 1 ){
												$Column = $Field->GetColumn();
												echo '</tr>'.$newLine; //end headings row
												echo '<tr><td>'.$Field->GetFieldName().'</td><td>'.$Field->GetFieldValue().'</td>';
												
												$FieldName == $Field->GetFieldName();
												
											}else{
												$FieldName == $Field->GetFieldName();
												
												if( $Field->GetColumn() > $Column )
												{
													$Column = $Field->GetColumn();
													
													echo '<td>'.($Field->GetFieldValue() + 0).'</td>';
													
												} 
												
												if(count($ReportFields) == $fields)
												{
													echo '</tr>'.$newLine; //end headings row
												}
												
											}
										}//end if we are dealing with multiple columns
										
										
									}//end for each
								//output totals
								if( $HasTotals )
								{
									echo '<tr><th>Totals:</th>';
									
									foreach( $Totals as $Total )
									{
										echo '<th>'.$Total.'</th>';
									}
									
									echo '</tr>';
								}
							?>
                        </table>
					</div> <!-- End Form Row -->
                </div> <!-- End Generic Form -->