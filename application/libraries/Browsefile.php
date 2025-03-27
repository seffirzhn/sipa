<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH."/third_party/elfinder/autoload.php";
class Browsefile
{

	function elfinderAccess($attr, $path, $data, $volume, $isDir, $relpath) {
		$basename = basename($path);
		return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
				 && strlen($relpath) !== 1           // but with out volume root
			? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
			:  null;                                 // else elFinder decide it itself
	}
	
	public function __construct() {
        elFinder::$netDrivers['ftp'] = 'FTP';
		$opts = array( 
//                'debug' => true, 
                'roots' => array( 
                        array( 
                            'driver'        => 'LocalFileSystem', 
                            'path'          => realpath('resources/image/'), 
                            'URL'           => base_url('resources/image/'),
                            'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
                            'winHashFix'    => DIRECTORY_SEPARATOR !== '/',
                            'alias'         => "Home", 
                            'uploadDeny'    => array('all'), 
                            'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png'),
                            'uploadOrder'   => array('deny', 'allow'), 
                            'accessControl' => array($this, 'elfinderAccess'), 
                            'attributes'    => array(
                                                    array( // hide readmes
                                                        'pattern'   => '/.html|.tmb|.htaccess|.quarantine/',
                                                        'hidden'    => true,
                                                    ),
                                                )

                        ),
                ), 
        );
	    $connector = new elFinderConnector(new elFinder($opts));
	    $connector->run();
	}
}