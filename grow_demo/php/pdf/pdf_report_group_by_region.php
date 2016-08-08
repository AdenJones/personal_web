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
	
	//Data
	$pdf->SetFont('Calibri','B',13);
	$pdf->SetWidths(array(100,14,14,14,14,14,14,14,14,14,14,14,14,14));
	$pdf->Row(array('Group Name','TMH','TMS','TFT','TCO','TCG','TNCG','TCGLA','TCA','TOA','TRA','TFA','TAees','TAces'));
	
	$TMA_Total = 0;
	$TMS_Total = 0;
	
	$TFT_Total = 0;
	$TCO_Total = 0;
	$TCG_Total = 0;
	$TNCG_Total = 0;
	
	$TCGLA_Total = 0;
	$TCA_Total = 0;
	//$TNA_Total = 0;
	$TOA_Total = 0;
	$TRA_Total = 0;
	$TFA_Total = 0;
	
	$TAees_Total = 0;
	$TAces_Total = 0;
	
	foreach( $Groups as $Group )
	{
		$TMA_Total += $Group->CountMeetingsAttendedInPeriod($str_safe_s_date,$str_safe_e_date);
		$TMS_Total += $Group->CountMeetingsScheduledInPeriod($str_safe_s_date,$str_safe_e_date);
		
		$TFT_Total += count($Group->LoadFirstTimersBetweenDates($str_safe_s_date,$str_safe_e_date));
		$TCO_Total += $Group->CountCommunityObserversInPeriod($str_safe_s_date,$str_safe_e_date);
		$TCG_Total += $Group->CountCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date);
		$TNCG_Total += $Group->CountNewCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date);
		
		$TCGLA_Total += $Group->CountNSinceLastAttended(8,$str_safe_s_date,$str_safe_e_date);
		$TCA_Total += $Group->CommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
		//$TNA_Total += $Group->NonCommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
		$TOA_Total += $Group->OrganiserAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
		$TRA_Total += $Group->RecorderAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
		$TFA_Total += $Group->FieldWorkerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
		
		$TAees_Total += $Group->TotalAttendeesInPeriod($str_safe_s_date,$str_safe_e_date);
		$TAces_Total += $Group->TotalAttendancesInPeriod($str_safe_s_date,$str_safe_e_date);
		
		$pdf->Row(
					array(
					html_entity_decode($Group->GetGroupName()),
					html_entity_decode($Group->CountMeetingsAttendedInPeriod($str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->CountMeetingsScheduledInPeriod($str_safe_s_date,$str_safe_e_date)),
					
					html_entity_decode(count($Group->LoadFirstTimersBetweenDates($str_safe_s_date,$str_safe_e_date))),
					html_entity_decode($Group->CountCommunityObserversInPeriod($str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->CountCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->CountNewCommittedGrowersInPeriod($str_safe_s_date,$str_safe_e_date)),
					
					html_entity_decode($Group->CountNSinceLastAttended(8,$str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->CommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date)),
					//html_entity_decode($Group->NonCommittedGrowerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->OrganiserAttendancesInPeriod($str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->RecorderAttendancesInPeriod($str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->FieldWorkerAttendancesInPeriod($str_safe_s_date,$str_safe_e_date)),
					
					html_entity_decode($Group->TotalAttendeesInPeriod($str_safe_s_date,$str_safe_e_date)),
					html_entity_decode($Group->TotalAttendancesInPeriod($str_safe_s_date,$str_safe_e_date))
					)
				)
				;
	}
	$pdf->Row(array('Totals:',$TMA_Total,$TMS_Total,$TFT_Total,$TCO_Total,$TCG_Total,$TNCG_Total,$TCGLA_Total,$TCA_Total,$TOA_Total,$TRA_Total,$TFA_Total,$TAees_Total,$TAces_Total));
	
	
	$pdf->Output();
}


?>