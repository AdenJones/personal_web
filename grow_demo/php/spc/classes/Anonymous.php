<?php 
	namespace Anonymous;
	
class State{
	private $StateID;
	private $Abbreviation;
	private $Name;
	
	function __construct() {
		//empty constructor that may need to be altered later
	}
	
	public static function LoadState($StateID)
	{
		
	}
	
} // End State Class
	

	
class OutputCSVFields{
	// Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
	
	public static function Labels($ReportType,$GroupName,$Dates,$filename,$content)
	{
		
		
		// filename for download
		$filename = $filename.".csv";
		
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: text/csv");
		
		$out = fopen("php://output", 'w');
		
		fputcsv($out, array($ReportType));
		fputcsv($out, array($GroupName));
		fputcsv($out, array($Dates));
		
		fputcsv($out, array('Name','Address','Suburb','State','Postcode'));
		
			foreach( $content as $item)
			{
				fputcsv($out, array( html_entity_decode($item->GetName()), html_entity_decode($item->GetAddress()), html_entity_decode($item->GetSuburb()), html_entity_decode($item->GetState()), html_entity_decode($item->GetPostCode())));
			}
		
		  fclose($out);
		  exit;
	}
	
	public static function GenericReport($ReportType,$ReportTypeHeading,$GroupName,$Dates,$filename,$content)
	{
		$Totals = array();
		$HasTotals = false;
		$fields = 0;
		$HasColumns = false;
		$FieldName = '';
		$Column = 0;
		$Headings = array();
		$FieldsColumns = array();
		
		foreach ($content as $Field) 
		{
			$fields++;
			
			if($Field->GetColumn() == '')
			{
				
			} else {
				$HasTotals = true;
				$HasColumns = true;
							
				//calculate totals
				if( $Field->GetFieldName() != 'Heading' )
				{
					if( !array_key_exists( $Field->GetColumn() , $Totals ))
					{
						$Totals[$Field->GetColumn() ] = $Field->GetFieldValue();
					} else {
						$Totals[$Field->GetColumn()] += $Field->GetFieldValue();
					}
				}
				
				if( $Field->GetFieldName() == 'Heading' and $fields == 1 )
				{
					$Headings[] = 'Groups:';
					$Headings[] = $Field->GetFieldValue();
					
					$FieldName == $Field->GetFieldName();
					
				}elseif($Field->GetFieldName() == 'Heading')
				{
					$Headings[] = $Field->GetFieldValue();
					
				}elseif(($Field->GetFieldName() != 'Heading' and $FieldName == 'Heading'))
				{
					$FieldsColumns[$Field->GetColumn()] = array($Field->GetFieldName(),$Field->GetFieldValue());
					
					$FieldName == $Field->GetFieldName();
					
				}else{
					
					if( !array_key_exists( $Field->GetFieldName() , $FieldsColumns ))
					{
						$FieldsColumns[$Field->GetFieldName() ] = array(html_entity_decode($Field->GetFieldName()),$Field->GetFieldValue());
					} else {
						$FieldsColumns[$Field->GetFieldName()][] = ($Field->GetFieldValue() + 0);
					}
					
					$FieldName == $Field->GetFieldName();
					
				}
				
			} // end if we are dealing with multiple columns
			
		}//end for each
		
		// filename for download
		$filename = $filename.".csv";
		
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: text/csv");
		
		$out = fopen("php://output", 'w');
		
		fputcsv($out, array($ReportTypeHeading));
		fputcsv($out, array($GroupName));
		fputcsv($out, array($Dates));
		
		if($HasColumns)
		{
			fputcsv($out, $Headings);
			foreach($FieldsColumns as $ThisColumn) 
			{
				fputcsv($out, $ThisColumn);
		
			}
			
			$NewTotals = array();
		
			$NewTotals[] = 'Totals:';
		
			foreach($Totals AS $ThisTotals)
			{
				$NewTotals[] = $ThisTotals;
			}
			
			fputcsv($out, $NewTotals);
		}
		else {
			foreach( $content as $item)
			{
				fputcsv($out, array($item->GetFieldName(),$item->GetFieldValue()));
			}
		}
		  fclose($out);
		  exit;
	}
}
	
?>