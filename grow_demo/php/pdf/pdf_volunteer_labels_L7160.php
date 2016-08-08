<?php
//require('PDF_Label.php');
require($BaseIncludeURL.'/FPDF17/PDF_Label.php');
/*------------------------------------------------
To create the object, 2 possibilities:
either pass a custom format via an array
or use a built-in AVERY name
------------------------------------------------*/

// Example of custom format
// $pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>2, 'NY'=>7, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));

// Standard format
$pdf = new PDF_Label('L7160');

$pdf->AddPage();

foreach( $LabelsFields as $Label )
{
	$text = sprintf("%s\n%s\n%s, %s %s", html_entity_decode($Label->GetName()), html_entity_decode($Label->GetAddress()), html_entity_decode($Label->GetSuburb()), html_entity_decode($Label->GetState()), html_entity_decode($Label->GetPostCode()));
    $pdf->Add_Label($text);
}


// Print labels
/*
for($i=1;$i<=20;$i++) {
    $text = sprintf("%s\n%s\n%s\n%s %s, %s", "Laurent $i", 'Immeuble Toto', 'av. Fragonard', '06000', 'NICE', 'FRANCE');
    $pdf->Add_Label($text);
}
*/

$pdf->Output();
?>