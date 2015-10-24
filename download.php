<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('inc/MysqliDb.php');
require_once('inc/tcpdf/tcpdf.php');
date_default_timezone_set('America/Chicago');
class MYPDF extends TCPDF {
	public $version = '1.0';
	public $date = '';
	public function setVersion($num){
		$this->version = $num;
	}
	public function setDate($num){
		$this->date = $num;
	}
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(60, 10, $this->date, 0, false, 'L', 0, '', 0, false, 'T', 'M');   
		$this->Cell(60, 10, 'V '.$this->version, 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
		$this->Cell(60, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}
$db = new MysqliDb();
$db->where('id',$_GET['id']);
$history = $db->getOne ('history');

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
	$pdf->setVersion((string)$_GET['version'].".0");
	$pdf->setDate($_GET['date']);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Ignitor Labs');

	$pdf->setHeaderFont(false);
	$pdf->setPrintHeader(false);
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}

	// ---------------------------------------------------------

	// add a page
	$pdf->AddPage();

	// set font
	$pdf->SetFont('times', 'BI', 20, '', 'false');

	//$pdf->Write(0, 'Example of HTML Justification', '', 0, 'L', true, 0, false, false, 0);

	// create some HTML content
	$html = $history['content'];
	// set core font
	$pdf->SetFont('helvetica', '', 10);

	// output the HTML content
	$pdf->writeHTML($html, true, 0, true, true);

	// ---------------------------------------------------------

	//Close and output PDF document
	$pdf->Output($_GET['name']."v".$_GET['version'].'.pdf', 'I');

?>