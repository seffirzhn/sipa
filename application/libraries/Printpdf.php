<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class Printpdf{
 	function __construct(){

 	}

 	function do_print($config){

 		if(empty($config["typecharacter"])){
 			$config['typecharacter']="utf-8";
 		}
 		if(empty($config["paperorientation"])){
 			$config['paperorientation']="A4-P";
 		}
 		if(empty($config["htmlcontent"])){
 			$config['htmlcontent']="";
 		}
 		if(empty($config["namefile"])){
 			$config['namefile']="sample.pdf";
 		}
 		if(empty($config["typeprocess"])){
 			$config['typeprocess']="D";
 		}
 		require_once APPPATH."/third_party/mpdf/vendor/autoload.php";		
 		$mpdf = new \Mpdf\Mpdf(array('tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf','mode' => $config["typecharacter"], 'format' => $config['paperorientation'],'orientation'=>'L'));
    	if(!empty($config["htmlfooter"])){
        	$mpdf->SetHTMLFooter($config['htmlfooter']);
    	}
    	$mpdf->WriteHTML($config['htmlcontent']);
        $mpdf->output($config["namefile"],$config['typeprocess']);
 	}
}