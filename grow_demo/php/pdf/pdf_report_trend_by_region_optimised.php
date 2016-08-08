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
	
	$TMA_Total = 0;
	$TCL_Total = 0;
	$TSPC_Total = 0;
	$TOT_Total = 0;
	$TFT_Total = 0;
	$TCO_Total = 0;
	$TCG_Total = 0;
	$TNC_Total = 0;
	$TCGL_Total = 0;
	$TCGA_Total = 0;
	$TCGAA_Total = 0;
	$GWO_Total = 0;
	$GWR_Total = 0;
	$GF_Total = 0;
	$FWA_Total = 0;
	
	foreach( $TrendStats as $Stat )
	{
		
		$TMA_Total += $Stat['MA'];
		$TCL_Total += $Stat['CL'];
		$TSPC_Total += $Stat['SPC'];
		$TOT_Total += $Stat['OT'];
		$TFT_Total += $Stat['FT'];
		$TCO_Total += $Stat['CO'];
		$TCG_Total += $Stat['CG'];
		$TNC_Total += $Stat['NC'];
		$TCGL_Total += $Stat['CGL']; //remember to add count of org and rec
		$TCGA_Total += $Stat['CGA'];
		$TCGAA_Total += $Stat['CGAA'];
		$GWO_Total += $Stat['GWO'];
		$GWR_Total += $Stat['GWR'];
		$GF_Total += $Stat['GF'];
		$FWA_Total += $Stat['FWA'];
		
		$pdf->Row(
					array(
					html_entity_decode($Stat['Name']),
					
					//html_entity_decode($Stat['MAName'].': '.$Stat['MA']),
					html_entity_decode($Stat['MA']),
					
					//html_entity_decode($Stat['CLName'].': '.$Stat['CL']),
					html_entity_decode($Stat['CL']),

					//html_entity_decode($Stat['SPCName'].': '.$Stat['SPC']),
					html_entity_decode($Stat['SPC']),
					
					//html_entity_decode($Stat['OTName'].': '.$Stat['OT']),
					html_entity_decode($Stat['OT']),
					
					//html_entity_decode($Stat['FTName'].': '.$Stat['FT']),
					html_entity_decode($Stat['FT']),
					
					//html_entity_decode($Stat['COName'].': '.$Stat['CO']),
					html_entity_decode($Stat['CO']),
					
					//html_entity_decode($Stat['CGName'].': '.$Stat['CG']),
					html_entity_decode($Stat['CG']),
					
					//html_entity_decode($Stat['NCName'].': '.$Stat['NC']),
					html_entity_decode($Stat['NC']),
					
					//html_entity_decode($Stat['CGLName'].': '.$Stat['CGL']),
					html_entity_decode($Stat['CGL']),
					
					//html_entity_decode($Stat['CGAName'].': '.$Stat['CGA']),
					html_entity_decode($Stat['CGA']),
					
					//html_entity_decode($Stat['CGAAName'].': '.$Stat['CGAA']),
					html_entity_decode($Stat['CGAA']),
					
					//html_entity_decode($Stat['GWOName'].': '.$Stat['GWO']),
					html_entity_decode($Stat['GWO']),
					
					//html_entity_decode($Stat['GWRName'].': '.$Stat['GWR']),
					html_entity_decode($Stat['GWR']),
					
					//html_entity_decode($Stat['GFName'].': '.$Stat['GF']),
					html_entity_decode($Stat['GF']),
					
					//html_entity_decode($Stat['FWAName'].': '.$Stat['FWA'])
					html_entity_decode($Stat['FWA']),
					
					)
				)
				;
	}
	
	$pdf->Row(array('Totals:',$TMA_Total,$TCL_Total,$TSPC_Total,$TOT_Total,$TFT_Total,$TCO_Total,$TCG_Total,$TNC_Total,$TCGL_Total,$TCGA_Total,($TCGAA_Total / count($TrendStats)),$GWO_Total,$GWR_Total,$GF_Total,$FWA_Total));
	$pdf->Output();
}


?>