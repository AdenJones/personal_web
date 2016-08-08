<?php

class PDF extends PDF_MC_Table
{
	function Header()
	{
		global $ReportName;
		global $DatesString;
		
		$this->SetFont('Calibri','B',16);
		$this->SetXY(10,10);
		$this->Image('images/grow_logo.png',10,10,50);
		$this->SetXY(65,10);
		$this->Cell(0,10,$ReportName,0,1,'L');
		$this->SetX(65);
		$this->Cell(0,10,$DatesString,0,1,'L');
		
		$this->SetY(35);
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

if( !$blnIsGood )
{
	$pdf = new FPDF();
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,10,'Bad URL values submitted!' ,0 ,0 ,'C');
	$pdf->Output();
} else {
	
	$pdf = new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	$pdf->AddFont('Calibri');
	$pdf->AddFont('Calibri','B','calibrib.php');
	$pdf->AddPage();
	
	//Key
	$pdf->SetFont('Calibri','B',15);
	$pdf->Cell(0,7,'Key: ',0,1,'L');
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
	$pdf->SetWidths(array(60,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14));
	$pdf->Row(array('Months','NGM','NCM','NSM','NOM','TFT','TCO','TCG','TNCG','TCGLA','TCGA','ACGA','TGO','TGR','TFG','TFA'));
	
	foreach($Dates as $thisDate)
	{
		$NGM = 0;
		$NCM = 0;
		$NSM = 0;
		$NOM = 0;
		
		$TFT = 0;
		$TCO = 0;
		$TCG = 0;
		$TNCG = 0;
		
		$TCGLA = 0;
		$TCGA = 0;
		$ACGA = 0;
		
		$TGO = 0;
		$TGR = 0;
		$TFG = 0;
		$TFA = 0;
		
		foreach( $Regions as $Region )
		{
			$NGM += $Region->TotalMeetingsByMonthYearAndType($thisDate,'General');
			$NCM += $Region->TotalMeetingsByMonthYearAndType($thisDate,'Closed');
			$NSM += $Region->TotalMeetingsByMonthYearAndType($thisDate,'Special');
			$NOM += $Region->TotalMeetingsByMonthYearAndTypeOther($thisDate);
			
			$TFT += $Region->TotalFirstTimersByMonthYear($thisDate);
			$TCO += $Region->TotalCommunityObserversByMonthYear($thisDate);
			$TCG += $Region->TotalCommittedGrowersInPeriodByMonthYear($thisDate);
			$TNCG += $Region->CountNewCommittedGrowersInPeriod($thisDate);
			
			$TCGLA += $Region->CountCGLaspsedAttendances($thisDate);
			$TCGA += $Region->CommittedGrowerAttendancesInPeriod($thisDate);
			$ACGA += $Region->AverageCommittedGrowerAttendancesInPeriod($thisDate); //remember to average again
			
			$TGO += $Region->CountGroupsWithOrganiserByMonth($thisDate);
			$TGR += $Region->CountGroupsWithRecorderByMonth($thisDate);
			$TFG += $Region->CountFormedGroupsByMonth($thisDate);
			$TFA += $Region->CountFieldWorkerAttendanceAllGroups($thisDate);
		}
		
		$ACGA = round($ACGA / count($Regions));
		
		$pdf->Row(array(date_format($thisDate,"F Y"),
				$NGM,
				$NCM,
				$NSM,
				$NOM,
				$TFT,
				$TCO,
				$TCG,
				$TNCG,
				$TCGLA,
				$TCGA,
				$ACGA,
				$TGO,
				$TGR,
				$TFG,
				$TFA));
	}
	
	$pdf->Output();
}


?>