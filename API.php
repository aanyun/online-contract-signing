<?php
require_once('inc/MysqliDb.php');
require_once('inc/tcpdf/tcpdf.php');
require_once('inc/Encryption.php');
if(!isset($_GET['c'])||$_GET['c']==""){
	echo "Error";
	return;
}
class MYPDF extends TCPDF {
	public $version = '1.0';
	public function setVersion($num){
		if (!is_null($num)&&$num!="")
			$this->version = $num;
	}
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(60, 10, date('m/d/Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');   
		$this->Cell(60, 10, 'V '.$this->version, 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
		$this->Cell(60, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}
date_default_timezone_set('America/Chicago');
switch ($_GET['c']) {
	case 'signin':
		if($_POST['password'] == "chicago123")
			echo 1;
		else echo 0;
		break;
	case 'save':
		if($_POST['content']==''||$_POST['client']=='') 
			return;
		$db = new MysqliDb();
		$folder_name = $_POST['client'].date('YmdHis');
		$data = array(
			'content'=>json_encode($_POST['content']),
			'client'=>$_POST['client'],
			'date'=>date('Y-m-d H:i:s'),
			'client_email'=>$_POST['client_email'],
			'ignitor_email'=>$_POST['ignitor_email'],
			'ignitor_title'=>$_POST['ignitor_title'],
			'ignitor_name'=>$_POST['ignitor_name'],
			'update_date'=>date('Y-m-d H:i:s'),
			'folder'=>$folder_name
			);
		$id = $db->insert ('contracts', $data);
		mkdir('assets/contracts/'.$folder_name, 0777, true);
		foreach($_POST['content'] as $contract) {
			printToServer($contract['content'],$contract['name'],$_POST['client'],$folder_name,"1.0");
		}
		if ($id) {
			$encrypt = new Encryption();
			$link = $encrypt->encode($id);
			echo json_encode(array( "link"=>$link, "folder"=>$folder_name));
		} else {
			echo 0;
		}
		break;
	case 'remove':
		$db = new MysqliDb();
		$db->where('id', $_POST['id']);
		$contract = $db->getOne ('contracts');
		if($contract['folder']!=null){
			rrmdir('assets/contracts/'.$contract['folder']);
			unlink('assets/contracts/'.$contract['folder'].'.zip');
		}
		$db->where('id', $_POST['id']);
		echo $db->delete('contracts');
		break;
	case 'saveAsTemplate':
		$db = new MysqliDb();
		$data = array(
			'des'=>$_POST['description'],
			'title'=>$_POST['title'],
			'type'=>$_POST['type'],
			'content'=>$_POST['content'],
			'date'=>date('Y-m-d H:i:s')
			);
		$id = $db->insert ('template', $data);
		echo $id;
		break;
	case 'getTemplate':
		$db = new MysqliDb();
		$cols = array("id", "title", "des");
		$db->where('type',$_POST['type']);
		$templates = $db->get ('template',null, $cols);
		echo json_encode($templates);
		break;
	case 'getTemplateContent':
		$db = new MysqliDb();
		$db->where('id',$_POST['id']);
		$templates = $db->getOne ('template');
		echo json_encode($templates);
		break;
	case 'sign':
		$db = new MysqliDb();
		//$db->where('id',$_GET['id']);

		$data = array(
			'type'=>$_POST['type'],
			'contract_id'=>$_GET['id'],
			'client_name'=>$_POST['fullName'],
			'client_title'=>$_POST['title'],
			'client_initial'=>$_POST['initial'],
			'signiture'=>$_POST['signature'],
			'signiture_date'=>$_POST['date'],
			'date'=>date('Y-m-d H:i:s')
			);
		$id = $db->insert ('signitures', $data);
		if ($id) {
			$db->where('id',$_GET['id']);
			if ($_POST['type'] == 1)  {
				$data =  array('first_signed'=>1);
				printToServer($_POST['contract'],'Mutual Confidentiality and Non-Disclosure Agreement',$_POST['client'],$_POST['folder']);
			} else {
				$data =  array('sec_signed'=>1);
				printToServer($_POST['contract'],'eContent Development Agreement for Manufacturers',$_POST['client'],$_POST['folder']);
			}
			$templates = $db->update ('contracts',$data);
			systemEmail($_GET['id']);
			echo json_encode($templates);
		} else echo "false";
		
		
		break;
	case 'initial':
		$db = new MysqliDb();
		$db->where('id',$_GET['id']);
		$data = array(			
			'client_name'=>$_POST['fullName'],
			'client_title'=>$_POST['title'],
			'client_email'=>$_POST['email'],
			'client_company'=>$_POST['company'],
			);
		$templates = $db->update ('contracts',$data);
		session_start();
		$_SESSION['login'] = $_POST['client'];
		echo json_encode($templates);
		break;
	case 'change':
		$db = new MysqliDb();
		$db->where('id',$_GET['id']);
		$encrypt = new Encryption();
		$link = "http://".$_SERVER['HTTP_HOST']."/contract_gen/contract.php?id=".$encrypt->encode($_GET['id']);
		//systemStatusUpdateEmail($_GET['id'],"hello");
		$contract_info = $db->getOne ('contracts');
		$data = array(			
			'content'=>json_encode($_POST['content']),
			'update_date'=>date('Y-m-d H:i:s')
			);
		$newContracts = $_POST['content'];
		$db->where('id',$_GET['id']);
		$templates = $db->update ('contracts',$data);
		foreach($_POST['changedContracts'] as $index) {
			$db->where('contract_id',$_GET['id']);
			$db->where('doc_id',$index);
			$history = $db->getOne ('vwhistoryRecentVersion');
			if ($history){
				$version = $history['version']+1;
			} else {
				$version = 1;
			}
			printToServer($newContracts[$index]['content'],$newContracts[$index]['name'],$_POST['client'],$contract_info['folder'],(string)$version.".0");
		}
		if($templates)
		echo $link;
		else echo 0;
		break;
	case 'check':
		$db = new MysqliDb();
		$db->where('id',$_GET['id']);
		$templates = $db->getOne ('contracts');
		if ($templates['client_email'] == $_POST['email']){
			session_start();
			$_SESSION['login'] = $templates['client'];
			echo 1;
		} else echo 0;
		break;
	case 'getContract':
		$encrypt = new Encryption();
		$id = $encrypt->decode($_GET['id']);
		$db = new MysqliDb();
		$db->where('id',$id);
		$contracts = $db->getOne ('contracts');
		echo json_encode($contracts);
		break;
	case 'getContractByClient':
		$encrypt = new Encryption();
		$id = $encrypt->decode($_GET['id']);
		$db = new MysqliDb();
		$db->where('id',$id);
		$cols=array("id","ignitor_name","ignitor_title","client","ignitor_email","client_name","client_title","client_company","client_name is not null as registed");
		$contracts = $db->get('contracts',null,$cols);
		echo json_encode($contracts);
		break;
	case 'saveToHistory':
		$db = new MysqliDb();
		//$db->where('id',$_GET['id']);
		$data = array(
			'doc_id'=>$_POST['index'],
			'contract_id'=>$_POST['id'],
			'content'=>$_POST['content'],
			'date'=>date('Y-m-d H:i:s')
			);
		$id = $db->insert ('history', $data);
		break;
	case 'getContractContent':
		$db = new MysqliDb();
		$db->where('id',$_GET['id']);
		$contracts = $db->getOne ('contracts');
		echo json_encode($contracts);
		break;
	case 'getHistory':
		$db = new MysqliDb();
		$history = $db->rawQuery("SELECT * FROM history WHERE contract_id = ? AND doc_id = ? order by date desc", array($_POST['id'],$_POST['index']));
		echo json_encode($history);
		break;
	case 'getHistoryId':
		$db = new MysqliDb();
		$history = $db->rawQuery("SELECT id,create_date FROM vwhistory WHERE contract_id = ? AND doc_id = ? order by id desc", array($_POST['id'],$_POST['index']));
		echo json_encode($history);
		break;
	case 'saveStatus':
		$db = new MysqliDb();
		$db->where('id',$_GET['id']);
		$data = array(			
			'initialed'=>implode(",",$_POST['status'])
			);
		$templates = $db->update ('contracts',$data);
		echo json_encode($data);
		break;	
	default:
		# code...
		break;
}

function printToServer($content,$name,$client,$folder_name,$version){
	//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
	$pdf->setVersion($version);
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
	$html = $content;
	// set core font
	$pdf->SetFont('helvetica', '', 10);

	// output the HTML content
	$pdf->writeHTML($html, true, 0, true, true);

	// ---------------------------------------------------------

	//Close and output PDF document
	$pdf->Output('assets/contracts/'.$folder_name.'/'.$client.'_'.$name.'.pdf', 'F');
}

function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
}

function systemEmail($id){
	$db = new MysqliDb();
	$db->where('id',$_GET['id']);
	$contract = $db->getOne ('contracts');
	$to = $contract['ignitor_email'];
	if ($contract['sec_signed'] == 1)  {
		$step =2;
		$message = " They have agreed to the eContent Development Agreement (eCDA). ";
		$message2 = "Congratulations! You have completed the first steps to creating your eContent with Ignitor Labs!\n
					The final paperwork will be reviewed and you will be contacted if we have any questions or concerns. An Ignitor Labs Instructional Designer will be contacting you with a link to your Project Tracker.  If you have any questions or concerns, please call us at (312)789-4170.\n
					Thank you for choosing Ignitor Labs!
					";
	} else if ($contract['first_signed'] == 1){
		$step =1;
		$message = " They have agreed to the Non-Disclosure Agreement (NDA) and acknowledged that they will submit the Source Materials Checklist. ";
		$message2 = "The signed NDA has been sent to Ignitor Labs. The next step is to send the source materials (requested in the Source Materials Checklist). Once all available materials have been received and approved, we will use them to outline the scope of the project in an eContent Design Document (eCDD). You will receive an email when the new eCDD has been drafted, so that you can read and sign all remaining documents including the eCDD and the eContent Design Agreement (eCDA). Please note that the eCDA is a legally binding document. Once signed, all parties are contractually obligated to uphold the terms therein.";
	}
	$subject = "Step ".$step." of the eContract has been signed by ".$contract['client'].".";
	
	$message = $subject.$message."Step ".$step." was completed by ".
		$contract['client_name'].", with the title of ".
		$contract['client_title'].", ".
		"on ".date('M jS Y h:i a').".";
	$headers = 'From: support@ignitorlabs.com' . "\r\n" .
	    'Reply-To: '.$contract['client_email']. "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);


	//client 

	$to = $contract['client_email'];
	$subject = $contract['client']." has finished Step ".$step." of the eContract";
	$headers = 'From: support@ignitorlabs.com' . "\r\n" .
	    'Reply-To: '.$contract['ignitor_email']. "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message2, $headers);
}

function systemStatusUpdateEmail($id,$message){
	$db = new MysqliDb();
	$db->where('id',$_GET['id']);
	$contract = $db->getOne ('contracts');
	$to = $contract['client_email'];
	$subject = "";
	$encrypt = new Encryption();
	$link = $encrypt->encode($id);
	$message = "Updates have been made to one or more of your documents. Please click the link below to review and approve the changes.\n http://".$_SERVER['HTTP_HOST']."/contract_gen/contract.php?id=".$link;
	$headers = 'From: support@ignitorlabs.com' . "\r\n" .
	    'Reply-To: '.$contract['ignitor_email']. "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
}
?>