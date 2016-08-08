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
		$pdf->Row(array(date_format($thisDate,"F Y"),
				$RegionObject->TotalMeetingsByMonthYearAndType($thisDate,'General'),
				$RegionObject->TotalMeetingsByMonthYearAndType($thisDate,'Closed'),
				$RegionObject->TotalMeetingsByMonthYearAndType($thisDate,'Special'),
				$RegionObject->TotalMeetingsByMonthYearAndTypeOther($thisDate),
				$RegionObject->TotalFirstTimersByMonthYear($thisDate),
				$RegionObject->TotalCommunityObserversByMonthYear($thisDate),
				$RegionObject->TotalCommittedGrowersInPeriodByMonthYear($thisDate),
				$RegionObject->CountNewCommittedGrowersInPeriod($thisDate),
				$RegionObject->CountCGLaspsedAttendances($thisDate),
				$RegionObject->CommittedGrowerAttendancesInPeriod($thisDate),
				$RegionObject->AverageCommittedGrowerAttendancesInPeriod($thisDate),
				$RegionObject->CountGroupsWithOrganiserByMonth($thisDate),
				$RegionObject->CountGroupsWithRecorderByMonth($thisDate),
				$RegionObject->CountFormedGroupsByMonth($thisDate),
				$RegionObject->CountFieldWorkerAttendanceAllGroups($thisDate)));
	}
	
	$pdf->Output();
}


?>