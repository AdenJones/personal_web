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
	$pdf->Cell(0,10,'Bad URL values submitted!' ,0 ,1 ,'C');
	
	foreach( $arr_branches as $thisBranch)
	{
		$pdf->Cell(0,10,$thisBranch['fld_branch_name'] ,0 ,1 ,'L');
	}
	//$pdf->Cell(0,10,var_dump($arrErrors) ,0 ,0 ,'C');
	
	
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
	
	foreach( $GroupsStats as $Stat )
	{
		
		
		
		$TMA_Total += $Stat['MA'];
		$TMS_Total += $Stat['MESCH'];
		
		$TFT_Total += $Stat['TFT'];
		$TCO_Total += $Stat['ComObs'];
		$TCG_Total += $Stat['ComGrow'];
		$TNCG_Total += $Stat['NewComGrow'];
		
		$TCGLA_Total += $Stat['CGLapsed'];
		$TCA_Total += $Stat['CGAttendances'];
		$TOA_Total += $Stat['OrgAtt'];
		$TRA_Total += $Stat['RecAtt'];
		$TFA_Total += $Stat['FWAtt'];
		
		$TAees_Total += $Stat['TotAttees'];
		$TAces_Total += $Stat['TotAtes'];
		
		$pdf->Row(
					array(
					html_entity_decode($Stat['Name']),
					html_entity_decode($Stat['MA']),
					//html_entity_decode($Stat['MAName'].': '.$Stat['MA']),
					html_entity_decode($Stat['MESCH']),
					//html_entity_decode($Stat['MESCHName'].': '.$Stat['MESCH']),
					
					html_entity_decode($Stat['TFT']),
					//html_entity_decode($Stat['TFTName'].': '.$Stat['TFT']),
					html_entity_decode($Stat['ComObs']),
					//html_entity_decode($Stat['ComObsName'].': '.$Stat['ComObs']),
					html_entity_decode($Stat['ComGrow']),
					//html_entity_decode($Stat['ComGrowName'].': '.$Stat['ComGrow']),
					html_entity_decode($Stat['NewComGrow']),
					//html_entity_decode($Stat['NewComGrowName'].': '.$Stat['NewComGrow']),
					
					html_entity_decode($Stat['CGLapsed']),
					//html_entity_decode($Stat['CGLapsedName'].': '.$Stat['CGLapsed']),
					html_entity_decode($Stat['CGAttendances']),
					//html_entity_decode($Stat['CGAttendancesName'].': '.$Stat['CGAttendances']),
					html_entity_decode($Stat['OrgAtt']),
					//html_entity_decode($Stat['OrgAttName'].': '.$Stat['OrgAtt']),
					html_entity_decode($Stat['RecAtt']),
					//html_entity_decode($Stat['RecAttName'].': '.$Stat['RecAtt']),
					html_entity_decode($Stat['FWAtt']),
					//html_entity_decode($Stat['FWAttName'].': '.$Stat['FWAtt']),
					
					html_entity_decode($Stat['TotAttees']),
					//html_entity_decode($Stat['TotAtteesName'].': '.$Stat['TotAttees']),
					html_entity_decode($Stat['TotAtes'])
					//html_entity_decode($Stat['TotAtesName'].': '.$Stat['TotAtes'])
					)
				)
				;
	}
	$pdf->Row(array('Totals:',$TMA_Total,$TMS_Total,$TFT_Total,$TCO_Total,$TCG_Total,$TNCG_Total,$TCGLA_Total,$TCA_Total,$TOA_Total,$TRA_Total,$TFA_Total,$TAees_Total,$TAces_Total));
	
	
	$pdf->Output();
}


?>