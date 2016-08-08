<?php

class PDF extends FPDF
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
	
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('Calibri');
	$pdf->AddFont('Calibri','B','calibrib.php');
	$pdf->AddPage();
	
	$pdf->SetFont('Calibri','B',15);
	$pdf->Cell(0,7,'Meeting Details',0,1,'L');
	$pdf->SetFont('Calibri','B',13);
	$pdf->Cell(0,7,'Total Meetings Held: '.$MeetingsAttended,0,1,'L');
	$pdf->Cell(0,7,'Total Meetings Scheduled: '.$MeetingsScheduled,0,1,'L');
	$pdf->Cell(0,7,'',0,1,'L');
	
	$pdf->SetFont('Calibri','B',15);
	$pdf->Cell(0,7,'Attendees Details',0,1,'L');
	$pdf->SetFont('Calibri','B',13);
	$pdf->Cell(0,7,'Total First Timers: '.$FirstTimers,0,1,'L');
	$pdf->Cell(0,7,'Total Community Observers: '.$TotalCommunityObservers,0,1,'L');
	$pdf->Cell(0,7,'Total Committed Growers: '.$TotalCommittedGrowers,0,1,'L');
	$pdf->Cell(0,7,'Total New Committed Growers: '.$TotalNewCommittedGrowers,0,1,'L');
	$pdf->Cell(0,7,'',0,1,'L');
	
	$pdf->SetFont('Calibri','B',15);
	$pdf->Cell(0,7,'Attendees Attendances',0,1,'L');
	$pdf->SetFont('Calibri','B',13);
	$pdf->Cell(0,7,'Committed Growers Not Attending Last 8 Meetings: '.$TotalCommittedGrowersWhoHaventAttendedForLastEightMeetings,0,1,'L'); //
	$pdf->Cell(0,7,'Total Committed Grower Attendances: '.$TotalNOOfCommittedGrowerAttendances,0,1,'L');
	//$pdf->Cell(0,7,'Total Prospective Grower Attendances: '.$TotalNOOfNonCommittedGrowerAttendances,0,1,'L');
	$pdf->Cell(0,7,'Total Organiser Attendances: '.$TotalOrganiserAttendances,0,1,'L');
	$pdf->Cell(0,7,'Total Recorder Attendances: '.$TotalRecorderAttendances,0,1,'L');
	$pdf->Cell(0,7,'Total Field Worker Attendances: '.$TotalFieldWorkerAttendances,0,1,'L');
	$pdf->Cell(0,7,'',0,1,'L');
	
	$pdf->SetFont('Calibri','B',15);
	$pdf->Cell(0,7,'Totals',0,1,'L');
	$pdf->SetFont('Calibri','B',13);
	$pdf->Cell(0,7,'Total Attendees: '.$TotalAttendees,0,1,'L');
	$pdf->Cell(0,7,'Total Attendances: '.$TotalAttendances,0,1,'L');
	
	$pdf->Output();

}


?>