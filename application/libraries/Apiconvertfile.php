<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once  APPPATH . '/third_party/ConvertApi/autoload.php';
use \ConvertApi\ConvertApi;
class Apiconvertfile{
	function do_convert($config){

		ConvertApi::setApiSecret($config["apisecret"]);

		$result = ConvertApi::convert($config["totype"], array(
		        'File' => $config['fromfile']
		    ), $config["fromtype"]
		);
		
		$savedFiles = $result->saveFiles($config['todir']);

		return $savedFiles;
 	}
}

?>