<?php
class Ciqrcode
{
	var $cacheable = true;
	var $cachedir = 'application/cache/';
	var $errorlog = 'application/logs/';
	var $quality = true;
	var $size = 1024;
	
	function __construct($config = array()) {

		include_once APPPATH."/third_party/qrcode/autoload.php";		
		$this->initialize($config);
	}
	
	public function initialize($config = array()) {
		$this->cacheable 	= (isset($config['cacheable'])) ? $config['cacheable'] : $this->cacheable;
		$this->cachedir 	= (isset($config['cachedir'])) ? $config['cachedir'] : FCPATH.$this->cachedir;
		$this->errorlog 	= (isset($config['errorlog'])) ? $config['errorlog'] : FCPATH.$this->errorlog;
		$this->quality 		= (isset($config['quality'])) ? $config['quality'] : $this->quality;
		$this->size 		= (isset($config['size'])) ? $config['size'] : $this->size;
		
		if (!defined('QR_CACHEABLE')) define('QR_CACHEABLE', $this->cacheable);
		if (!defined('QR_CACHE_DIR')) define('QR_CACHE_DIR', $this->cachedir);
		if (!defined('QR_LOG_DIR')) define('QR_LOG_DIR', $this->errorlog);
		if ($this->quality) {
			if (!defined('QR_FIND_BEST_MASK')) define('QR_FIND_BEST_MASK', true);
		} else {
			if (!defined('QR_FIND_BEST_MASK')) define('QR_FIND_BEST_MASK', false);
			if (!defined('QR_DEFAULT_MASK')) define('QR_DEFAULT_MASK', $this->quality);
		}
		if (!defined('QR_FIND_FROM_RANDOM')) define('QR_FIND_FROM_RANDOM', false);	
		if (!defined('QR_PNG_MAXIMUM_SIZE')) define('QR_PNG_MAXIMUM_SIZE',  $this->size);
	}
	
	public function generate($params = array()) {
		$now 			= microtime(TRUE);
		$expiration 	= 360;
		$img_path 		= "resources/qrcode/";
		
		$current_dir = @opendir($img_path);
		while ($filename = @readdir($current_dir))
		{
			if (in_array(substr($filename, -4), array('.png'))
				&& (str_replace(array('.png'), '', $filename) + $expiration) < $now)
			{
				@unlink("./".$img_path.$filename);
			}
		}
		@closedir($current_dir);
		
		$imagename 		= $now.".png";
		$level 			= (isset($params['level']) && in_array($params['level'], array('L','M','Q','H'))) ? $params['level'] : "H";
		$size 			= isset($params['size']) ? min(max((int)$params['size'], 1), 10) : 10;
		$padding 		= isset($params['padding'])?$params['padding']:0;
		$data  			= (isset($params['data'])) ? $params['data'] : 'QR Code';
		QRcode::png($data, "./".$img_path.$imagename, $level, $size, $padding);
		return $img_path.$imagename;
	}
}
