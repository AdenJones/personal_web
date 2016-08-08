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
		$this->Cell(0,10,$Heading,0,1,'L');
		//$this->SetX(65);
		//$this->Cell(0,10,$DatesString,0,1,'L');
		//$this->SetY(35);
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

if( !$bad_report )
{
	$pdf = new FPDF();
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,10,'Bad URL values submitted!' ,0 ,0 ,'C');
	$pdf->Output();
} elseif(!$bad_user)
{ 
	$pdf = new FPDF();
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,10,'You do not have access to this report!' ,0 ,0 ,'C');
	$pdf->Output();
} else {
	
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('Calibri');
	$pdf->AddFont('Calibri','B','calibrib.php');
	$pdf->AddPage();
	
	$pdf->SetFont('Calibri','B',13);
	
	foreach ($ReportFields as $Field) 
	{
		$pdf->Cell(0,7,$Field->GetFieldName().': '.$Field->GetFieldValue(),0,1,'L');
		
	}
	
	$pdf->Output();

}


?>