<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*require_once APPPATH."/third_party/PHPWord.php"; 

class Word extends PHPWord { 
    public function __construct() { 
        parent::__construct(); 
    } 
}

*/

require_once  APPPATH . '/third_party/ConvertApi/autoload.php';
use \ConvertApi\ConvertApi;
class Wordtopdf{
	function do_convert($config){

 		ConvertApi::setApiSecret($config["apisecret"]);
 		
		$dir = sys_get_temp_dir();

		$result = ConvertApi::convert('pdf', [
		        'File' => $config['word'],
		    ], 'doc'
		);
		
		$savedFiles = $result->saveFiles($config['dir']);

		return $savedFiles;
 	}
}

?>