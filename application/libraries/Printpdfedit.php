<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
use setasign\Fpdi\Fpdi;
require_once APPPATH."/third_party/fpdf/fpdi/autoload.php";	
require_once APPPATH."/third_party/fpdf/fpdf/fpdf.php";		
class Printpdfedit extends Fpdi{
 	function __construct(){

 	}

 	function generatePDF($source, $output, $image,$typeprocess="D",$locationqr="topleft",$pageinsertqr="",$distanceleftright=5,$distancetopbottom=5,$width=15) {
		$pdf = new FPDI();
		// get the page count
		$pageCount = $pdf->setSourceFile($source);
		// iterate through all pages
		$pageinsert = array(1);
		if($pageinsertqr!=""){
			$pageinsertqr = explode(",", $pageinsertqr);
			if(count($pageinsertqr)>0){
				$pageinsert = $pageinsertqr;
			}
		}
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
		    // import a page
		    $templateId = $pdf->importPage($pageNo);
		    // get the size of the imported page
		    $size = $pdf->getTemplateSize($templateId);

		    // create a page (landscape or portrait depending on the imported page size)
		    if ($size[0] > $size[1]) {
		        $pdf->AddPage('L', array($size[0], $size[1]));
		    } else {
		        $pdf->AddPage('P', array($size[0], $size[1]));
		    }

		    // use the imported page
		    $pdf->useTemplate($templateId);

		    if(in_array($pageNo,$pageinsert)){
		    	if($locationqr=="topleft"){
			    	$pdf->Image($image,$distanceleftright,$distancetopbottom,$width,$width);
		    	}else if($locationqr=="topright"){
		    		$pdf->Image($image,$size[0]-$distanceleftright-$width,$distancetopbottom,$width,$width);
		    	}else if($locationqr=="bottomright"){
		    		$pdf->Image($image,$size[0]-$distanceleftright-$width,$size[1]-$distancetopbottom-$width,$width,$width);
		    	}else if($locationqr=="bottomleft"){
		    		$pdf->Image($image,$distanceleftright,$size[1]-$distancetopbottom-$width,$width,$width);
		    	}
			} 
		}

		$pdf->Output($output, $typeprocess);
		if($typeprocess=="F"){
			@unlink($source);
			@copy('./'.$output, $source);
			@unlink("./".$output);
		}
	}
	 
}