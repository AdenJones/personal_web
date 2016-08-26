<?php

	require($BaseIncludeURL.'/FPDF181/fpdf.php');
	require($BaseIncludeURL.'/FPDF181/fpdf_mk_table.php');
	
class PDF extends PDF_MC_Table
{
	function Header()
	{
		global $ReportType;
		global $GroupName;
		global $Dates;
		global $ReportTypeHeading;
		
		$this->SetFont('Calibri','B',16);
		$this->SetXY(10,10);
		$this->Image('images/grow_logo.png',10,10,50);
		$this->SetXY(10,30);
		$this->Cell(0,10,$ReportTypeHeading,0,1,'L');
		$this->Cell(0,10,$GroupName,0,1,'L');
		$this->Cell(0,10,$Dates,0,1,'L');
	}
	
	function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

if( $bad_report )
{
	$pdf = new FPDF();
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,10,'Bad URL values submitted!' ,0 ,0 ,'C');
	$pdf->Output();
} elseif($bad_user) {
	$pdf = new FPDF();
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,10,'You do not have access to this report!' ,0 ,0 ,'C');
	$pdf->Output();
} else {
	
	$Totals = array();
	$HasTotals = false;
	$fields = 0;
	$HasColumns = false;
	$FieldName = '';
	$Column = 0;
	$Headings = array();
	$FieldsColumns = array();
	
	foreach ($ReportFields as $Field) 
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
	
	
	
	if($ReportType == GROUP_BY_REGION or $ReportType == GROUP_BY_BRANCH )
	{
		
		$Widths = array();
	
		$Widths[] = 100;
		
		foreach( $Headings as $Heading )
		{
			$Widths[] = 14;
		}
		
		$pdf = new PDF('L','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddFont('Calibri');
		$pdf->AddFont('Calibri','B','calibrib.php');
		$pdf->AddPage();
		
		$pdf->SetFont('Calibri','B',13);
		$pdf->SetWidths(array(100,20));
		
		
		$pdf->Row(array('Total Meetings Held:','TMH'));
		$pdf->Row(array('Total Meetings Scheduled:','TMS'));
		
		$pdf->Row(array('Total First Timers:','TFT'));
		$pdf->Row(array('Total Community Observers:','TCO'));
		$pdf->Row(array('Total Committed Growers:','TCG'));
		$pdf->Row(array('Total New Committed Growers:','TNCG'));
		
		$pdf->Row(array('Total CG Lapsed Attendance:','TCGLA'));
		$pdf->Row(array('Total Committed Grower Attendances:','TCA'));
		//$pdf->Row(array('Total Prospective Grower Attendances:','TPG'));
		$pdf->Row(array('Total Organiser Attendances:','TOA'));
		$pdf->Row(array('Total Recorder Attendances:','TRA'));
		$pdf->Row(array('Total Field Worker Attendances:','TFA'));
		
		$pdf->Row(array('Total Attendees:','TAees'));
		$pdf->Row(array('Total Attendances:','TAces'));
		$pdf->AddPage();
		
		$pdf->SetFont('Calibri','B',13);
		$pdf->SetWidths($Widths);
		$pdf->Row($Headings);
		
		foreach($FieldsColumns as $ThisColumn) 
		{
			$pdf->Row($ThisColumn);
	
		}
		
		$NewTotals = array();
		
		$NewTotals[] = 'Totals:';
		
		foreach($Totals AS $ThisTotals)
		{
			$NewTotals[] = $ThisTotals;
		}
		
		$pdf->Row($NewTotals);
	
	} elseif($ReportType == TREND_BY_REGION or $ReportType == TREND_BY_BRANCH) {
		
		$Widths = array();
	
		$Widths[] = 60;
		
		foreach( $Headings as $Heading )
		{
			$Widths[] = 14;
		}
		
		$pdf = new PDF('L','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddFont('Calibri');
		$pdf->AddFont('Calibri','B','calibrib.php');
		$pdf->AddPage();
		
		$pdf->SetFont('Calibri','B',13);
		$pdf->SetWidths(array(100,20));
		
		
		$pdf->Row(array('No General Meetings:','NGM'));
		$pdf->Row(array('No Closed Meetings','NCM'));
		$pdf->Row(array('No Special Meetings','NSM'));
		$pdf->Row(array('No Other Meetings','NOM'));
		
		$pdf->Row(array('Total First Timers:','TFT'));
		$pdf->Row(array('Total Community Observers:','TCO'));
		$pdf->Row(array('Total Committed Growers:','TCG'));
		$pdf->Row(array('Total New Committed Growers:','TNCG'));
		
		$pdf->Row(array('Total CG Lapsed Attendance:','TCGLA'));
		$pdf->Row(array('Total Committed Grower Attendances:','TCGA'));
		$pdf->Row(array('Average Committed Grower Attendances:','ACGA'));
		
		//$pdf->Row(array('Total Prospective Grower Attendances:','TPG'));
		$pdf->Row(array('Total No Groups with an Organiser:','TGO'));
		$pdf->Row(array('Total No Groups with a Recorder:','TGR'));
		$pdf->Row(array('Total Fully Formed Groups:','TFG'));
		$pdf->Row(array('Total Field Worker Attendances:','TFA'));
		
		$pdf->AddPage();
		
		$pdf->SetFont('Calibri','B',13);
		$pdf->SetWidths($Widths);
		$pdf->Row($Headings);
		
		foreach($FieldsColumns as $ThisColumn) 
		{
			$pdf->Row($ThisColumn);
	
		}
		
		$NewTotals = array();
		
		$NewTotals[] = 'Totals:';
		
		foreach($Totals AS $ThisTotals)
		{
			$NewTotals[] = $ThisTotals;
		}
		
		$pdf->Row($NewTotals);
		
	} elseif($ReportType == ATTENDEES_BY_REGION or $ReportType == ATTENDEES_BY_BRANCH or $ReportType == ATTENDANCES_BY_REGION or $ReportType == ATTENDANCES_BY_BRANCH or $ReportType == STATISTICS_BY_REGION or $ReportType == STATISTICS_BY_BRANCH)
	{
		$Widths = array();
	
		$Widths[] = 60;
		
		foreach( $Headings as $Heading )
		{
			$Widths[] = 27;
		}
		
		$pdf = new PDF('L','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddFont('Calibri');
		$pdf->AddFont('Calibri','B','calibrib.php');
		$pdf->AddPage();
		
		$pdf->SetFont('Calibri','B',13);
		$pdf->SetWidths($Widths);
		$pdf->Row($Headings);
		
		foreach($FieldsColumns as $ThisColumn) 
		{
			$pdf->Row($ThisColumn);
	
		}
		
		$NewTotals = array();
		
		$NewTotals[] = 'Totals:';
		
		foreach($Totals AS $ThisTotals)
		{
			$NewTotals[] = $ThisTotals;
		}
		
		$pdf->Row($NewTotals);
		
	} else {
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('Calibri');
		$pdf->AddFont('Calibri','B','calibrib.php');
		$pdf->AddPage();
		$pdf->SetFont('Calibri','B',13);
		
		foreach($ReportFields as $Field) 
		{
			$pdf->Cell(0,7,html_entity_decode($Field->GetFieldName()).' '.$Field->GetFieldValue(),0,1,'L');
		}
	}
	
	$pdf->Output();

}


?>
